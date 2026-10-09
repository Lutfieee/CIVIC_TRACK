<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    /**
     * Menampilkan daftar wilayah (FR-34)
     */
    public function index(Request $request)
    {
        $query = Wilayah::query();

        if ($request->filled('q')) {
            $query->where('nama', 'like', "%{$request->q}%");
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $wilayahs = $query->orderBy('nama')->paginate(10)->withQueryString();

        return view('admin.wilayah.index', compact('wilayahs'));
    }

    public function create()
    {
        return view('admin.wilayah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:wilayahs,nama',
            'tipe' => 'required|in:Kecamatan,Kelurahan,Desa,Kota/Kabupaten',
            'kode_pos' => 'nullable|string|max:10',
            'deskripsi' => 'nullable|string',
        ]);

        Wilayah::create($validated);

        return redirect()->route('admin.wilayah.index')->with('success', 'Data wilayah berhasil ditambahkan!');
    }

    public function edit(Wilayah $wilayah)
    {
        return view('admin.wilayah.edit', compact('wilayah'));
    }

    public function update(Request $request, Wilayah $wilayah)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:wilayahs,nama,' . $wilayah->id,
            'tipe' => 'required|in:Kecamatan,Kelurahan,Desa,Kota/Kabupaten',
            'kode_pos' => 'nullable|string|max:10',
            'deskripsi' => 'nullable|string',
        ]);

        $wilayah->update($validated);

        return redirect()->route('admin.wilayah.index')->with('success', 'Data wilayah berhasil diperbarui!');
    }

    public function destroy(Wilayah $wilayah)
    {
        $wilayah->delete();
        return redirect()->route('admin.wilayah.index')->with('success', 'Data wilayah berhasil dihapus!');
    }
}
