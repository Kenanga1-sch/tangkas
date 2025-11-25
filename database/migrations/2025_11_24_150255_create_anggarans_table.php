<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggarans', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Master Kegiatan (Pilih Kegiatan)
            // Kita simpan kodenya saja biar aman kalau master berubah
            $table->string('kode_sub_kegiatan')->index(); 
            
            // Informasi Waktu
            $table->integer('tahun');
            $table->string('bulan'); // Juni, Juli, dst
            
            // Informasi Tanda Tangan (Bisa default dari setting nanti)
            $table->string('nama_kepala_sekolah')->nullable();
            $table->string('nip_kepala_sekolah')->nullable();
            $table->string('nama_bendahara')->nullable();
            $table->string('nip_bendahara')->nullable();
            
            // Total Anggaran (Cache field biar gak perlu hitung ulang terus)
            $table->decimal('total_anggaran', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggarans');
    }
};