<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    protected $fillable = ['penduduk_id', 'nama_usaha', 'bidang_usaha'];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }
}
