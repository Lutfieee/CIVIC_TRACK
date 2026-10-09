<?php

namespace App\Http\Controllers;

use App\Models\Transportasi;
use Illuminate\Http\Request;

class TransportasiController extends Controller
{
    /**
     * Menampilkan informasi rute, jadwal, dan status operasional transportasi umum (FR-21, FR-22, FR-26)
     */
    public function index(Request $request)
    {
        $query = Transportasi::query()->where('is_active', true);

        // Pencarian rute berdasarkan asal, tujuan, atau nama moda (FR-22)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_moda', 'like', "%{$q}%")
                    ->orWhere('kode_trayek', 'like', "%{$q}%")
                    ->orWhere('rute_asal', 'like', "%{$q}%")
                    ->orWhere('rute_tujuan', 'like', "%{$q}%")
                    ->orWhere('titik_pemberhentian', 'like', "%{$q}%");
            });
        }

        // Filter tipe moda
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Filter status operasional
        if ($request->filled('status')) {
            $query->where('status_operasional', $request->status);
        }

        $transportasis = $query->orderBy('nama_moda')->get();

        $stats = [
            'total' => Transportasi::where('is_active', true)->count(),
            'normal' => Transportasi::where('is_active', true)->where('status_operasional', 'normal')->count(),
            'terkendala' => Transportasi::where('is_active', true)->where('status_operasional', 'terkendala')->count(),
            'perbaikan' => Transportasi::where('is_active', true)->where('status_operasional', 'perbaikan')->count(),
        ];

        return view('transportasi.index', compact('transportasis', 'stats'));
    }

    /**
     * Melihat detail rute & titik pemberhentian (FR-23)
     */
    public function show(Transportasi $transportasi)
    {
        return view('transportasi.show', compact('transportasi'));
    }
}
