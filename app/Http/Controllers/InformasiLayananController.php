<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Transportasi;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class InformasiLayananController extends Controller
{
    /**
     * Menampilkan panduan layanan pelaporan dan transportasi umum (FR-32)
     */
    public function index()
    {
        $kategoris = Kategori::all();
        $totalWilayah = Wilayah::count();
        $totalModa = Transportasi::where('is_active', true)->count();

        return view('informasi-layanan.index', compact('kategoris', 'totalWilayah', 'totalModa'));
    }
}
