<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LokasiController extends Controller
{
    const KECAMATAN = [
        'Andir', 'Antapani', 'Arcamanik', 'Astanaanyar', 'Babakan Ciparay', 
        'Bandung Kidul', 'Bandung Kulon', 'Bandung Wetan', 'Batununggal', 'Bojongloa Kaler',
        'Bojongloa Kidul','Bojongsoang', 'Buahbatu', 'Cibeunying Kaler', 'Cibeunying Kidul', 'Cibiru',
        'Cicendo', 'Cidadap', 'Cinambo', 'Coblong', 'Gedebage',
        'Kiaracondong', 'Lengkong', 'Mandalajati', 'Margacinta', 'Panyileukan',
        'Rancasari', 'Regol', 'Sukajadi', 'Sukasari', 'Sumur Bandung',
    ];

    public function index()
    {
        $lokasis = Lokasi::with('kategoris')->latest()->paginate(15);
        return view('admin.lokasi.index', compact('lokasis'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $kecamatan = self::KECAMATAN;
        return view('admin.lokasi.create', compact('kategoris', 'kecamatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:200',
            'alamat' => 'required|string|max:500',
            'wilayah' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'harga_per_bulan' => 'nullable|string|max:50',
            'kontak' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'is_active' => 'boolean',
            'kategoris' => 'nullable|array',
            'kategoris.*' => 'exists:kategoris,id',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('lokasi', 'public');
        }

        $lokasi = Lokasi::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'wilayah' => $request->wilayah,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'deskripsi' => $request->deskripsi,
            'harga_per_bulan' => $request->harga_per_bulan,
            'kontak' => $request->kontak,
            'foto' => $fotoPath,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->filled('kategoris')) {
            $lokasi->kategoris()->attach($request->kategoris);
        }

        return redirect()->route('admin.lokasi.index')->with('success', 'Lokasi kos berhasil ditambahkan.');
    }

    public function edit(Lokasi $lokasi)
    {
        $kategoris = Kategori::all();
        $kecamatan = self::KECAMATAN;
        $lokasi->load('kategoris');
        return view('admin.lokasi.edit', compact('lokasi', 'kategoris', 'kecamatan'));
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $request->validate([
            'nama' => 'required|string|max:200',
            'alamat' => 'required|string|max:500',
            'wilayah' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'harga_per_bulan' => 'nullable|string|max:50',
            'kontak' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $fotoPath = $lokasi->foto;
        if ($request->hasFile('foto')) {
            if ($lokasi->foto) {
                Storage::disk('public')->delete($lokasi->foto);
            }
            $fotoPath = $request->file('foto')->store('lokasi', 'public');
        }

        $lokasi->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'wilayah' => $request->wilayah,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'deskripsi' => $request->deskripsi,
            'harga_per_bulan' => $request->harga_per_bulan,
            'kontak' => $request->kontak,
            'foto' => $fotoPath,
            'is_active' => $request->boolean('is_active'),
        ]);

        $lokasi->kategoris()->sync($request->kategoris ?? []);

        return redirect()->route('admin.lokasi.index')->with('success', 'Lokasi kos berhasil diperbarui.');
    }

    public function destroy(Lokasi $lokasi)
    {
        if ($lokasi->foto) {
            Storage::disk('public')->delete($lokasi->foto);
        }
        $lokasi->kategoris()->detach();
        $lokasi->delete();
        return back()->with('success', 'Lokasi kos berhasil dihapus.');
    }
}
