<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PinjamController;
use App\Http\Controllers\SuratBebasLabController;
use App\Http\Controllers\SuratBebasLabGabunganController;
use App\Http\Controllers\PeminjamanActionController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\NotificationController;

// ✅ Halaman login mahasiswa
Route::get('/login', function () {
    if (Auth::check()) return redirect()->route('pinjam.create');
    return view('auth.login');
})->name('login');

Route::get('/', [PinjamController::class, 'create'])->name('pinjam.create');
Route::post('/', [PinjamController::class, 'store'])
    ->name('pinjam.store')
    ->middleware('throttle:5,1');

// Download surat peminjaman (saat mengajukan)
Route::get('/surat/{nomorSurat}', [PinjamController::class, 'downloadSurat'])
    ->where('nomorSurat', '.*')
    ->name('pinjam.download-surat');

// Download Surat Bebas Lab per barang (admin)
Route::get('/surat-bebas-lab/{id}', [SuratBebasLabController::class, 'download'])
    ->name('surat-bebas-lab.download');

// Terbitkan + Download Surat Bebas Lab per barang (admin)
Route::get('/surat-bebas-lab/{id}/terbitkan', [SuratBebasLabController::class, 'terbitkanDanDownload'])
    ->name('surat-bebas-lab.terbitkan');

// Terbitkan Surat Bebas Lab GABUNGAN semua barang 1 mahasiswa (admin)
Route::get('/surat-bebas-lab-gabungan/{nim}/terbitkan', [SuratBebasLabGabunganController::class, 'terbitkanDanDownload'])
    ->name('surat-bebas-lab-gabungan.terbitkan');

// Download ulang Surat Bebas Lab GABUNGAN (admin)
Route::get('/surat-bebas-lab-gabungan/{nim}', [SuratBebasLabGabunganController::class, 'download'])
    ->name('surat-bebas-lab-gabungan.download');

// Aksi setujui / tolak per item dari modal detail (admin)
Route::get('/admin/peminjaman/{id}/setujui-item', [PeminjamanActionController::class, 'setujuiItem'])
    ->name('admin.peminjaman.setujui-item');
Route::get('/admin/peminjaman/{id}/kembalikan-item/{kondisi}', [PeminjamanActionController::class, 'kembalikanItem'])
    ->name('admin.peminjaman.kembalikan-item')
    ->where('kondisi', 'baik|rusak');
Route::get('/admin/peminjaman/{nomorSurat}/kembalikan-semua', [PeminjamanActionController::class, 'kembalikanSemua'])
    ->where('nomorSurat', '.*')
    ->name('admin.peminjaman.kembalikan-semua');
Route::get('/admin/peminjaman/{id}/tandai-baik', [PeminjamanActionController::class, 'tandaiBaik'])
    ->name('admin.peminjaman.tandai-baik');
Route::get('/admin/peminjaman/{nomorSurat}/setujui-semua', [PeminjamanActionController::class, 'setujuiSemua'])
    ->where('nomorSurat', '.*')
    ->name('admin.peminjaman.setujui-semua');

Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])
    ->name('notifications.read-all');

Route::get('/export/bahan-padat', [ExportController::class, 'exportBahanPadat']);
Route::get('/export/bahan-cairan-lama', [ExportController::class, 'exportBahanCairanLama']);
Route::get('/test-error', function() {
    abort(500, 'Test Error');
});

// Rute untuk redirect ke Google
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
// Rute untuk callback dari Google
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
// Rute untuk logout
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');
Route::get('/logout', [GoogleAuthController::class, 'logout'])->name('logout.get');