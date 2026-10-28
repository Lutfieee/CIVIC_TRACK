@extends('layouts.dashboard')

@section('title', 'Kelola & Detail Laporan')
@section('page-title', 'Detail & Penanganan Laporan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>#mini-map { height: 240px; border-radius: 12px; }</style>
@endpush

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($laporan->foto)
            <div class="bg-gray-900 border-b border-gray-100 flex items-center justify-center max-h-[420px] overflow-hidden">
                <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto Laporan" class="w-full max-h-[420px] object-contain"
                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'p-8 text-center text-gray-400 text-sm\'>Foto laporan tidak dapat dimuat atau berkas tidak ditemukan di server.</div>';">
            </div>
        @endif

        <div class="p-6 sm:p-8">
            {{-- Header info --}}
            <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                            {{ $laporan->kategori->ikon }} {{ $laporan->kategori->nama_kategori }} • {{ $laporan->wilayah }}
                        </span>
                        @if($laporan->kategori->dinas_terkait)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                🏛️ Dinas: {{ $laporan->kategori->dinas_terkait }}
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $laporan->judul ?? 'Laporan Masalah #' . $laporan->id }}</h1>
                    <p class="text-xs text-gray-400 mt-1">
                        Dilaporkan oleh <strong class="text-gray-700">{{ $laporan->user->name }}</strong> ({{ $laporan->user->email }}) • {{ $laporan->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>
                <div class="flex flex-col items-end gap-1.5">
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                        {{ $laporan->status == 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                        {{ $laporan->status == 'diverifikasi' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $laporan->status == 'diproses' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $laporan->status == 'selesai' ? 'bg-emerald-100 text-emerald-800' : '' }}
                        {{ $laporan->status == 'ditolak' ? 'bg-rose-100 text-rose-800' : '' }}">
                        Status: {{ $laporan->status_label }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold
                        {{ $laporan->tingkat_keparahan === 'tinggi' ? 'bg-red-100 text-red-700' : ($laporan->tingkat_keparahan === 'sedang' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                        Keparahan: {{ ucfirst($laporan->tingkat_keparahan) }}
                    </span>
                </div>
            </div>

            {{-- Deskripsi Masalah --}}
            <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-100">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Deskripsi Kejadian</h3>
                <p class="text-gray-800 text-sm leading-relaxed whitespace-pre-line">{{ $laporan->deskripsi }}</p>
            </div>

            {{-- Peta Lokasi Kejadian (FR-8, FR-19) --}}
            <div class="mb-6">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Titik Koordinat Lokasi: {{ $laporan->latitude }}, {{ $laporan->longitude }}</h3>
                <div id="mini-map" class="border border-gray-200 shadow-xs"></div>
            </div>

            {{-- Tindak Lanjut & Catatan Riwayat Saat Ini (FR-15, FR-30) --}}
            @if($laporan->tindak_lanjut || ($laporan->verifikasi && $laporan->verifikasi->alasan_penolakan))
                <div class="p-5 rounded-2xl bg-indigo-50/60 border border-indigo-100 mb-6">
                    <h3 class="text-xs font-bold text-indigo-900 uppercase tracking-wider mb-2">Riwayat Tindak Lanjut & Catatan Lapangan</h3>
                    <p class="text-sm text-indigo-950 leading-relaxed">
                        {{ $laporan->tindak_lanjut ?? $laporan->verifikasi->alasan_penolakan }}
                    </p>
                    @if($laporan->tanggal_selesai)
                        <div class="mt-2 text-xs text-emerald-700 font-semibold">
                            ✓ Penanganan dinyatakan selesai pada: {{ $laporan->tanggal_selesai->format('d M Y, H:i') }} WIB
                        </div>
                    @endif
                </div>
            @endif

            {{-- Form Pembaruan Status & Catatan Tindak Lanjut (FR-14, FR-15) --}}
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-base font-bold text-gray-900 mb-2">Pembaruan Tahapan Status & Tindak Lanjut Laporan</h3>
                <p class="text-xs text-gray-500 mb-4">Ubah tahapan penanganan (Verifikasi, Diproses, Selesai, Tolak) dan input catatan tindak lanjut. Sistem akan otomatis mengirimkan notifikasi ke warga pelapor.</p>

                <form method="POST" action="{{ route('admin.laporan.update-status', $laporan) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Status Penanganan *</label>
                            <select name="status" required class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-white">
                                <option value="pending" {{ $laporan->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu Verifikasi)</option>
                                <option value="diverifikasi" {{ $laporan->status == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi (Disetujui Publik)</option>
                                <option value="diproses" {{ $laporan->status == 'diproses' ? 'selected' : '' }}>Diproses (Sedang Ditangani di Lapangan)</option>
                                <option value="selesai" {{ $laporan->status == 'selesai' ? 'selected' : '' }}>Selesai (Penanganan Tuntas)</option>
                                <option value="ditolak" {{ $laporan->status == 'ditolak' ? 'selected' : '' }}>Ditolak (Hoax / Tidak Valid)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan Tindak Lanjut / Catatan Petugas</label>
                        <textarea name="tindak_lanjut" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none"
                                  placeholder="Contoh: Tim Unit Reaksi Cepat telah diterjunkan ke lokasi untuk perbaikan jalan...">{{ old('tindak_lanjut', $laporan->tindak_lanjut) }}</textarea>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-3 pt-3">
                        <button type="submit" name="redirect_to" value="{{ route('admin.laporan.show', $laporan) }}"
                                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold transition-all">
                            Simpan & Tetap di Sini
                        </button>
                        <button type="submit" name="redirect_to" value="{{ route('admin.antrian') }}"
                                class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] hover:shadow-lg hover:-translate-y-0.5 text-white text-sm font-bold shadow transition-all flex items-center gap-2">
                            <span>✓</span> Simpan & Kembali ke Antrian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.laporan.index') }}" class="text-xs text-gray-500 hover:text-primary transition-colors">
            ← Kembali ke Semua Laporan
        </a>
        <span class="text-gray-300">•</span>
        <a href="{{ route('admin.antrian') }}" class="text-xs text-gray-500 hover:text-primary transition-colors">
            Antrian Verifikasi Pending
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('mini-map').setView([{{ $laporan->latitude }}, {{ $laporan->longitude }}], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution:'OSM'}).addTo(map);
L.circleMarker([{{ $laporan->latitude }}, {{ $laporan->longitude }}], {
    radius: 10, fillColor: '{{ $laporan->kategori->warna }}', color: 'white', weight: 3, fillOpacity: 0.9
}).addTo(map);
</script>
@endpush
