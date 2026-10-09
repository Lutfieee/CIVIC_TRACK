<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Laporan;
use App\Models\Transportasi;
use App\Models\User;
use App\Models\Verifikasi;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_laporan' => Laporan::count(),
            'pending' => Laporan::where('status', 'pending')->count(),
            'diverifikasi' => Laporan::where('status', 'diverifikasi')->count(),
            'diproses' => Laporan::where('status', 'diproses')->count(),
            'selesai' => Laporan::where('status', 'selesai')->count(),
            'ditolak' => Laporan::where('status', 'ditolak')->count(),
            'total_transportasi' => Transportasi::where('is_active', true)->count(),
            'total_wilayah' => Wilayah::count(),
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

    /**
     * Mengelola seluruh laporan yang masuk (FR-12, FR-17, FR-18)
     */
    public function kelolaLaporan(Request $request)
    {
        $query = Laporan::with(['user', 'kategori']);

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

        $laporans = $query->latest()->paginate(15)->withQueryString();
        $kategoris = Kategori::all();
        $wilayahs = Wilayah::orderBy('nama')->get();

        return view('admin.laporan.index', compact('laporans', 'kategoris', 'wilayahs'));
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
