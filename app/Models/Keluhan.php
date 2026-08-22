<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kecamatan;

class Keluhan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_tiket',
        'user_id',
        'kecamatan_id',
        'desa_id',
        'pangkalan_id',
        'jenis_aduan',
        'latitude',
        'longitude',
        'foto_bukti',
        'isi_keluhan',
        'status_keluhan',
        'tindak_lanjut',
        'alasan_penolakan',
        'diverifikasi_oleh',
        'tanggal_respon_wa',
        'otp_code',
        'otp_verified_at',
        'no_hp_pelapor',
        'email_pelapor',
        'nama_pelapor'
    ];

    protected $casts = [
        'tanggal_respon_wa' => 'datetime',
        'otp_verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function pangkalan()
    {
        return $this->belongsTo(User::class, 'pangkalan_id');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
