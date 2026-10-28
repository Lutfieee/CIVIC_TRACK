<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Laporan;
use App\Models\Wilayah;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    use AuthorizesRequests;

    const DEFAULT_KECAMATAN = [
        'Andir', 'Antapani', 'Arcamanik', 'Astanaanyar', 'Babakan Ciparay', 
        'Bandung Kidul', 'Bandung Kulon', 'Bandung Wetan', 'Batununggal', 'Bojongloa Kaler',
        'Bojongloa Kidul','Bojongsoang', 'Buahbatu', 'Cibeunying Kaler', 'Cibeunying Kidul', 'Cibiru',
        'Cicendo', 'Cidadap', 'Cinambo', 'Coblong', 'Gedebage',
        'Kiaracondong', 'Lengkong', 'Mandalajati', 'Margacinta', 'Panyileukan',
        'Rancasari', 'Regol', 'Sukajadi', 'Sukasari', 'Sumur Bandung',
    ];

    private function getDaftarWilayah()
    {
        $fromDb = Wilayah::pluck('nama')->toArray();
        return array_unique(array_merge($fromDb, self::DEFAULT_KECAMATAN));
    }

    public function index()
    {
        $laporans = Laporan::with('kategori')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('warga.laporan.index', compact('laporans'));
    }

    public function cepat(Request $request)
    {
        $kategoris = Kategori::all();
        $kecamatan = $this->getDaftarWilayah();
        $initLat   = $request->query('lat', -6.9175);
        $initLng   = $request->query('lng', 107.6191);
        return view('warga.laporan.cepat', compact('kategoris', 'kecamatan', 'initLat', 'initLng'));
    }

    public function storeCepat(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'wilayah' => 'nullable|string',
            'deskripsi' => 'required|string|min:5|max:1000',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'tingkat_keparahan' => 'nullable|in:rendah,sedang,tinggi',
        ], [
            'kategori_id.required' => 'Silakan pilih salah satu kategori permasalahan.',
            'deskripsi.required' => 'Keterangan singkat kejadian wajib diisi.',
            'latitude.required' => 'Titik lokasi belum ditentukan.',
            'longitude.required' => 'Titik lokasi belum ditentukan.',
        ]);

        $kategori = Kategori::findOrFail($request->kategori_id);
        $wilayah = $request->wilayah ?: 'Kabupaten Bandung';
        $judul = $request->filled('judul') ? $request->judul : ($kategori->nama_kategori . ' di ' . $wilayah);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }

        Laporan::create([
            'user_id' => Auth::id(),
            'kategori_id' => $kategori->id,
            'judul' => $judul,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'wilayah' => $wilayah,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
            'tingkat_keparahan' => $request->tingkat_keparahan ?: 'sedang',
            'status' => 'pending',
        ]);

        return redirect()->route('warga.laporan.index')
            ->with('success', '⚡ Laporan Cepat Anda berhasil dikirim dan otomatis masuk dalam antrean verifikasi!');
    }

    public function create(Request $request)
    {
        $kategoris = Kategori::all();
        $kecamatan = $this->getDaftarWilayah();
        $initLat   = $request->query('lat');
        $initLng   = $request->query('lng');
        return view('warga.laporan.create', compact('kategoris', 'kecamatan', 'initLat', 'initLng'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|min:5|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'wilayah' => 'required|string',
            'deskripsi' => 'required|string|min:15|max:2000',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'tingkat_keparahan' => 'required|in:rendah,sedang,tinggi',
        ], [
            'judul.required' => 'Judul laporan wajib diisi.',
            'kategori_id.required' => 'Kategori permasalahan wajib dipilih.',
            'deskripsi.required' => 'Deskripsi kejadian wajib diisi minimal 15 karakter.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }

        Laporan::create([
            'user_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'judul' => $request->judul,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'wilayah' => $request->wilayah,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
            'tingkat_keparahan' => $request->tingkat_keparahan,
            'status' => 'pending',
        ]);

        return redirect()->route('warga.laporan.index')
            ->with('success', 'Laporan berhasil dikirim dan kini masuk dalam antrean verifikasi.');
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
        $kecamatan = $this->getDaftarWilayah();
        return view('warga.laporan.edit', compact('laporan', 'kategoris', 'kecamatan'));
    }

    public function update(Request $request, Laporan $laporan)
    {
        Gate::authorize('update', $laporan);

        $request->validate([
            'judul' => 'required|string|min:5|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'wilayah' => 'required|string',
            'deskripsi' => 'required|string|min:15|max:2000',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
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
            'judul' => $request->judul,
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
        if ($laporan->user_id !== Auth::id()) {
            abort(403);
        }

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
