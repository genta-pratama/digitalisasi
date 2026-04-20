<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // 'baik' atau 'rusak' — hanya relevan untuk Alat
            $table->enum('kondisi_pengembalian', ['baik', 'rusak'])
                  ->nullable()
                  ->after('tanggal_kembali');
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn('kondisi_pengembalian');
        });
    }
};