@extends('layouts.dashboard')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>#mini-map { height: 200px; border-radius: 12px; }</style>
@endpush

@section('content')
<div class="max-w-3xl space-y-6">
    {{-- Laporan Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($laporan->foto)
            <img src="{{ asset('storage/' . $laporan->foto) }}" class="w-full h-52 object-cover">
        @endif
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-2xl">{{ $laporan->kategori?->ikon ?? '📌' }}</span>
                        <h2 class="text-xl font-bold text-gray-800">{{ $laporan->kategori?->nama_kategori ?? 'Kategori Dihapus' }}</h2>
                    </div>
                    <p class="text-sm text-gray-500">📍 {{ $laporan->wilayah }} · {{ $laporan->created_at->format('d M Y') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $laporan->tingkat_keparahan === 'tinggi' ? 'bg-red-100 text-red-700' : ($laporan->tingkat_keparahan === 'sedang' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                        {{ ucfirst($laporan->tingkat_keparahan) }}
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $laporan->status === 'diverifikasi' ? 'bg-green-100 text-green-700' : ($laporan->status === 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ $laporan->status === 'diverifikasi' ? '✓ Terverifikasi' : ($laporan->status === 'ditolak' ? '✕ Ditolak' : '⏳ Pending') }}
                    </span>
                </div>
            </div>

            <p class="text-gray-700 text-sm leading-relaxed mb-6">{{ $laporan->deskripsi }}</p>

            @if($laporan->status === 'ditolak' && $laporan->verifikasi?->alasan_penolakan)
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                    <p class="text-sm font-semibold text-red-700 mb-1">Alasan Penolakan:</p>
                    <p class="text-sm text-red-600">{{ $laporan->verifikasi->alasan_penolakan }}</p>
                </div>
            @endif

            <div id="mini-map" class="border border-gray-200 mb-4"></div>

            @if($laporan->status === 'pending')
                <div class="flex gap-3">
                    <a href="{{ route('warga.laporan.edit', $laporan) }}" class="flex-1 py-2.5 rounded-xl border-2 border-[#6C3DE6] text-[#6C3DE6] text-sm font-semibold text-center hover:bg-[#6C3DE6]/5 transition-all">Edit</a>
                    <form method="POST" action="{{ route('warga.laporan.destroy', $laporan) }}" class="flex-1" onsubmit="return confirm('Hapus laporan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-2.5 rounded-xl border-2 border-red-300 text-red-600 text-sm font-semibold hover:bg-red-50 transition-all">Hapus</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    {{-- Notifikasi Permohonan Hapus --}}
    @if($laporan->isMenungguHapus())
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
            <span class="text-xl">⏳</span>
            <div>
                <p class="text-sm font-semibold text-amber-800">Permohonan Penghapusan Menunggu Verifikasi Admin</p>
                <p class="text-xs text-amber-700 mt-0.5">Diajukan pada {{ $laporan->tanggal_pengajuan_hapus->format('d M Y, H:i') }} WIB.</p>
                <p class="text-xs text-amber-600 mt-0.5">Alasan: "{{ $laporan->alasan_penghapusan }}"</p>
                <form method="POST" action="{{ route('warga.laporan.batal-hapus', $laporan) }}" class="mt-2">
                    @csrf
                    <button type="submit" class="text-xs px-3 py-1 rounded-lg border border-amber-400 text-amber-700 hover:bg-amber-100 transition-colors">
                        Batalkan Permohonan
                    </button>
                </form>
            </div>
        </div>
    @endif
    @if($laporan->status_penghapusan === 'ditolak_hapus')
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
            <span class="text-xl text-red-600 font-bold">✕</span>
            <div class="flex-1">
                <p class="text-sm font-semibold text-red-700">Permohonan Penghapusan Ditolak Admin</p>
                <p class="text-xs text-red-600 mt-0.5">Laporan ini tidak jadi dihapus. Anda dapat mengajukan permohonan baru jika diperlukan.</p>
                @if($laporan->alasan_penolakan_hapus)
                    <div class="mt-2.5 p-2.5 bg-white/70 rounded-xl border border-red-200/80">
                        <p class="text-xs font-semibold text-red-800">Alasan Penolakan dari Admin:</p>
                        <p class="text-xs text-red-700 mt-0.5 font-medium">"{{ $laporan->alasan_penolakan_hapus }}"</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Comments (read-only di dashboard, form komentar ada di halaman peta) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800">Komentar ({{ $laporan->komentars->count() }})</h3>
            @if($laporan->status === 'diverifikasi')
                <a href="{{ route('peta.show', $laporan) }}"
                   class="text-xs px-3 py-1.5 rounded-lg bg-[#6C3DE6]/10 text-[#6C3DE6] font-medium hover:bg-[#6C3DE6]/20 transition-colors flex items-center gap-1">
                    🗺️ Lihat di Peta & Beri Komentar
                </a>
            @endif
        </div>
        <div class="space-y-4">
            @forelse($laporan->komentars as $komentar)
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#6C3DE6] to-[#3B82F6] text-white text-xs font-bold flex items-center justify-center flex-shrink-0">
                        {{ strtoupper(substr($komentar->user?->name ?? 'W', 0, 1)) }}
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-xl p-3">
                        <p class="text-sm font-semibold text-gray-800">{{ $komentar->user?->name ?? 'Pengguna' }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $komentar->isi }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $komentar->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-6">
                    <p class="text-sm text-gray-400">Belum ada komentar.</p>
                    @if($laporan->status === 'diverifikasi')
                        <a href="{{ route('peta.show', $laporan) }}" class="inline-block mt-2 text-xs text-[#6C3DE6] hover:underline">
                            Tambah komentar via halaman peta →
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('mini-map').setView([{{ $laporan->latitude }}, {{ $laporan->longitude }}], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution:'© OSM'}).addTo(map);
L.circleMarker([{{ $laporan->latitude }}, {{ $laporan->longitude }}], {
    radius: 10, fillColor: '{{ $laporan->kategori?->warna ?? '#6C3DE6' }}', color: 'white', weight: 3, fillOpacity: 0.9
}).addTo(map);
</script>
@endpush
