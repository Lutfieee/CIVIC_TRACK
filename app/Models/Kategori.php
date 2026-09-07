<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kategori', 'ikon', 'warna'];

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    public function lokasis()
    {
        return $this->belongsToMany(Lokasi::class, 'kategori_lokasi');
    }
}
