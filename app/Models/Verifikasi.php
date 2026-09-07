<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Verifikasi extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tanggal_verifikasi' => 'datetime',
    ];

    public static function administratorColumn(): string
    {
        return Schema::hasColumn('verifikasis', 'administrator_id')
            ? 'administrator_id'
            : 'veri' . 'fikator_id';
    }

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function administrator()
    {
        return $this->belongsTo(User::class, self::administratorColumn());
    }
}
