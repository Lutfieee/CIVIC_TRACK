<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Laporan;
use App\Models\User;
use App\Models\Verifikasi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_laporan' => Laporan::count(),
            'pending' => Laporan::where('status', 'pending')->count(),
            'diverifikasi' => Laporan::where('status', 'diverifikasi')->count(),
            'ditolak' => Laporan::where('status', 'ditolak')->count(),
            'total_warga' => User::where('role', 'warga')->count(),
            'total_admin' => User::where('role', 'admin')->count(),
        ];

        // Chart data: laporan per kategori
        $perKategori = Laporan::with('kategori')
            ->selectRaw('kategori_id, count(*) as total')
            ->groupBy('kategori_id')
            ->get()
            ->map(fn($l) => [
                'label' => $l->kategori->nama_kategori ?? 'Lainnya',
                'total' => $l->total,
                'warna' => $l->kategori->warna ?? '#6C3DE6',
            ]);

        // Chart data: laporan per bulan (6 bulan terakhir)
        $perBulan = Laporan::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, count(*) as total")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $laporanTerbaru = Laporan::with(['user', 'kategori'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'perKategori', 'perBulan', 'laporanTerbaru'));
    }

    public function antrian()
    {
        $laporans = Laporan::with(['user', 'kategori'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.verifikasi.antrian', compact('laporans'));
    }

    public function riwayat()
    {
        $laporans = Laporan::with(['user', 'kategori', 'verifikasi'])
            ->whereHas('verifikasi', fn($q) => $q->where(Verifikasi::administratorColumn(), Auth::id()))
            ->latest()
            ->paginate(15);

        return view('admin.verifikasi.riwayat', compact('laporans'));
    }

    public function show(Laporan $laporan)
    {
        $laporan->load(['user', 'kategori', 'komentars.user', 'verifikasi']);
        return view('admin.verifikasi.show', compact('laporan'));
    }

    public function permohonanHapus()
    {
        $laporans = Laporan::with(['user', 'kategori'])
            ->where('status_penghapusan', 'menunggu_verifikasi')
            ->latest('tanggal_pengajuan_hapus')
            ->paginate(15);

        return view('admin.verifikasi.permohonan-hapus', compact('laporans'));
    }
}
