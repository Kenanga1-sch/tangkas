<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_kegiatans', function (Blueprint $table) {
            $table->id();
            // Kita simpan flat sesuai Excel agar mudah diimport
            $table->string('standar_pendidikan'); // Contoh: 02. STANDAR ISI
            $table->string('kode_kegiatan');
            $table->string('uraian_kegiatan');
            $table->string('kode_sub_kegiatan')->unique(); // Ini key uniknya
            $table->text('uraian_sub_kegiatan'); // Contoh: Penyusunan Kurikulum
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_kegiatans');
    }
};