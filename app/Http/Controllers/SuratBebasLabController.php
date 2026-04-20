<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Notifications\SuratBebasLabNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SuratBebasLabController extends Controller
{
    /**
     * Download Surat Bebas Lab oleh admin (download ulang)
     * Route: GET /surat-bebas-lab/{id}
     */
    public function download(int $id)
    {
        $peminjaman = Peminjaman::with('peminjamable')->findOrFail($id);

        if (!$peminjaman->surat_bebas_lab_diterbitkan) {
            abort(403, 'Surat Bebas Lab belum diterbitkan oleh admin.');
        }

        return $this->generatePdf($peminjaman);
    }

    /**
     * Terbitkan + langsung download PDF oleh admin
     * Route: GET /surat-bebas-lab/{id}/terbitkan
     */
    public function terbitkanDanDownload(int $id)
    {
        $peminjaman = Peminjaman::with('peminjamable')->findOrFail($id);

        if (!$peminjaman->surat_bebas_lab_diterbitkan) {
            $peminjaman->update([
                'surat_bebas_lab_diterbitkan'    => true,
                'surat_bebas_lab_diterbitkan_at' => now(),
            ]);

            $user = \App\Models\User::where('name', $peminjaman->nama_peminjam)->first();
            if ($user) {
                $user->notify(new SuratBebasLabNotification($peminjaman));
            }
        }

        return $this->generatePdf($peminjaman);
    }

    /**
     * Generate PDF pakai DomPDF + blade template
     */
    private function generatePdf(Peminjaman $peminjaman)
    {
        $pdf = Pdf::loadView('pdf.surat_bebas_lab', [
            'peminjaman' => $peminjaman,
        ])->setPaper('a4', 'portrait');

        $namaFile = 'Surat_Bebas_Lab_'
            . str_replace(' ', '_', $peminjaman->nama_peminjam)
            . '_' . Carbon::now()->format('Ymd')
            . '.pdf';

        return $pdf->download($namaFile);
    }
}