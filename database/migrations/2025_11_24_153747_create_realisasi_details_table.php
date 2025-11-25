<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisasi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('realisasi_id')->constrained()->cascadeOnDelete();
            
            $table->string('uraian');
            $table->text('spesifikasi')->nullable();
            $table->decimal('harga_satuan', 15, 2);
            $table->integer('qty');
            $table->string('satuan');
            $table->decimal('total_harga', 15, 2);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisasi_details');
    }
};