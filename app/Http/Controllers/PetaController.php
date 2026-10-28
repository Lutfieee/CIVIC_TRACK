<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Laporan;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    /**
     * Menampilkan peta persebaran laporan masalah (FR-19)
     */
    public function index()
    {
        $kategoris = Kategori::all();
        $wilayahs = Wilayah::orderBy('nama')->get();
        return view('peta.index', compact('kategoris', 'wilayahs'));
    }

    /**
     * API data laporan untuk peta interaktif Leaflet (FR-17, FR-18, FR-19)
     */
    public function data(Request $request)
    {
        // Tampilkan hanya laporan aktif di peta (diverifikasi dan diproses). Laporan selesai dihilangkan.
        $query = Laporan::with(['kategori', 'user'])
            ->whereIn('status', ['diverifikasi', 'diproses']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%")
                    ->orWhere('wilayah', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

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
                'id'               => $laporan->id,
                'judul'            => $laporan->judul ?? 'Laporan Masalah #' . $laporan->id,
                'latitude'         => $laporan->latitude,
                'longitude'        => $laporan->longitude,
                'kategori'         => $laporan->kategori->nama_kategori ?? 'Umum',
                'dinas'            => $laporan->kategori->dinas_terkait ?? null,
                'warna'            => $laporan->kategori->warna ?? '#6C3DE6',
                'ikon'             => $laporan->kategori->ikon ?? '📍',
                'wilayah'          => $laporan->wilayah,
                'deskripsi'        => substr($laporan->deskripsi, 0, 120) . '...',
                'status'           => $laporan->status,
                'status_label'     => $laporan->status_label,
                'tindak_lanjut'    => $laporan->tindak_lanjut,
                'tingkat_keparahan'=> $laporan->tingkat_keparahan,
                'tanggal'          => $laporan->created_at->format('d M Y'),
                'pelapor'          => $laporan->user->name ?? 'Warga',
                'foto'             => $laporan->foto ? asset('storage/' . $laporan->foto) : null,
                'url'              => route('peta.show', $laporan->id),
                'komentar_count'   => $laporan->komentars()->count(),
            ];
        });

        return response()->json($laporans);
    }

    /**
     * Melihat detail laporan publik (FR-10, FR-30)
     */
    public function show($id)
    {
        $laporan = Laporan::with(['kategori', 'user', 'komentars.user', 'verifikasi.administrator'])
            ->whereIn('status', ['diverifikasi', 'diproses', 'selesai'])
            ->findOrFail($id);

        return view('peta.show', compact('laporan'));
    }
}
