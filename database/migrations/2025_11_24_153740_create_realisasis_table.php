<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisasis', function (Blueprint $table) {
            $table->id();
            
            // Link ke Rencana (Biar tahu ini realisasi dari rencana yang mana)
            $table->foreignId('anggaran_id')->constrained()->cascadeOnDelete();
            
            // Kita simpan ulang data header jaga-jaga kalau realisasi beda bulan/tahun
            $table->string('kode_sub_kegiatan'); 
            $table->integer('tahun');
            $table->string('bulan');
            
            // Tanda Tangan (Bisa jadi pejabatnya ganti saat realisasi)
            $table->string('nama_kepala_sekolah')->nullable();
            $table->string('nip_kepala_sekolah')->nullable();
            $table->string('nama_bendahara')->nullable();
            $table->string('nip_bendahara')->nullable();
            
            $table->decimal('total_realisasi', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisasis');
    }
};