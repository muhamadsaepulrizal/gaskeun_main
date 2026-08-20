<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RumahTanggaSasaran extends Model
{
    protected $fillable = ['kk_id', 'kriteria_bantuan', 'status_penerima'];

    public function kk()
    {
        return $this->belongsTo(Kk::class);
    }
}
