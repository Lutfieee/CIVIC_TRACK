<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('laporans')->get();
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris',
            'dinas_terkait' => 'nullable|string|max:255',
            'ikon' => 'required|string|max:10',
            'warna' => 'required|string|max:20',
        ]);

        Kategori::create($request->only('nama_kategori', 'dinas_terkait', 'ikon', 'warna'));

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori,' . $kategori->id,
            'dinas_terkait' => 'nullable|string|max:255',
            'ikon' => 'required|string|max:10',
            'warna' => 'required|string|max:20',
        ]);

        $kategori->update($request->only('nama_kategori', 'dinas_terkait', 'ikon', 'warna'));

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->laporans()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang sudah memiliki laporan.');
        }
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
