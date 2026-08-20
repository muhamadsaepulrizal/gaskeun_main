<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kk extends Model
{
    protected $fillable = ['desa_id', 'nomor_kk', 'alamat_lengkap'];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function penduduks()
    {
        return $this->hasMany(Penduduk::class);
    }

    public function rumahTanggaSasaran()
    {
        return $this->hasOne(RumahTanggaSasaran::class);
    }
}
