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
            <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto laporan" class="w-full h-64 object-cover">
        @endif

        <div class="p-8">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-3xl">{{ $laporan->kategori->ikon }}</span>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $laporan->kategori->nama_kategori }}</h1>
                    </div>
                    <p class="text-gray-500 text-sm">📍 {{ $laporan->wilayah }} · {{ $laporan->created_at->format('d M Y') }}</p>
                </div>
                <span class="px-3 py-1.5 rounded-full text-sm font-semibold
                    {{ $laporan->tingkat_keparahan === 'tinggi' ? 'bg-red-100 text-red-700' : ($laporan->tingkat_keparahan === 'sedang' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                    {{ ucfirst($laporan->tingkat_keparahan) }}
                </span>
            </div>

            <p class="text-gray-700 leading-relaxed mb-8">{{ $laporan->deskripsi }}</p>

            {{-- Mini Map --}}
            <div id="mini-map" class="w-full h-56 rounded-2xl overflow-hidden mb-8 border border-gray-200"></div>

            {{-- Reporter Info --}}
            <div class="bg-gray-50 rounded-2xl p-4 mb-8">
                <p class="text-sm text-gray-500">Dilaporkan oleh</p>
                <p class="font-semibold text-gray-800">{{ $laporan->user->name }}</p>
                @if($laporan->verifikasi)
                    <p class="text-xs text-green-600 mt-1">✓ Diverifikasi oleh {{ $laporan->verifikasi->administrator->name }} pada {{ $laporan->verifikasi->tanggal_verifikasi->format('d M Y') }}</p>
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
