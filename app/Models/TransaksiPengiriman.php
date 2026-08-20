<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPengiriman extends Model
{
    protected $table = 'transaksi_pengirimans';

    protected $fillable = [
        'agen_id',
        'pangkalan_id',
        'jumlah_tabung',
        'tanggal_pengiriman',
        'foto_bukti',
        'status',
    ];

    public function agen()
    {
        return $this->belongsTo(User::class, 'agen_id');
    }

    public function pangkalan()
    {
        return $this->belongsTo(User::class, 'pangkalan_id');
    }

    public function koreksi()
    {
        return $this->hasOne(KoreksiPengiriman::class);
    }
}
