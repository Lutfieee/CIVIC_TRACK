<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'alamat', 'wilayah', 'latitude', 'longitude',
        'deskripsi', 'harga_per_bulan', 'kontak', 'foto', 'is_active',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_active' => 'boolean',
    ];

    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class, 'kategori_lokasi');
    }
}
