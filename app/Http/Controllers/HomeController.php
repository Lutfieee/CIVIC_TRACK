<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Laporan;
use App\Models\Transportasi;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Halaman Dashboard Utama Terintegrasi (FR-5)
     */
    public function index()
    {
        $stats = [
            'total_laporan' => Laporan::count(),
            'terverifikasi' => Laporan::whereIn('status', ['diverifikasi', 'diproses', 'selesai'])->count(),
            'diproses'      => Laporan::where('status', 'diproses')->count(),
            'selesai'       => Laporan::where('status', 'selesai')->count(),
            'pending'       => Laporan::where('status', 'pending')->count(),
            'total_warga'   => User::where('role', 'warga')->count(),
            'total_moda'    => Transportasi::where('is_active', true)->count(),
            'moda_normal'   => Transportasi::where('is_active', true)->where('status_operasional', 'normal')->count(),
        ];

        $laporanTerbaru = Laporan::with(['user', 'kategori'])
            ->whereIn('status', ['diverifikasi', 'diproses', 'selesai'])
            ->latest()
            ->take(6)
            ->get();

        $transportasiUtama = Transportasi::where('is_active', true)
            ->take(4)
            ->get();

        $kategoris = Kategori::all();

        return view('welcome', compact('stats', 'laporanTerbaru', 'transportasiUtama', 'kategoris'));
    }
}
