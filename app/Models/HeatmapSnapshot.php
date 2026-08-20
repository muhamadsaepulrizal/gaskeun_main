<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeatmapSnapshot extends Model
{
    protected $fillable = [
        'kecamatan_id',
        'tanggal_snapshot',
        'skor_heatmap',
        'level_risiko',
        'parameter_detail',
        'rekomendasi_kuota',
        'jumlah_konsumen_sasaran',
        'rata_konsumsi_harian',
        'kepadatan_penduduk',
    ];

    protected $casts = [
        'tanggal_snapshot'       => 'date',
        'parameter_detail'       => 'array',
        'skor_heatmap'           => 'decimal:2',
        'rata_konsumsi_harian'   => 'decimal:2',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    /**
     * BR-08: Simpan snapshot BARU — tidak boleh update data hari kemarin.
     * Gunakan insertOrIgnore untuk memastikan uniqueness per kecamatan per tanggal.
     */
    public static function simpanSnapshot(int $kecamatanId, array $data): static
    {
        return static::firstOrCreate(
            [
                'kecamatan_id'     => $kecamatanId,
                'tanggal_snapshot' => now()->toDateString(),
            ],
            $data
        );
    }
}
