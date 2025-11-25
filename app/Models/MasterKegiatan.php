<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKegiatan extends Model
{
    use HasFactory;

    // --- TAMBAHKAN BARIS INI ---
    // Artinya: Izinkan semua kolom diisi (termasuk standar_pendidikan)
    protected $guarded = []; 
}