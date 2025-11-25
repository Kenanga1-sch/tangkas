<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Realisasi extends Model
{
    protected $guarded = [];

    public function details(): HasMany
    {
        return $this->hasMany(RealisasiDetail::class);
    }

    public function anggaran(): BelongsTo
    {
        return $this->belongsTo(Anggaran::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(MasterKegiatan::class, 'kode_sub_kegiatan', 'kode_sub_kegiatan');
    }
}