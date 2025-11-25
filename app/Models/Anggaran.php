<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anggaran extends Model
{
    protected $guarded = [];

    // Relasi ke Detail
    public function details(): HasMany
    {
        return $this->hasMany(AnggaranDetail::class);
    }

    // Relasi "Palsu" ke Master Kegiatan (karena kita pakai string kode, bukan ID)
    // Ini trik agar bisa ambil nama kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(MasterKegiatan::class, 'kode_sub_kegiatan', 'kode_sub_kegiatan');
    }
}