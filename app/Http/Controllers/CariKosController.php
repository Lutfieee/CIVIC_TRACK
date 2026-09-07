<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class CariKosController extends Controller
{
    public function index(Request $request)
    {
        $lokasiRekomendasi = Lokasi::where('is_active', true)->take(6)->get();
        $initialLat = (float) ($request->query('lat') ?? $request->query('latitude') ?? -6.974026);
        $initialLng = (float) ($request->query('lng') ?? $request->query('longitude') ?? 107.630328);
        return view('cari-kos.index', compact('lokasiRekomendasi', 'initialLat', 'initialLng'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|numeric|min:0.5|max:10',
        ]);

        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;
        $radius = (float) $request->radius; // km

        // 1. Query riwayat risiko (laporans yang diverifikasi) dalam radius
        $laporans = Laporan::with('kategori')
            ->where('status', 'diverifikasi')
            ->selectRaw("
                *,
                (6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )) AS jarak
            ", [$lat, $lng, $lat])
            ->having('jarak', '<=', $radius)
            ->orderBy('jarak', 'asc')
            ->get();

        // 2. Query master kos terdaftar dalam radius (jika ada)
        $kos = Lokasi::where('is_active', true)
            ->with('kategoris')
            ->selectRaw("
                *,
                (6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )) AS jarak
            ", [$lat, $lng, $lat])
            ->having('jarak', '<=', $radius)
            ->orderBy('jarak', 'asc')
            ->get();

        // 3. Hitung indikator keamanan
        $jumlahBanjir = $laporans->filter(fn($l) => str_contains(strtolower($l->kategori->nama_kategori), 'banjir'))->count();
        $jumlahMacet = $laporans->filter(fn($l) => str_contains(strtolower($l->kategori->nama_kategori), 'lintas') || str_contains(strtolower($l->kategori->nama_kategori), 'macet'))->count();
        $jumlahKumuh = $laporans->filter(fn($l) => str_contains(strtolower($l->kategori->nama_kategori), 'padat') || str_contains(strtolower($l->kategori->nama_kategori), 'kumuh') || str_contains(strtolower($l->kategori->nama_kategori), 'permukiman'))->count();

        $statusAman = 'Aman';
        $statusWarna = 'green';
        if ($jumlahBanjir > 0) {
            $statusAman = 'Waspada Banjir';
            $statusWarna = 'red';
        } elseif ($jumlahMacet > 2 || $jumlahKumuh > 1) {
            $statusAman = 'Cukup Ramai / Padat';
            $statusWarna = 'yellow';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'ringkasan' => [
                    'status_aman' => $statusAman,
                    'status_warna' => $statusWarna,
                    'total_laporan' => $laporans->count(),
                    'banjir' => $jumlahBanjir,
                    'macet' => $jumlahMacet,
                    'permukiman' => $jumlahKumuh,
                    'radius' => $radius,
                ],
                'laporans' => $laporans->map(function ($l) {
                    return [
                        'id' => $l->id,
                        'kategori' => $l->kategori->nama_kategori,
                        'ikon' => $l->kategori->ikon,
                        'warna' => $l->kategori->warna,
                        'latitude' => (float) $l->latitude,
                        'longitude' => (float) $l->longitude,
                        'wilayah' => $l->wilayah,
                        'deskripsi' => $l->deskripsi,
                        'tingkat_keparahan' => $l->tingkat_keparahan,
                        'jarak' => round($l->jarak, 2),
                        'tanggal' => $l->created_at->format('d M Y'),
                    ];
                }),
                'kos' => $kos->map(function ($k) {
                    return [
                        'id' => $k->id,
                        'nama' => $k->nama,
                        'alamat' => $k->alamat,
                        'wilayah' => $k->wilayah,
                        'latitude' => (float) $k->latitude,
                        'longitude' => (float) $k->longitude,
                        'harga_per_bulan' => $k->harga_per_bulan,
                        'kontak' => $k->kontak,
                        'jarak' => round($k->jarak, 2),
                    ];
                }),
            ]);
        }

        return view('cari-kos.index', compact('laporans', 'kos', 'lat', 'lng', 'radius', 'statusAman', 'statusWarna'));
    }
}
