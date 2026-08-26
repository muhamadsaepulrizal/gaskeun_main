<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Konsumen extends Model
{
    protected $fillable = [
        'pangkalan_id',
        'kecamatan_id',
        'desa_kelurahan_id',
        'kategori',
        'nama_lengkap',
        'nik_encrypted',
        'nik_hash',
        'nib_encrypted',
        'nib_hash',
        'alamat',
        'kontak',
        'is_anomali',
    ];

    protected $casts = [
        'is_anomali' => 'boolean',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================
    public function pangkalan()
    {
        return $this->belongsTo(User::class, 'pangkalan_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_kelurahan_id');
    }

    // ============================================================
    // HELPERS: Enkripsi & Hashing NIK/NIB (BR-20)
    // ============================================================

    /**
     * Simpan NIK terenkripsi + hash untuk lookup (BR-19, BR-20)
     */
    public function setNikAttribute(?string $nik): void
    {
        if (is_null($nik)) {
            $this->attributes['nik_encrypted'] = null;
            $this->attributes['nik_hash'] = null;
            return;
        }
        $this->attributes['nik_encrypted'] = Crypt::encryptString($nik);
        $this->attributes['nik_hash'] = hash('sha256', $nik);
    }

    /**
     * Simpan NIB terenkripsi + hash untuk lookup (BR-19, BR-20)
     */
    public function setNibAttribute(?string $nib): void
    {
        if (is_null($nib)) {
            $this->attributes['nib_encrypted'] = null;
            $this->attributes['nib_hash'] = null;
            return;
        }
        $this->attributes['nib_encrypted'] = Crypt::encryptString($nib);
        $this->attributes['nib_hash'] = hash('sha256', $nib);
    }

    /**
     * Dekripsi NIK untuk ditampilkan
     */
    public function getNikAttribute(): ?string
    {
        return $this->nik_encrypted ? Crypt::decryptString($this->nik_encrypted) : null;
    }

    /**
     * Dekripsi NIB untuk ditampilkan
     */
    public function getNibAttribute(): ?string
    {
        return $this->nib_encrypted ? Crypt::decryptString($this->nib_encrypted) : null;
    }

    // ============================================================
    // VALIDASI UNIK GLOBAL (BR-19)
    // ============================================================

    /**
     * Cek apakah NIK sudah terdaftar di pangkalan manapun
     */
    public static function nikSudahTerdaftar(string $nik, ?int $excludeId = null): bool
    {
        $hash = hash('sha256', $nik);
        $query = static::where('nik_hash', $hash);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists();
    }

    /**
     * Cek apakah NIB sudah terdaftar di pangkalan manapun
     */
    public static function nibSudahTerdaftar(string $nib, ?int $excludeId = null): bool
    {
        $hash = hash('sha256', $nib);
        $query = static::where('nib_hash', $hash);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists();
    }
}
