<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'kategori_id', 'latitude', 'longitude',
        'wilayah', 'deskripsi', 'foto', 'tingkat_keparahan', 'status',
        'status_penghapusan', 'alasan_penghapusan', 'alasan_penolakan_hapus', 'tanggal_pengajuan_hapus',
    ];

    protected $casts = [
        'latitude'              => 'float',
        'longitude'             => 'float',
        'tanggal_pengajuan_hapus' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function komentars()
    {
        return $this->hasMany(Komentar::class);
    }

    public function verifikasi()
    {
        return $this->hasOne(Verifikasi::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isVerified(): bool
    {
        return $this->status === 'diverifikasi';
    }

    public function isMenungguHapus(): bool
    {
        return $this->status_penghapusan === 'menunggu_verifikasi';
    }

    public function getBadgeColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'diverifikasi' => 'green',
            'ditolak' => 'red',
            default => 'gray',
        };
    }

    public function getKeparahanColorAttribute(): string
    {
        return match($this->tingkat_keparahan) {
            'rendah' => 'green',
            'sedang' => 'yellow',
            'tinggi' => 'red',
            default => 'gray',
        };
    }
}
