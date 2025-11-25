<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anggaran extends Model
{
    protected $guarded = [];

    // Getter Virtual (Wajib untuk Form Filament)
    public function getStandarPendidikanAttribute()
    {
        return $this->kegiatan->standar_pendidikan ?? null;
    }

    public function getUraianKegiatanAttribute()
    {
        return $this->kegiatan->uraian_kegiatan ?? null;
    }

    // Relasi
    public function details(): HasMany
    {
        return $this->hasMany(AnggaranDetail::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(MasterKegiatan::class, 'kode_sub_kegiatan', 'kode_sub_kegiatan');
    }
}