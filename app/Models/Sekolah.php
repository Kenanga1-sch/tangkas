<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    // --- TAMBAHKAN INI ---
    // Artinya: Izinkan semua kolom (nama, alamat, logo, dll) diisi sekaligus
    protected $guarded = []; 
}