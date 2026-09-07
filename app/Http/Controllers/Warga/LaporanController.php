<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Laporan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    use AuthorizesRequests;

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
        $laporans = Laporan::with('kategori')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('warga.laporan.index', compact('laporans'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $kecamatan = self::KECAMATAN;
        return view('warga.laporan.create', compact('kategoris', 'kecamatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'wilayah' => 'required|string',
            'deskripsi' => 'required|string|min:20|max:2000',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'tingkat_keparahan' => 'required|in:rendah,sedang,tinggi',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }

        Laporan::create([
            'user_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'wilayah' => $request->wilayah,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
            'tingkat_keparahan' => $request->tingkat_keparahan,
            'status' => 'pending',
        ]);

        return redirect()->route('warga.laporan.index')
            ->with('success', 'Laporan berhasil dikirim dan menunggu verifikasi.');
    }

    public function show(Laporan $laporan)
    {
        Gate::authorize('view', $laporan);
        $laporan->load(['kategori', 'komentars.user', 'verifikasi.administrator']);
        return view('warga.laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan)
    {
        Gate::authorize('update', $laporan);
        $kategoris = Kategori::all();
        $kecamatan = self::KECAMATAN;
        return view('warga.laporan.edit', compact('laporan', 'kategoris', 'kecamatan'));
    }

    public function update(Request $request, Laporan $laporan)
    {
        Gate::authorize('update', $laporan);

        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'wilayah' => 'required|string',
            'deskripsi' => 'required|string|min:20|max:2000',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'tingkat_keparahan' => 'required|in:rendah,sedang,tinggi',
        ]);

        $fotoPath = $laporan->foto;
        if ($request->hasFile('foto')) {
            if ($laporan->foto) {
                Storage::disk('public')->delete($laporan->foto);
            }
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }

        $laporan->update([
            'kategori_id' => $request->kategori_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'wilayah' => $request->wilayah,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
            'tingkat_keparahan' => $request->tingkat_keparahan,
        ]);

        return redirect()->route('warga.laporan.show', $laporan)
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Laporan $laporan)
    {
        Gate::authorize('delete', $laporan);

        if ($laporan->foto) {
            Storage::disk('public')->delete($laporan->foto);
        }

        $laporan->delete();

        return redirect()->route('warga.laporan.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    public function ajukanHapus(Request $request, Laporan $laporan)
    {
        // Pastikan laporan milik user yang sedang login
        if ($laporan->user_id !== Auth::id()) {
            abort(403);
        }

        // Tidak boleh mengajukan hapus jika sudah dalam proses menunggu
        if ($laporan->isMenungguHapus()) {
            return back()->with('error', 'Permohonan hapus laporan ini sudah diajukan dan sedang menunggu verifikasi admin.');
        }

        $request->validate([
            'alasan_penghapusan' => 'required|string|min:10|max:500',
        ]);

        $laporan->update([
            'status_penghapusan'      => 'menunggu_verifikasi',
            'alasan_penghapusan'      => $request->alasan_penghapusan,
            'tanggal_pengajuan_hapus' => now(),
        ]);

        return back()->with('success', 'Permohonan penghapusan laporan telah dikirim. Menunggu verifikasi admin.');
    }

    public function batalHapus(Laporan $laporan)
    {
        if ($laporan->user_id !== Auth::id()) {
            abort(403);
        }

        $laporan->update([
            'status_penghapusan'      => null,
            'alasan_penghapusan'      => null,
            'tanggal_pengajuan_hapus' => null,
        ]);

        return back()->with('success', 'Permohonan hapus berhasil dibatalkan.');
    }
}
