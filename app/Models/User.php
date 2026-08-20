<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'status_aktif',
        'force_password_change',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable()
        ->logOnlyDirty();
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'status_aktif'      => 'boolean',
            'force_password_change' => 'boolean',
        ];
    }

    // ============================================================
    // RELATIONSHIPS
    // ============================================================
    public function profilAgen()
    {
        return $this->hasOne(ProfilAgen::class);
    }

    public function pangkalanProfile()
    {
        return $this->hasOne(PangkalanProfile::class);
    }

    public function stokPangkalan()
    {
        return $this->hasOne(StokPangkalan::class);
    }

    /**
     * Pangkalan-pangkalan binaan agen ini (relasi melalui pangkalan_profiles)
     */
    public function pangkalanBinaan()
    {
        return $this->hasMany(PangkalanProfile::class, 'agen_pembina_id');
    }

    // ============================================================
    // SCOPES
    // ============================================================
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    public function scopeNonaktif($query)
    {
        return $query->where('status_aktif', false);
    }
}
