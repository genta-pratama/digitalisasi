<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\BahanPadat;
use App\Models\BahanCairanLama;
use App\Models\Peminjaman;
use App\Notifications\SuratPeminjamanNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PinjamController extends Controller
{
    public function create()
    {
        // Kalau belum login, redirect ke halaman login dulu
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $alats              = Alat::orderBy('nama')->get();
        $bahan_padats       = BahanPadat::orderBy('nama')->get();
        $bahan_cairan_lamas = BahanCairanLama::orderBy('nama')->get();

        return view('pinjam', compact('alats', 'bahan_padats', 'bahan_cairan_lamas'));
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $validatedData = $request->validate([
            'nama_peminjam'         => 'required|string|max:255',
            'nim_peminjam'          => 'required|string|max:255',
            'no_hp'                 => ['required', 'string', 'regex:/^\+62[0-9]{9,15}$/'],
            'items'                 => 'required|array|min:1',
            'items.*.item_id'       => 'required|integer',
            'items.*.item_type'     => 'required|string',
            'items.*.jumlah_pinjam' => 'required|numeric|min:0.01',
        ], [
            'no_hp.regex' => 'Format Nomor HP tidak valid. Harap masukkan nomor yang benar (contoh: 81234567890).',
        ]);

        $nim   = $validatedData['nim_peminjam'];
        $nama  = $validatedData['nama_peminjam'];
        $no_hp = $validatedData['no_hp'];

        // 2. Cek peminjaman aktif per nomor_surat
        $nomorSuratAktif = Peminjaman::where(function ($query) use ($nim, $nama, $no_hp) {
                $query->where('nim_peminjam', $nim)
                    ->orWhere('nama_peminjam', $nama)
                    ->orWhere('no_hp', $no_hp);
            })
            ->whereIn('status', ['Menunggu Persetujuan', 'Disetujui'])
            ->distinct('nomor_surat')
            ->pluck('nomor_surat');

        $peminjamanAktif = $nomorSuratAktif->isNotEmpty();

        if ($peminjamanAktif) {
            return redirect()->back()
                ->withErrors(['peminjaman_aktif' => 'Anda masih memiliki peminjaman yang sedang aktif atau menunggu persetujuan.'])
                ->withInput();
        }

        // 3. Generate nomor surat untuk batch peminjaman ini
        $nomorSurat = $this->generateNomorSurat();

        // 4. Proses transaksi database
        $peminjamanIds = [];

        DB::transaction(function () use ($validatedData, $request, $nomorSurat, &$peminjamanIds) {
            $allowedModels = [
                'Alat'            => \App\Models\Alat::class,
                'BahanPadat'      => \App\Models\BahanPadat::class,
                'BahanCairanLama' => \App\Models\BahanCairanLama::class,
            ];

            foreach ($validatedData['items'] as $index => $itemData) {

                if (!isset($allowedModels[$itemData['item_type']])) {
                    throw ValidationException::withMessages([
                        'items.' . $index . '.item_type' => 'Tipe barang tidak valid.'
                    ]);
                }

                $modelClass    = $allowedModels[$itemData['item_type']];
                $jumlah_pinjam = $itemData['jumlah_pinjam'];

                $item = $modelClass::where('id', $itemData['item_id'])->lockForUpdate()->firstOrFail();

                $stokTersedia = ($itemData['item_type'] === 'Alat') ? $item->stok : $item->sisa_bahan;

                if ($jumlah_pinjam > $stokTersedia) {
                    throw ValidationException::withMessages([
                        'items.' . $index . '.jumlah_pinjam' => 'Stok untuk ' . $item->nama . ' tidak cukup (sisa: ' . $stokTersedia . ').'
                    ]);
                }

                $peminjaman = Peminjaman::create([
                    'user_id'           => Auth::id(), // ✅ Simpan user_id
                    'nama_peminjam'     => $request->nama_peminjam,
                    'nim_peminjam'      => $request->nim_peminjam,
                    'no_hp'             => $validatedData['no_hp'],
                    'peminjamable_id'   => $itemData['item_id'],
                    'peminjamable_type' => $modelClass,
                    'jumlah'            => $jumlah_pinjam,
                    'status'            => 'Menunggu Persetujuan',
                    'nomor_surat'       => $nomorSurat,
                    'tanggal_pinjam'    => Carbon::today(),
                ]);

                $peminjamanIds[] = $peminjaman->id;

                if ($itemData['item_type'] === 'Alat') {
                    $item->decrement('stok', $jumlah_pinjam);
                } else {
                    $item->decrement('sisa_bahan', $jumlah_pinjam);
                }
            }
        });

        // 5. Kirim notifikasi ke user yang sedang login
        if (Auth::check()) {
            $semuaItem = Peminjaman::whereIn('id', $peminjamanIds)
                ->with('peminjamable')
                ->get();

            Auth::user()->notify(new SuratPeminjamanNotification(
                $semuaItem,
                $nomorSurat,
                $request->nama_peminjam,
                $request->nim_peminjam
            ));
        }

        return back()->with([
            'success'        => 'Permintaan peminjaman untuk ' . count($request->items) . ' barang telah berhasil dikirim!',
            'nomor_surat'    => $nomorSurat,
            'peminjaman_ids' => $peminjamanIds,
        ]);
    }

    /**
     * Download surat peminjaman PDF berdasarkan nomor surat
     */
    public function downloadSurat(string $nomorSurat)
    {
        $nomorSurat = urldecode($nomorSurat);

        $peminjamans = Peminjaman::with('peminjamable')
            ->where('nomor_surat', $nomorSurat)
            ->get();

        if ($peminjamans->isEmpty()) {
            abort(404, 'Surat tidak ditemukan di database.');
        }

        $pertama = $peminjamans->first();

        $pdf = Pdf::loadView('pdf.surat_peminjaman', [
            'peminjamans'    => $peminjamans,
            'nomor_surat'    => $nomorSurat,
            'nama_peminjam'  => $pertama->nama_peminjam,
            'nim_peminjam'   => $pertama->nim_peminjam,
            'no_hp'          => $pertama->no_hp,
            'tanggal_pinjam' => Carbon::parse($pertama->tanggal_pinjam)->translatedFormat('d F Y'),
            'tanggal_cetak'  => Carbon::now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        $namaFile = 'Surat_Peminjaman_' . str_replace('/', '-', $nomorSurat) . '.pdf';

        return $pdf->download($namaFile);
    }

    /**
     * Generate nomor surat otomatis
     * Format: SP/LAB-KIM/02/001/2026
     */
    private function generateNomorSurat(): string
    {
        $tahun = Carbon::now()->year;
        $bulan = Carbon::now()->format('m');

        $urutan = Peminjaman::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->whereNotNull('nomor_surat')
            ->distinct('nomor_surat')
            ->count('nomor_surat') + 1;

        return sprintf('SP/LAB-KIM/%s/%03d/%s', $bulan, $urutan, $tahun);
    }
}