<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petani extends Model
{
    protected $fillable = ['penduduk_id', 'luas_lahan_m2', 'jenis_komoditas'];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }
}
