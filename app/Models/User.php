<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    public function komentars()
    {
        return $this->hasMany(Komentar::class);
    }

    public function verifikasis()
    {
        return $this->hasMany(Verifikasi::class, Verifikasi::administratorColumn());
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isWarga(): bool
    {
        return $this->role === 'warga';
    }
}
