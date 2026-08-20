<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokPangkalan extends Model
{
    protected $fillable = ['user_id', 'jumlah_tabung'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
