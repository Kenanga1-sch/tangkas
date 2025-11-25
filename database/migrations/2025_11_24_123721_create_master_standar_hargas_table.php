<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_standar_hargas', function (Blueprint $table) {
            $table->id();
            // Kolom penanda sumber data (SSH, SBU, HSPK, ASB)
            $table->string('jenis_standar', 10)->index(); 
            
            // Sesuai kolom di CSV
            $table->string('kode_kelompok_barang')->nullable()->index();
            $table->string('uraian_kelompok_barang')->nullable();
            $table->string('id_standar_harga')->nullable(); // ID bawaan dari excel
            $table->string('kode_barang')->index(); // Index biar pencarian cepat
            $table->text('uraian_barang'); // Text karena namanya bisa panjang
            $table->text('spesifikasi')->nullable();
            $table->string('satuan')->nullable();
            $table->decimal('harga_satuan', 15, 2); // Format uang
            $table->string('kode_rekening')->nullable();
            
            $table->timestamps();

            // Fulltext index untuk fitur "Smart Search" super cepat
            // Pastikan database MySQL/MariaDB support fulltext
            $table->fullText('uraian_barang'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_standar_hargas');
    }
};