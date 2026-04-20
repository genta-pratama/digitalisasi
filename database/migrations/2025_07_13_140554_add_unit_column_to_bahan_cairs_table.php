<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('bahan_cairan_lamas', function (Blueprint $table) {
        if (!Schema::hasColumn('bahan_cairan_lamas', 'sisa_bahan')) {
            $table->string('sisa_bahan')->nullable();
        }
    });
}

public function down(): void
{
    Schema::table('bahan_cairan_lamas', function (Blueprint $table) {
        $table->string('sisa_bahan')->change();
        $table->dropColumn('unit');
    });
}
};
