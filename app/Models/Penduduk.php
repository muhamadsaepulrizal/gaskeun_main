<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    protected $fillable = [
        'kk_id',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'pekerjaan'
    ];

    public function kk()
    {
        return $this->belongsTo(Kk::class);
    }

    public function nelayan()
    {
        return $this->hasOne(Nelayan::class);
    }

    public function petani()
    {
        return $this->hasOne(Petani::class);
    }

    public function umkm()
    {
        return $this->hasOne(Umkm::class);
    }
}
