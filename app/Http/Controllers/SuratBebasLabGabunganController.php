<?php
namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\User;
use App\Notifications\SuratBebasLabNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SuratBebasLabGabunganController extends Controller
{
    /**
     * Terbitkan surat bebas lab GABUNGAN untuk semua peminjaman
     * satu mahasiswa yang berstatus Dikembalikan.
     * Route: GET /surat-bebas-lab-gabungan/{nim}/terbitkan
     */
    public function terbitkanDanDownload(string $nim)
    {
        $peminjamans = Peminjaman::with('peminjamable')
            ->where('nim_peminjam', $nim)
            ->where('status', 'Dikembalikan')
            ->get();

        if ($peminjamans->isEmpty()) {
            abort(404, 'Tidak ada peminjaman yang sudah dikembalikan untuk NIM ini.');
        }

        $now = now();
        foreach ($peminjamans as $p) {
            if (!$p->surat_bebas_lab_diterbitkan) {
                $p->update([
                    'surat_bebas_lab_diterbitkan'    => true,
                    'surat_bebas_lab_diterbitkan_at' => $now,
                ]);
            }
        }

        // Kirim notifikasi ke mahasiswa menggunakan user_id
        $first = $peminjamans->first();
        $user  = User::find($first->user_id);
        if ($user) {
            $user->notify(new SuratBebasLabNotification($first));
        }

        return $this->generatePdf($peminjamans, $now);
    }

    /**
     * Download ulang surat bebas lab gabungan
     * Route: GET /surat-bebas-lab-gabungan/{nim}
     */
    public function download(string $nim)
    {
        $peminjamans = Peminjaman::with('peminjamable')
            ->where('nim_peminjam', $nim)
            ->where('status', 'Dikembalikan')
            ->where('surat_bebas_lab_diterbitkan', true)
            ->get();

        if ($peminjamans->isEmpty()) {
            abort(404, 'Surat Bebas Lab gabungan belum diterbitkan untuk NIM ini.');
        }

        $tanggalTerbit = $peminjamans->first()->surat_bebas_lab_diterbitkan_at;
        return $this->generatePdf($peminjamans, $tanggalTerbit);
    }

    private function generatePdf($peminjamans, $tanggalTerbit)
    {
        $first = $peminjamans->first();

        $pdf = Pdf::loadView('pdf.surat_bebas_lab_gabungan', [
            'peminjamans'    => $peminjamans,
            'nama_peminjam'  => $first->nama_peminjam,
            'nim_peminjam'   => $first->nim_peminjam,
            'tanggal_terbit' => $tanggalTerbit,
        ])->setPaper('a4', 'portrait');

        $namaFile = 'Surat_Bebas_Lab_'
            . str_replace(' ', '_', $first->nama_peminjam)
            . '_' . Carbon::now()->format('Ymd')
            . '.pdf';

        return $pdf->download($namaFile);
    }
}