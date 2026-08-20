<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilAgen extends Model
{
    protected $fillable = [
        'user_id', 
        'nama_agen', 
        'no_registrasi', 
        'alamat', 
        'kontak',
        'pso',
        'jumlah_mitra',
        'id_spbe',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'latitude'  => 'decimal:8',
        'longitude' => 'decimal:8',
        'id_spbe'   => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
