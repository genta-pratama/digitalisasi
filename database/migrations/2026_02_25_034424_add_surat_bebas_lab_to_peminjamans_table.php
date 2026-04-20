<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Jalankan:
// php artisan make:migration add_surat_bebas_lab_to_peminjamans_table
// Ganti isinya dengan ini, lalu: php artisan migrate

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->boolean('surat_bebas_lab_diterbitkan')->default(false)->after('tanggal_kembali');
            $table->timestamp('surat_bebas_lab_diterbitkan_at')->nullable()->after('surat_bebas_lab_diterbitkan');
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn(['surat_bebas_lab_diterbitkan', 'surat_bebas_lab_diterbitkan_at']);
        });
    }
};