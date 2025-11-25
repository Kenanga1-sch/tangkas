<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anggarans', function (Blueprint $table) {
            // Kombinasi 3 kolom ini TIDAK BOLEH kembar
            $table->unique(['kode_sub_kegiatan', 'tahun', 'bulan'], 'unique_anggaran_per_bulan');
        });
    }

    public function down(): void
    {
        Schema::table('anggarans', function (Blueprint $table) {
            $table->dropUnique('unique_anggaran_per_bulan');
        });
    }
};