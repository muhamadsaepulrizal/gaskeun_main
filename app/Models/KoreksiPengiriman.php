<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KoreksiPengiriman extends Model
{
    protected $table = 'koreksi_pengirimans';

    protected $fillable = [
        'transaksi_pengiriman_id',
        'jumlah_seharusnya',
        'keterangan_koreksi',
        'status_koreksi',
    ];

    public function transaksiPengiriman()
    {
        return $this->belongsTo(TransaksiPengiriman::class);
    }
}
