<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPenyaluran extends Model
{
    protected $fillable = [
        'pangkalan_id',
        'kategori_konsumen',
        'penduduk_id',
        'jumlah_tabung',
        'tanggal_penyaluran',
    ];

    public function pangkalan()
    {
        return $this->belongsTo(User::class, 'pangkalan_id');
    }

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'penduduk_id');
    }
}
