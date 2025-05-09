<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriterias';
    protected $guarded = [];

    public function crips()
    {
        return $this->hasMany(Crips::class, 'kriteria_id');
    }

    public function penilaian()
    {
        return $this->hasManyThrough(Penilaian::class, Crips::class, 'kriteria_id', 'crips_id');
    }

}
