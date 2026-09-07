<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Laporan;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('peta.index', compact('kategoris'));
    }

    public function data(Request $request)
    {
        $query = Laporan::with(['kategori', 'user'])
            ->where('status', 'diverifikasi');

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('wilayah')) {
            $query->where('wilayah', $request->wilayah);
        }

        if ($request->filled('keparahan')) {
            $query->where('tingkat_keparahan', $request->keparahan);
        }

        $laporans = $query->get()->map(function ($laporan) {
            return [
                'id' => $laporan->id,
                'latitude' => $laporan->latitude,
                'longitude' => $laporan->longitude,
                'kategori' => $laporan->kategori->nama_kategori,
                'warna' => $laporan->kategori->warna,
                'ikon' => $laporan->kategori->ikon,
                'wilayah' => $laporan->wilayah,
                'deskripsi' => substr($laporan->deskripsi, 0, 100) . '...',
                'tingkat_keparahan' => $laporan->tingkat_keparahan,
                'tanggal' => $laporan->created_at->format('d M Y'),
                'pelapor' => $laporan->user->name,
                'foto' => $laporan->foto ? asset('storage/' . $laporan->foto) : null,
                'url' => route('peta.show', $laporan->id),
            ];
        });

        return response()->json($laporans);
    }

    public function show($id)
    {
        $laporan = Laporan::with(['kategori', 'user', 'komentars.user', 'verifikasi.administrator'])
            ->where('status', 'diverifikasi')
            ->findOrFail($id);

        return view('peta.show', compact('laporan'));
    }
}
