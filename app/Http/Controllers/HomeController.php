<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Laporan;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total_laporan' => Laporan::count(),
            'terverifikasi' => Laporan::where('status', 'diverifikasi')->count(),
            'pending' => Laporan::where('status', 'pending')->count(),
            'total_warga' => User::where('role', 'warga')->count(),
        ];

        $laporanTerbaru = Laporan::with(['user', 'kategori'])
            ->where('status', 'diverifikasi')
            ->latest()
            ->take(3)
            ->get();

        return view('welcome', compact('stats', 'laporanTerbaru'));
    }
}
