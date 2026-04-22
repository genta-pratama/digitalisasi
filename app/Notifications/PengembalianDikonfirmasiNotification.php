<?php
namespace App\Notifications;

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
        return [
            'tipe'        => 'pengembalian_dikonfirmasi',
            'nomor_surat' => $this->nomorSurat,
            'pesan'       => 'Pengembalian ' . $this->jumlahBarang . ' barang dengan nomor surat ' . $this->nomorSurat . ' telah dikonfirmasi oleh admin.',
        ];
    }
}