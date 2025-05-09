<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaians';
    protected $guarded = [];

    public function crips()
    {
        return $this->belongsTo(Crips::class, 'crips_id');
    }

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_id');
    }
}
