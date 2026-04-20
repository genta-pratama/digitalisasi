<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjamans', 'nomor_surat')) {
                $table->string('nomor_surat')->nullable()->after('no_hp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            if (Schema::hasColumn('peminjamans', 'nomor_surat')) {
                $table->dropColumn('nomor_surat');
            }
            if (Schema::hasColumn('peminjamans', 'tanggal_pinjam')) {
                $table->dropColumn('tanggal_pinjam');
            }
        });
    }
};