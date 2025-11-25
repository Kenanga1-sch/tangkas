<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterStandarHarga extends Model
{
    use HasFactory;

    // Tambahkan baris ini:
    // Artinya: "Tidak ada kolom yang dijaga, silakan isi semua"
    protected $guarded = []; 
}