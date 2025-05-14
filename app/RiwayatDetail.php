<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatDetail extends Model
{
    protected $table = 'riwayat_detail';
    protected $guarded = [];

    public function riwayat()
    {
        return $this->belongsTo(RiwayatPerhitungan::class, 'riwayat_id');
    }
}
