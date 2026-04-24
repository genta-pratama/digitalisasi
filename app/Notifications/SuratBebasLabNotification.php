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

    /**
     * $isGabungan = true  → link download ke surat gabungan (by NIM + nomor_surat)
     * $isGabungan = false → link download ke surat single (by id)
     */
    public function __construct(
        public Peminjaman $peminjaman,
        public bool $isGabungan = false
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $downloadUrl = $this->getDownloadUrl();

        $mail = (new MailMessage)
            ->subject('✅ Surat Bebas Lab Telah Diterbitkan')
            ->greeting('Halo, ' . $this->peminjaman->nama_peminjam . '!')
            ->line('Surat Bebas Laboratorium Anda telah diterbitkan oleh admin.');

        if ($this->isGabungan) {
            // Untuk surat gabungan, tampilkan nomor surat saja
            // (tidak ada 1 barang spesifik yang ditampilkan)
            $mail->line('**Nomor Surat:** ' . $this->peminjaman->nomor_surat);
        } else {
            $mail->line('**Detail Peminjaman:**')
                 ->line('🔬 Barang : ' . ($this->peminjaman->peminjamable?->nama ?? '-'))
                 ->line('📅 Tanggal Pinjam  : ' . Carbon::parse($this->peminjaman->tanggal_pinjam)->translatedFormat('d F Y'))
                 ->line('📅 Tanggal Kembali : ' . Carbon::parse($this->peminjaman->tanggal_kembali)->translatedFormat('d F Y'));
        }

        return $mail
            ->action('⬇️ Download Surat Bebas Lab (PDF)', $downloadUrl)
            ->line('Simpan surat ini sebagai bukti bahwa Anda telah mengembalikan semua pinjaman laboratorium.')
            ->salutation('Salam, Tim Lab Kimia');
    }

    public function toDatabase(object $notifiable): array
    {
        $downloadUrl = $this->getDownloadUrl();

        return [
            'peminjaman_id' => $this->peminjaman->id,
            'nama_barang'   => $this->isGabungan
                                    ? 'Semua barang (' . $this->peminjaman->nomor_surat . ')'
                                    : ($this->peminjaman->peminjamable?->nama ?? '-'),
            'pesan'         => 'Surat Bebas Lab Anda telah diterbitkan. Hubungi admin untuk mengambil suratnya.',
            'url_download'  => $downloadUrl,
            'tipe'          => 'surat_bebas_lab',
        ];
    }

    /**
     * Pilih URL download yang tepat:
     * - Gabungan → route download by NIM + nomor_surat
     * - Single   → route download by id
     */
    private function getDownloadUrl(): string
    {
        if ($this->isGabungan) {
            return route('surat-bebas-lab-gabungan.download', [
                'nim'         => $this->peminjaman->nim_peminjam,
                'nomor_surat' => $this->peminjaman->nomor_surat,
            ]);
        }

        return route('surat-bebas-lab.download', $this->peminjaman->id);
    }
}