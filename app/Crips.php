<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crips extends Model
{
    protected $table = 'crips';
    protected $guarded = [];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'crips_id');
    }
}