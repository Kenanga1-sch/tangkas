<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    // KUNCI UTAMA: Baris ini mengizinkan semua kolom untuk diisi
    protected $guarded = []; 

    // Jika Anda lebih suka cara manual (ribet), pakai $fillable:
    // protected $fillable = ['nama_sekolah', 'alamat', 'telepon', ...];
}