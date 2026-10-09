<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transportasi;
use Illuminate\Http\Request;

class TransportasiController extends Controller
{
    /**
     * Menampilkan daftar transportasi umum untuk dikelola Admin (FR-24)
     */
    public function index(Request $request)
    {
        $query = Transportasi::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_moda', 'like', "%{$q}%")
                    ->orWhere('kode_trayek', 'like', "%{$q}%")
                    ->orWhere('rute_asal', 'like', "%{$q}%")
                    ->orWhere('rute_tujuan', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status_operasional', $request->status);
        }

        $transportasis = $query->orderBy('nama_moda')->paginate(10)->withQueryString();

        return view('admin.transportasi.index', compact('transportasis'));
    }

    public function create()
    {
        return view('admin.transportasi.create');
    }

    /**
     * Menyimpan data transportasi baru (FR-24, FR-25)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_moda' => 'required|string|max:255',
            'tipe' => 'required|in:Bus,Angkot,Kereta,Feeder',
            'kode_trayek' => 'required|string|max:100',
            'rute_asal' => 'required|string|max:255',
            'rute_tujuan' => 'required|string|max:255',
            'titik_pemberhentian' => 'required|string',
            'jadwal_operasional' => 'required|string|max:255',
            'tarif' => 'required|string|max:100',
            'status_operasional' => 'required|in:normal,terkendala,perbaikan,tidak_beroperasi',
            'keterangan_operasional' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Transportasi::create($validated);

        return redirect()->route('admin.transportasi.index')->with('success', 'Data transportasi berhasil ditambahkan!');
    }

    public function edit(Transportasi $transportasi)
    {
        return view('admin.transportasi.edit', compact('transportasi'));
    }

    /**
     * Memperbarui data transportasi, rute, jadwal, dan status operasional (FR-24, FR-25, FR-26)
     */
    public function update(Request $request, Transportasi $transportasi)
    {
        $validated = $request->validate([
            'nama_moda' => 'required|string|max:255',
            'tipe' => 'required|in:Bus,Angkot,Kereta,Feeder',
            'kode_trayek' => 'required|string|max:100',
            'rute_asal' => 'required|string|max:255',
            'rute_tujuan' => 'required|string|max:255',
            'titik_pemberhentian' => 'required|string',
            'jadwal_operasional' => 'required|string|max:255',
            'tarif' => 'required|string|max:100',
            'status_operasional' => 'required|in:normal,terkendala,perbaikan,tidak_beroperasi',
            'keterangan_operasional' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $transportasi->update($validated);

        return redirect()->route('admin.transportasi.index')->with('success', 'Data transportasi berhasil diperbarui!');
    }

    public function destroy(Transportasi $transportasi)
    {
        $transportasi->delete();
        return redirect()->route('admin.transportasi.index')->with('success', 'Data transportasi berhasil dihapus!');
    }
}
