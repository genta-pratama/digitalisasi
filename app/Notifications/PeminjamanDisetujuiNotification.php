<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PeminjamanDisetujuiNotification extends Notification
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
        return [
            'tipe'        => 'peminjaman_disetujui',
            'nomor_surat' => $this->nomorSurat,
            'pesan'       => 'Peminjaman ' . $this->jumlahBarang . ' barang dengan nomor surat ' . $this->nomorSurat . ' telah disetujui oleh admin.',
        ];
    }
}