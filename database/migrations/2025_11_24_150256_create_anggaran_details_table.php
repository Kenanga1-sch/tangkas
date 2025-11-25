<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggaran_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggaran_id')->constrained()->cascadeOnDelete();
            
            // Relasi ke barang SSH (Nullable, jaga-jaga kalau barang manual)
            $table->foreignId('master_standar_harga_id')->nullable(); 
            
            $table->string('uraian'); // Semen, Pasir (Bisa dari SSH atau ketik sendiri)
            $table->text('spesifikasi')->nullable();
            $table->decimal('harga_satuan', 15, 2);
            $table->integer('qty');
            $table->string('satuan');
            $table->decimal('total_harga', 15, 2); // qty * harga_satuan
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggaran_details');
    }
};