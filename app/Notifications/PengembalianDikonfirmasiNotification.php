<?php
namespace App\Notifications;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengembalianDikonfirmasiNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $nomorSurat,
        public int $jumlahBarang
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $items = Peminjaman::where('nomor_surat', $this->nomorSurat)
            ->with('peminjamable')
            ->get()
            ->map(fn($p) => [
                'nama'      => $p->peminjamable?->nama ?? '-',
                'jumlah'    => $p->jumlah,
                'tipe'      => class_basename($p->peminjamable_type),
                'unit'      => $p->peminjamable?->unit ?? 'unit',
                'kondisi'   => $p->kondisi_pengembalian ?? 'baik',
            ])->toArray();

        $pertama = Peminjaman::where('nomor_surat', $this->nomorSurat)->first();

        return [
            'tipe'            => 'pengembalian_dikonfirmasi',
            'nomor_surat'     => $this->nomorSurat,
            'pesan'           => 'Pengembalian ' . $this->jumlahBarang . ' barang dengan nomor surat ' . $this->nomorSurat . ' telah dikonfirmasi oleh admin.',
            'nama_peminjam'   => $pertama?->nama_peminjam ?? '-',
            'nim_peminjam'    => $pertama?->nim_peminjam ?? '-',
            'tanggal_kembali' => now()->format('d M Y'),
            'items'           => $items,
        ];
    }
}