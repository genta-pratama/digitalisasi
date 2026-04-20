<?php

namespace App\Notifications;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuratBebasLabNotification extends Notification
{
    use Queueable;

    public function __construct(public Peminjaman $peminjaman) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $downloadUrl = route('surat-bebas-lab.download', $this->peminjaman->id);

        return (new MailMessage)
            ->subject('✅ Surat Bebas Lab Telah Diterbitkan')
            ->greeting('Halo, ' . $this->peminjaman->nama_peminjam . '!')
            ->line('Surat Bebas Laboratorium Anda telah diterbitkan oleh admin.')
            ->line('**Detail Peminjaman:**')
            ->line('🔬 Barang : ' . ($this->peminjaman->peminjamable?->nama ?? '-'))
            ->line('📅 Tanggal Pinjam  : ' . Carbon::parse($this->peminjaman->tanggal_pinjam)->translatedFormat('d F Y'))
            ->line('📅 Tanggal Kembali : ' . Carbon::parse($this->peminjaman->tanggal_kembali)->translatedFormat('d F Y'))
            ->action('⬇️ Download Surat Bebas Lab (PDF)', $downloadUrl)
            ->line('Simpan surat ini sebagai bukti bahwa Anda telah mengembalikan semua pinjaman laboratorium.')
            ->salutation('Salam, Tim Lab Kimia');
    }

    public function toDatabase(object $notifiable): array
    {
        $downloadUrl = route('surat-bebas-lab.download', $this->peminjaman->id);

        return [
            'peminjaman_id' => $this->peminjaman->id,
            'nama_barang'   => $this->peminjaman->peminjamable?->nama ?? '-',
            'pesan'         => 'Surat Bebas Lab Anda telah diterbitkan. Silakan download sekarang.',
            'url_download'  => $downloadUrl,
            'tipe'          => 'surat_bebas_lab',
        ];
    }
}