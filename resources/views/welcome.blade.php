@extends('layouts.app')

@section('title', 'SIGAP — Sistem Informasi Geospasial Aman & Peduli')

@section('content')
{{-- 1. Hero Section --}}
<section class="relative overflow-hidden pt-12 pb-24 lg:pt-20 lg:pb-32 bg-gradient-to-b from-purple-50/50 via-white to-white">
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[900px] h-[400px] bg-gradient-to-r from-purple-300/25 via-blue-200/25 to-indigo-200/20 blur-3xl rounded-full"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            {{-- Left Content --}}
            <div class="lg:col-span-7 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-purple-100/70 border border-purple-200 text-[#6C3DE6] text-xs font-semibold uppercase tracking-wider mb-6">
                    <span class="w-2 h-2 rounded-full bg-[#6C3DE6] animate-ping"></span>
                    <span>Platform Geospasial Berkelanjutan</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-gray-900 tracking-tight leading-[1.15] mb-6">
                    Satu peta, <span class="bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] bg-clip-text text-transparent">tiga risiko</span>, keputusan lebih aman.
                </h1>

                <p class="text-base sm:text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto lg:mx-0 mb-8 font-normal">
                    Pantau riwayat banjir, titik kemacetan parah, dan kepadatan permukiman di seluruh Kota Bandung secara terpusat. Temukan hunian kos yang bebas banjir dan terverifikasi untuk mahasiswa.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <a href="{{ route('peta.index') }}"
                       class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white font-bold text-base shadow-lg shadow-purple-500/25 hover:shadow-xl hover:shadow-purple-500/35 hover:-translate-y-0.5 transition-all text-center flex items-center justify-center gap-2">
                        <span>🗺️</span> Jelajahi Peta Interaktif
                    </a>
                    <a href="{{ route('cari-kos.index') }}"
                       class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-white border-2 border-gray-200 text-gray-800 font-bold text-base hover:border-[#6C3DE6] hover:text-[#6C3DE6] hover:-translate-y-0.5 transition-all text-center flex items-center justify-center gap-2 shadow-sm">
                        <span>🏡</span> Cari Kos Aman
                    </a>
                </div>

                <div class="mt-10 flex items-center justify-center lg:justify-start gap-6 text-xs text-gray-500 font-medium">
                    <div class="flex items-center gap-2">
                        <span class="text-green-500 text-base">✓</span> 100% Terverifikasi Dishub & BPBD
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-blue-500 text-base">✓</span> 30 Kecamatan se-Kota Bandung
                    </div>
                </div>
            </div>

            {{-- Right Mockup / Card --}}
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-md bg-gradient-to-b from-gray-900 to-gray-800 p-4 rounded-3xl shadow-2xl border border-gray-700">
                    <div class="bg-gray-950 rounded-2xl p-4 overflow-hidden text-white relative">
                        {{-- Mockup Top Bar --}}
                        <div class="flex items-center justify-between pb-3 border-b border-gray-800 text-xs">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                            </div>
                            <span class="text-gray-400 font-mono text-[11px]">sigap-map.live</span>
                            <span class="text-emerald-400 font-bold text-[10px] bg-emerald-950 px-2 py-0.5 rounded-full border border-emerald-800">ONLINE</span>
                        </div>

                        {{-- Simulated Map Display --}}
                        <div class="relative h-64 bg-slate-900 rounded-xl my-3 overflow-hidden border border-gray-800 flex items-center justify-center">
                            {{-- Grid pattern background --}}
                            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#6C3DE6_1px,transparent_1px)] [background-size:16px_16px]"></div>

                            {{-- Animated Marker 1 (Banjir) --}}
                            <div class="absolute top-12 left-16 bg-blue-500/90 text-white text-xs px-2.5 py-1 rounded-full shadow-lg border border-white flex items-center gap-1 animate-bounce">
                                <span>🌊</span> Banjir 40cm
                            </div>

                            {{-- Animated Marker 2 (Macet) --}}
                            <div class="absolute top-28 right-12 bg-purple-600/90 text-white text-xs px-2.5 py-1 rounded-full shadow-lg border border-white flex items-center gap-1">
                                <span>🚦</span> Macet Dago
                            </div>

                            {{-- Animated Marker 3 (Kos Radius) --}}
                            <div class="absolute bottom-10 left-24 bg-emerald-600/90 text-white text-xs px-2.5 py-1 rounded-full shadow-lg border border-white flex items-center gap-1">
                                <span>🏡</span> Kos Telkom U (Aman)
                            </div>

                            {{-- Proximity circle --}}
                            <div class="absolute w-44 h-44 rounded-full border border-purple-400/40 bg-purple-500/10 pointer-events-none animate-pulse"></div>
                            <div class="w-4 h-4 rounded-full bg-[#6C3DE6] border-2 border-white shadow-md"></div>
                        </div>

                        {{-- Mockup Footer Card --}}
                        <div class="bg-gray-900/90 rounded-xl p-3 border border-gray-800 flex items-center justify-between">
                            <div>
                                <p class="text-[11px] text-gray-400">Analisis Radius Kos</p>
                                <p class="text-xs font-bold text-white">Radius 1.5 km Bebas Banjir</p>
                            </div>
                            <span class="text-xs font-bold text-emerald-400 bg-emerald-950 px-2.5 py-1 rounded-lg border border-emerald-800">Skor: 9.4/10</span>
                        </div>
                    </div>
                </div>

                {{-- Floating Pill --}}
                <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl p-3.5 shadow-xl border border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-lg">
                        🛡️
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Laporan Aktif</p>
                        <p class="text-sm font-bold text-gray-800">{{ $stats['total_laporan'] ?? 6 }} Titik Terpetakan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 2. How It Works Section --}}
<section class="py-20 bg-gray-50/70 border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-[#6C3DE6] bg-purple-100/60 px-3 py-1 rounded-full">Alur Mudah</span>
            <h2 class="text-3xl font-black text-gray-900 mt-3">Bagaimana SIGAP Bekerja?</h2>
            <p class="text-sm text-gray-500 mt-2">Tiga langkah cerdas untuk memahami kondisi lingkungan dan memilih tempat tinggal terbaik di Bandung.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm relative group hover:shadow-md transition-all">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-[#6C3DE6] flex items-center justify-center text-2xl font-bold mb-4">
                    1
                </div>
                <h3 class="font-bold text-lg text-gray-900 mb-2">Pilih Kategori Isu</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Filter informasi geospasial berdasarkan 3 kategori utama: Titik Kemacetan, Kawasan Banjir & Genangan, atau Permukiman Padat/Kumuh.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm relative group hover:shadow-md transition-all">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-[#3B82F6] flex items-center justify-center text-2xl font-bold mb-4">
                    2
                </div>
                <h3 class="font-bold text-lg text-gray-900 mb-2">Jelajahi Peta / Cari Kos</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Ketik nama calon kos atau tentukan radius 1–5 km. Algoritma Haversine SIGAP menghitung jarak laporan risiko secara presisi.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm relative group hover:shadow-md transition-all">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl font-bold mb-4">
                    3
                </div>
                <h3 class="font-bold text-lg text-gray-900 mb-2">Pantau Verifikasi & Update</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Buka detail laporan yang telah divalidasi resmi oleh Dishub atau BPBD Kota Bandung lengkap dengan foto riil dan komentar warga sekitar.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- 3. Features & Benefits --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-[#3B82F6] bg-blue-100/60 px-3 py-1 rounded-full">Fitur Unggulan</span>
            <h2 class="text-3xl font-black text-gray-900 mt-3">Solusi Menyeluruh untuk Kota Berkelanjutan</h2>
            <p class="text-sm text-gray-500 mt-2">Didesain khusus untuk mahasiswa, warga, dan dinas pengambil kebijakan.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            {{-- Big Feature: Cari Kos Aman (Col 12/7) --}}
            <div class="md:col-span-7 bg-gradient-to-br from-indigo-900 via-[#6C3DE6] to-[#3B82F6] rounded-3xl p-8 text-white relative overflow-hidden flex flex-col justify-between shadow-xl">
                <div class="relative z-10">
                    <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold text-white">⭐ Fitur Andalan</span>
                    <h3 class="text-2xl sm:text-3xl font-black mt-4 mb-3">Cari Kos Aman Bebas Banjir</h3>
                    <p class="text-purple-100 text-sm leading-relaxed max-w-lg mb-6">
                        Jangan sampai salah memilih kos! Masukkan koordinat atau pilih kos rekomendasi, lalu lihat riwayat banjir dan kemacetan dalam radius 1 hingga 5 km sebelum bayar sewa tahunan.
                    </p>
                </div>
                <div class="relative z-10 flex items-center gap-4 pt-4 border-t border-white/10">
                    <a href="{{ route('cari-kos.index') }}" class="px-6 py-3 rounded-xl bg-white text-[#6C3DE6] font-bold text-sm hover:shadow-lg hover:scale-105 transition-all">
                        Coba Cari Kos Sekarang →
                    </a>
                    <span class="text-xs text-purple-200">Formula Haversine Presisi</span>
                </div>
            </div>

            {{-- Feature Card 2: Laporan Partisipatif --}}
            <div class="md:col-span-5 bg-purple-50/50 rounded-3xl p-8 border border-purple-100 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#6C3DE6] text-white flex items-center justify-center text-xl mb-4 shadow-md shadow-purple-300">
                        📢
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Laporan Warga Cepat</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Warga cukup foto, tandai pin koordinat otomatis di peta Bandung, pilih tingkat keparahan, dan kirimkan laporan hanya dalam 30 detik.
                    </p>
                </div>
                <div class="pt-6">
                    <a href="{{ route('register') }}" class="text-sm font-bold text-[#6C3DE6] hover:underline flex items-center gap-1">
                        Daftar Jadi Pelapor Warga →
                    </a>
                </div>
            </div>

            {{-- Feature Card 3: Validasi Resmi Dishub & BPBD --}}
            <div class="md:col-span-4 bg-gray-50 rounded-3xl p-6 border border-gray-100">
                <div class="text-2xl mb-3">🛡️</div>
                <h4 class="font-bold text-gray-900 mb-1">Verifikasi Anti-Hoax</h4>
                <p class="text-xs text-gray-500 leading-relaxed">Setiap laporan masuk melewati pemeriksaan administrator sebelum tampil di peta publik.</p>
            </div>

            {{-- Feature Card 4: Transparansi Status --}}
            <div class="md:col-span-4 bg-gray-50 rounded-3xl p-6 border border-gray-100">
                <div class="text-2xl mb-3">👁️</div>
                <h4 class="font-bold text-gray-900 mb-1">Peta Transparan</h4>
                <p class="text-xs text-gray-500 leading-relaxed">Laporan pending tetap dapat dipantau publik dengan visual pudar demi transparansi penanganan.</p>
            </div>

            {{-- Feature Card 5: Diskusi & Update Komentar --}}
            <div class="md:col-span-4 bg-gray-50 rounded-3xl p-6 border border-gray-100">
                <div class="text-2xl mb-3">💬</div>
                <h4 class="font-bold text-gray-900 mb-1">Update Real-time Warga</h4>
                <p class="text-xs text-gray-500 leading-relaxed">Warga sekitar bisa menambahkan update langsung seperti "air sudah surut" pada titik laporan.</p>
            </div>
        </div>
    </div>
</section>

{{-- 4. Public Quick Statistics --}}
<section class="py-20 bg-gradient-to-r from-gray-900 via-slate-900 to-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="p-4">
                <p class="text-4xl sm:text-5xl font-extrabold bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent">
                    {{ $stats['total_laporan'] ?? 0 }}
                </p>
                <p class="text-sm font-medium text-gray-400 mt-2">Total Laporan Masuk</p>
            </div>
            <div class="p-4">
                <p class="text-4xl sm:text-5xl font-extrabold bg-gradient-to-r from-blue-400 to-emerald-400 bg-clip-text text-transparent">
                    {{ $stats['terverifikasi'] ?? 0 }}
                </p>
                <p class="text-sm font-medium text-gray-400 mt-2">Laporan Terverifikasi</p>
            </div>
            <div class="p-4">
                <p class="text-4xl sm:text-5xl font-extrabold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent">
                    30
                </p>
                <p class="text-sm font-medium text-gray-400 mt-2">Kecamatan Bandung Tercover</p>
            </div>
            <div class="p-4">
                <p class="text-4xl sm:text-5xl font-extrabold bg-gradient-to-r from-amber-400 to-pink-400 bg-clip-text text-transparent">
                    {{ $stats['total_warga'] ?? 0 }}
                </p>
                <p class="text-sm font-medium text-gray-400 mt-2">Warga & Mahasiswa Aktif</p>
            </div>
        </div>
    </div>
</section>

{{-- 5. Student Testimonial --}}
<section class="py-20 bg-gray-50/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto bg-white rounded-3xl p-8 sm:p-10 border border-gray-100 shadow-sm text-center">
            <div class="flex justify-center gap-1 text-amber-400 text-lg mb-4">
                ★★★★★
            </div>
            <p class="text-gray-700 text-base sm:text-lg italic leading-relaxed mb-6">
                "Sebelum tahu SIGAP, saya hampir bayar kos di daerah yang ternyata langganan banjir saat musim hujan. Fitur Cari Kos Aman dengan radius 1 km benar-benar membantu mahasiswa perantau seperti saya mengambil keputusan dengan tenang."
            </p>
            <div class="flex items-center justify-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#6C3DE6] to-[#3B82F6] text-white font-bold text-sm flex items-center justify-center">
                    R
                </div>
                <div class="text-left">
                    <h5 class="font-bold text-gray-900 text-sm">Rian Pratama</h5>
                    <p class="text-xs text-gray-400">Mahasiswa Telkom University · Penghuni Sukapura</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 6. CTA Section --}}
<section class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-3xl p-8 sm:p-12 border border-purple-100">
            <h2 class="text-3xl font-black text-gray-900 mb-4">Siap Berkontribusi untuk Bandung yang Lebih Aman?</h2>
            <p class="text-sm sm:text-base text-gray-600 max-w-xl mx-auto mb-8">
                Laporkan kendala lingkungan di sekitar Anda atau cek keamanan calon hunian Anda sekarang.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}" class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white font-bold text-sm hover:shadow-lg transition-all">
                    Daftar Sekarang — Gratis
                </a>
                <a href="{{ route('peta.index') }}" class="px-7 py-3.5 rounded-2xl bg-white border border-gray-200 text-gray-800 font-bold text-sm hover:bg-gray-50 transition-all">
                    Buka Peta Interaktif
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
