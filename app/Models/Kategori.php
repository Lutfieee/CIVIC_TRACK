<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    const DINAS_LIST = [
        'Dinas Lingkungan Hidup Kabupaten Bandung',
        'Dinas Pekerjaan Umum dan Tata Ruang (DPUTR) Kabupaten Bandung',
        'Badan Penanggulangan Bencana Daerah (BPBD)',
        'Dinas Perhubungan Kabupaten Bandung',
    ];

    protected $fillable = [
        'nama_kategori',
        'dinas_terkait',
        'ikon',
        'warna'
    ];

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    public function lokasis()
    {
        return $this->belongsToMany(Lokasi::class, 'kategori_lokasi');
    }
}
