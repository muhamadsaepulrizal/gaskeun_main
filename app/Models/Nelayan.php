<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nelayan extends Model
{
    protected $fillable = ['penduduk_id', 'jenis_kapal', 'alat_tangkap'];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }
}
