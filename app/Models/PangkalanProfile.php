<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PangkalanProfile extends Model
{
    protected $fillable = [
        'user_id',
        'agen_pembina_id',
        'kecamatan_id',
        'desa_kelurahan_id',
        'nama_pangkalan',
        'no_registrasi',
        'alamat',
        'kontak',
        'penyaluran',
        'latitude',
        'longitude',
        'kuota_bulanan',
    ];

    protected $casts = [
        'latitude'       => 'decimal:8',
        'longitude'      => 'decimal:8',
        'kuota_bulanan'  => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agenPembina()
    {
        return $this->belongsTo(User::class, 'agen_pembina_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desaKelurahan()
    {
        return $this->belongsTo(Desa::class, 'desa_kelurahan_id');
    }
}
