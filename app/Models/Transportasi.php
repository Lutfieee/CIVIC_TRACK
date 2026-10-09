<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_moda',
        'tipe',
        'kode_trayek',
        'rute_asal',
        'rute_tujuan',
        'titik_pemberhentian',
        'jadwal_operasional',
        'tarif',
        'status_operasional',
        'keterangan_operasional',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status_operasional) {
            'normal' => 'emerald',
            'terkendala' => 'amber',
            'perbaikan' => 'blue',
            'tidak_beroperasi' => 'rose',
            default => 'slate',
        };
    }

    public function getTipeBadgeColorAttribute(): string
    {
        return match($this->tipe) {
            'Bus' => 'indigo',
            'Angkot' => 'sky',
            'Kereta' => 'violet',
            'Feeder' => 'amber',
            default => 'slate',
        };
    }
}
