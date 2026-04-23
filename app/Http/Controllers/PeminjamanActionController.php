<?php
namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\User;
use App\Notifications\PeminjamanDisetujuiNotification;
use App\Notifications\PengembalianDikonfirmasiNotification;
use Illuminate\Http\Request;

class PeminjamanActionController extends Controller
{
    /**
     * Setujui 1 item
     * GET /admin/peminjaman/{id}/setujui-item
     */
    public function setujuiItem(int $id)
    {
        $item = Peminjaman::findOrFail($id);
        if ($item->status !== 'Menunggu Persetujuan') {
            return redirect('/admin/peminjamen')->with('error', 'Status tidak valid.');
        }
        $item->update(['status' => 'Disetujui', 'tanggal_pinjam' => now()]);

        // Kirim notifikasi ke user — hanya sekali per nomor surat
        if ($item->user_id) {
            $user = User::find($item->user_id);
            if ($user) {
                $sudahAdaNotif = $user->notifications()
                    ->where('data->tipe', 'peminjaman_disetujui')
                    ->where('data->nomor_surat', $item->nomor_surat)
                    ->exists();

                if (!$sudahAdaNotif) {
                    $jumlah = Peminjaman::where('nomor_surat', $item->nomor_surat)->count();
                    $user->notify(new PeminjamanDisetujuiNotification($item->nomor_surat, $jumlah));
                }
            }
        }

        return redirect('/admin/peminjamen')->with('success', 'Barang "' . ($item->peminjamable?->nama ?? '-') . '" berhasil disetujui.');
    }

    /**
     * Konfirmasi pengembalian 1 item
     * GET /admin/peminjaman/{id}/kembalikan-item/{kondisi}
     */
    public function kembalikanItem(int $id, string $kondisi)
    {
        if (!in_array($kondisi, ['baik', 'rusak'])) {
            return redirect('/admin/peminjamen')->with('error', 'Kondisi tidak valid.');
        }
        $item = Peminjaman::with('peminjamable')->findOrFail($id);
        if ($item->status !== 'Disetujui') {
            return redirect('/admin/peminjamen')->with('error', 'Status tidak valid.');
        }

        $isAlat = $item->peminjamable_type === 'App\\Models\\Alat';
        $barang = $item->peminjamable;

        if ($isAlat && $kondisi === 'baik') {
            $barang->increment('stok', $item->jumlah);
        }

        $item->update([
            'status'               => 'Dikembalikan',
            'tanggal_kembali'      => now(),
            'kondisi_pengembalian' => $isAlat ? $kondisi : 'baik',
        ]);

        // Kirim notifikasi ke user — hanya sekali per nomor surat
        if ($item->user_id) {
            $user = User::find($item->user_id);
            if ($user) {
                $sudahAdaNotif = $user->notifications()
                    ->where('data->tipe', 'pengembalian_dikonfirmasi')
                    ->where('data->nomor_surat', $item->nomor_surat)
                    ->exists();

                if (!$sudahAdaNotif) {
                    $jumlah = Peminjaman::where('nomor_surat', $item->nomor_surat)->count();
                    $user->notify(new PengembalianDikonfirmasiNotification($item->nomor_surat, $jumlah));
                }
            }
        }

        if ($isAlat && $kondisi === 'rusak') {
            return redirect('/admin/peminjamen')->with('warning', 'Alat "' . ($barang?->nama ?? '-') . '" dilaporkan RUSAK.');
        }
        return redirect('/admin/peminjamen')->with('success', 'Pengembalian "' . ($barang?->nama ?? '-') . '" berhasil dikonfirmasi.');
    }

    /**
     * Konfirmasi semua pengembalian dalam 1 surat sekaligus
     * GET /admin/peminjaman/{nomorSurat}/kembalikan-semua?kondisi[id]=baik/rusak
     */
    public function kembalikanSemua(Request $request, string $nomorSurat)
    {
        $nomorSurat  = urldecode($nomorSurat);
        $kondisiData = $request->input('kondisi', []);

        $items = Peminjaman::with('peminjamable')
            ->where('nomor_surat', $nomorSurat)
            ->where('status', 'Disetujui')
            ->get();

        $adaRusak  = false;
        $firstItem = $items->first();

        foreach ($items as $item) {
            $isAlat  = $item->peminjamable_type === 'App\\Models\\Alat';
            $kondisi = $isAlat ? ($kondisiData[$item->id] ?? 'baik') : 'baik';

            if (!in_array($kondisi, ['baik', 'rusak'])) {
                $kondisi = 'baik';
            }

            $barang = $item->peminjamable;

            if ($isAlat && $kondisi === 'baik') {
                $barang->increment('stok', $item->jumlah);
            }

            $item->update([
                'status'               => 'Dikembalikan',
                'tanggal_kembali'      => now(),
                'kondisi_pengembalian' => $isAlat ? $kondisi : 'baik',
            ]);

            if ($isAlat && $kondisi === 'rusak') {
                $adaRusak = true;
            }
        }

        // Kirim notifikasi ke user — hanya sekali per nomor surat
        if ($firstItem && $firstItem->user_id) {
            $user = User::find($firstItem->user_id);
            if ($user) {
                $sudahAdaNotif = $user->notifications()
                    ->where('data->tipe', 'pengembalian_dikonfirmasi')
                    ->where('data->nomor_surat', $nomorSurat)
                    ->exists();

                if (!$sudahAdaNotif) {
                    $user->notify(new PengembalianDikonfirmasiNotification($nomorSurat, $items->count()));
                }
            }
        }

        if ($adaRusak) {
            return redirect('/admin/peminjamen')->with('warning', 'Beberapa alat dilaporkan RUSAK. Surat Bebas Lab tidak dapat diterbitkan.');
        }
        return redirect('/admin/peminjamen')->with('success', 'Semua pengembalian dalam surat ' . $nomorSurat . ' berhasil dikonfirmasi.');
    }

    /**
     * Tandai alat rusak sudah diganti/diperbaiki → kondisi jadi baik
     * GET /admin/peminjaman/{id}/tandai-baik
     */
    public function tandaiBaik(int $id)
    {
        $item = Peminjaman::with('peminjamable')->findOrFail($id);

        if ($item->status !== 'Dikembalikan' || $item->kondisi_pengembalian !== 'rusak') {
            return redirect('/admin/peminjamen')->with('error', 'Hanya alat dengan kondisi rusak yang bisa diubah.');
        }

        $barang = $item->peminjamable;

        if ($item->peminjamable_type === 'App\\Models\\Alat') {
            $barang->increment('stok', $item->jumlah);
        }

        $item->update(['kondisi_pengembalian' => 'baik']);

        return redirect('/admin/peminjamen')->with('success', 'Alat "' . ($barang?->nama ?? '-') . '" berhasil ditandai sudah diganti dan kondisi baik.');
    }

    /**
     * Setujui semua item dalam 1 nomor surat
     * GET /admin/peminjaman/{nomorSurat}/setujui-semua
     */
    public function setujuiSemua(string $nomorSurat)
    {
        $nomorSurat = urldecode($nomorSurat);

        $items = Peminjaman::where('nomor_surat', $nomorSurat)
            ->where('status', 'Menunggu Persetujuan')
            ->get();

        foreach ($items as $item) {
            $item->update(['status' => 'Disetujui', 'tanggal_pinjam' => now()]);
        }

        // Kirim notifikasi ke user — hanya sekali per nomor surat
        $firstItem = $items->first();
        if ($firstItem && $firstItem->user_id) {
            $user = User::find($firstItem->user_id);
            if ($user) {
                $sudahAdaNotif = $user->notifications()
                    ->where('data->tipe', 'peminjaman_disetujui')
                    ->where('data->nomor_surat', $nomorSurat)
                    ->exists();

                if (!$sudahAdaNotif) {
                    $user->notify(new PeminjamanDisetujuiNotification($nomorSurat, $items->count()));
                }
            }
        }

        return redirect('/admin/peminjamen')->with('success', 'Semua barang dalam surat ' . $nomorSurat . ' berhasil disetujui.');
    }
}