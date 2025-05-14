<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatPerhitungan extends Model
{
    protected $table = 'riwayat_perhitungan';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasMany(RiwayatDetail::class, 'riwayat_id');
    }
}
