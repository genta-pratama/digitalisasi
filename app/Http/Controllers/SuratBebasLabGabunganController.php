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
     * dalam satu nomor_surat yang berstatus Dikembalikan.
     *
     * Route: GET /surat-bebas-lab-gabungan/{nim}/terbitkan?nomor_surat=xxx
     */
    public function terbitkanDanDownload(string $nim)
    {
        // FIX: filter by nomor_surat agar tidak tercampur dengan
        // surat lain milik mahasiswa yang sama
        $nomorSurat = request()->query('nomor_surat');

        $query = Peminjaman::with('peminjamable')
            ->where('nim_peminjam', $nim)
            ->where('status', 'Dikembalikan');

        if ($nomorSurat) {
            $query->where('nomor_surat', $nomorSurat);
        }

        $peminjamans = $query->get();

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

        // FIX: cari user by user_id (bukan nama), dan kirim notifikasi
        // dengan isGabungan=true agar link download di email mengarah
        // ke surat gabungan, bukan ke satu barang saja
        $first = $peminjamans->first();
        $user  = User::find($first->user_id);

        if ($user) {
            $user->notify(new SuratBebasLabNotification($first, isGabungan: true));
        }

        return $this->generatePdf($peminjamans, $now);
    }

    /**
     * Download ulang surat bebas lab gabungan.
     *
     * Route: GET /surat-bebas-lab-gabungan/{nim}?nomor_surat=xxx
     */
    public function download(string $nim)
    {
        // FIX: filter by nomor_surat agar tidak tercampur
        $nomorSurat = request()->query('nomor_surat');

        $query = Peminjaman::with('peminjamable')
            ->where('nim_peminjam', $nim)
            ->where('status', 'Dikembalikan')
            ->where('surat_bebas_lab_diterbitkan', true);

        if ($nomorSurat) {
            $query->where('nomor_surat', $nomorSurat);
        }

        $peminjamans = $query->get();

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