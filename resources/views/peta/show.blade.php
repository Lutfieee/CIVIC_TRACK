@extends('layouts.app')

@section('title', 'Detail Laporan — Peta')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <a href="{{ route('peta.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#6C3DE6] mb-6 transition-colors">
        ← Kembali ke Peta
    </a>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        @if($laporan->foto)
            <div class="bg-gray-900 border-b border-gray-100 flex items-center justify-center max-h-[460px] overflow-hidden">
                <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto laporan" class="w-full max-h-[460px] object-contain"
                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'p-8 text-center text-gray-400 text-sm\'>Foto laporan tidak dapat dimuat atau berkas tidak ditemukan di server.</div>';">
            </div>
        @endif

        <div class="p-8">
            <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-700 font-semibold">{{ $laporan->kategori->ikon }} {{ $laporan->kategori->nama_kategori }}</span>
                        @if($laporan->kategori->dinas_terkait)
                            <span class="text-xs px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 font-semibold border border-blue-100">
                                🏛️ {{ $laporan->kategori->dinas_terkait }}
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ $laporan->judul ?? $laporan->kategori->nama_kategori }}</h1>
                    <p class="text-gray-500 text-sm mt-1">📍 Wilayah: <strong class="text-gray-700">{{ $laporan->wilayah }}</strong> · Dilaporkan pada {{ $laporan->created_at->format('d M Y, H:i') }} WIB</p>
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
                        Tingkat: {{ ucfirst($laporan->tingkat_keparahan) }}
                    </span>
                </div>
            </div>

            <p class="text-gray-700 leading-relaxed mb-6">{{ $laporan->deskripsi }}</p>

            @if($laporan->tindak_lanjut)
                <div class="bg-indigo-50/80 border border-indigo-200 rounded-2xl p-5 mb-8">
                    <div class="flex items-center gap-2 text-indigo-900 font-bold text-sm mb-1.5">
                        <span>📢</span> Catatan & Tindak Lanjut Penanganan:
                    </div>
                    <p class="text-indigo-950 text-sm leading-relaxed">{{ $laporan->tindak_lanjut }}</p>
                    @if($laporan->tanggal_selesai)
                        <div class="mt-2 text-xs text-emerald-700 font-semibold flex items-center gap-1">
                            <span>✓</span> Selesai ditangani pada: {{ $laporan->tanggal_selesai->format('d M Y, H:i') }} WIB
                        </div>
                    @endif
                </div>
            @endif

            {{-- Mini Map --}}
            <div id="mini-map" class="w-full h-56 rounded-2xl overflow-hidden mb-8 border border-gray-200"></div>

            {{-- Reporter Info --}}
            <div class="bg-gray-50 rounded-2xl p-4 mb-8 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Dilaporkan oleh Warga</p>
                    <p class="font-bold text-gray-800 text-sm">{{ $laporan->user->name }}</p>
                </div>
                @if($laporan->verifikasi)
                    <div class="text-right">
                        <p class="text-xs text-emerald-700 font-semibold">✓ Diverifikasi oleh Petugas</p>
                        <p class="text-xs text-gray-400">{{ $laporan->verifikasi->tanggal_verifikasi?->format('d M Y') }}</p>
                    </div>
                @endif
            </div>

            {{-- Comments --}}
            <h2 class="text-lg font-bold text-gray-800 mb-4">Komentar ({{ $laporan->komentars->count() }})</h2>

            {{-- Alert success/error --}}
            @if(session('success'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-center gap-2">
                    <span>✓</span> {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="space-y-4 mb-6">
                @forelse($laporan->komentars as $komentar)
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#6C3DE6] to-[#3B82F6] flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($komentar->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 bg-gray-50 rounded-xl p-3">
                            <p class="text-sm font-semibold text-gray-800">{{ $komentar->user->name }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $komentar->isi }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $komentar->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada komentar. Jadilah yang pertama!</p>
                @endforelse
            </div>

            {{-- Form Komentar / Login Gate --}}
            @auth
                <form method="POST" action="{{ route('peta.komentar.store', $laporan) }}" class="flex gap-3">
                    @csrf
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#6C3DE6] to-[#3B82F6] flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 flex gap-2">
                        <input type="text" name="isi" placeholder="Tulis komentar Anda..." required minlength="3"
                               class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6] bg-gray-50 transition">
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all whitespace-nowrap">
                            Kirim
                        </button>
                    </div>
                </form>
            @else
                <div class="rounded-2xl border-2 border-dashed border-[#6C3DE6]/30 bg-[#6C3DE6]/5 p-5 text-center">
                    <p class="text-2xl mb-2">💬</p>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Ingin ikut berkomentar?</p>
                    <p class="text-xs text-gray-500 mb-4">Bergabunglah dengan komunitas SIGAP untuk memberikan informasi terkini seputar kejadian di lapangan.</p>
                    <div class="flex items-center justify-center gap-3">
                        <a href="{{ route('register') }}"
                           class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all">
                            Daftar Akun
                        </a>
                        <a href="{{ route('login') }}"
                           class="px-5 py-2 rounded-xl border-2 border-[#6C3DE6] text-[#6C3DE6] text-sm font-semibold hover:bg-[#6C3DE6]/5 transition-all">
                            Masuk
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('mini-map').setView([{{ $laporan->latitude }}, {{ $laporan->longitude }}], 16);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OSM' }).addTo(map);
L.circleMarker([{{ $laporan->latitude }}, {{ $laporan->longitude }}], {
    radius: 12, fillColor: '{{ $laporan->kategori->warna }}', color: 'white', weight: 3, fillOpacity: 0.9
}).addTo(map).bindPopup('{{ $laporan->kategori->nama_kategori }}').openPopup();
</script>
@endpush
