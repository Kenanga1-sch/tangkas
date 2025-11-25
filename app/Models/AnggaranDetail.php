<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggaranDetail extends Model
{
    protected $guarded = [];

    public function anggaran()
    {
        return $this->belongsTo(Anggaran::class);
    }
}