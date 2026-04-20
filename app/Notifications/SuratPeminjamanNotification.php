<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SuratPeminjamanNotification extends Notification
{
    use Queueable;

    public function __construct(
        public $peminjamans,   // Collection of Peminjaman
        public string $nomorSurat,
        public string $namaPeminjam,
        public string $nimPeminjam
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'tipe'         => 'surat_peminjaman',
            'nomor_surat'  => $this->nomorSurat,
            'pesan'        => 'Peminjaman ' . $this->peminjamans->count() . ' barang berhasil diajukan.',
        ];
    }
}