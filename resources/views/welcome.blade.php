@extends('layouts.app')

@section('title', 'Dasbor Pelaporan Masalah Kota & Desa serta Pemantauan Transportasi Umum')

@section('content')
{{-- 1. Hero Section --}}
<section class="relative overflow-hidden pt-12 pb-20 lg:pt-16 lg:pb-28 bg-gradient-to-b from-indigo-50/70 via-white to-white">
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[900px] h-[400px] bg-gradient-to-r from-purple-300/25 via-blue-200/25 to-indigo-200/20 blur-3xl rounded-full"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            {{-- Left Content --}}
            <div class="lg:col-span-7 text-center lg:text-left">


                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight leading-[1.2] mb-6">
                    Dasbor Pelaporan Masalah Wilayah & <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Pemantauan Transportasi Umum</span>
                </h1>

                <p class="text-base sm:text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto lg:mx-0 mb-8 font-normal">
                    Sistem informasi terintegrasi yang menghubungkan Warga dalam menyampaikan laporan fasilitas & infrastruktur lingkungan, dengan kemudahan pemantauan rute dan jadwal transportasi umum secara terpusat.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <a href="{{ route('peta.index') }}"
                       class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-gradient-to-r from-primary to-secondary text-white font-bold text-sm shadow-lg shadow-purple-500/25 hover:shadow-xl hover:shadow-purple-500/35 hover:-translate-y-0.5 transition-all text-center flex items-center justify-center gap-2">
                        <span>🗺️</span> Peta Persebaran Masalah
                    </a>
                    <a href="{{ route('transportasi.index') }}"
                       class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-white border-2 border-indigo-200 text-indigo-700 font-bold text-sm hover:border-primary hover:bg-indigo-50/50 hover:-translate-y-0.5 transition-all text-center flex items-center justify-center gap-2 shadow-sm">
                        <span>🚌</span> Cek Rute Transportasi
                    </a>
                    @guest
                        <a href="{{ route('register') }}"
                           class="w-full sm:w-auto px-5 py-3.5 rounded-2xl bg-gray-100 text-gray-700 font-bold text-sm hover:bg-gray-200 transition-all text-center">
                            Daftar Warga
                        </a>
                    @else
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('warga.laporan.create') }}"
                           class="w-full sm:w-auto px-5 py-3.5 rounded-2xl bg-gray-900 text-white font-bold text-sm hover:bg-black transition-all text-center">
                            {{ auth()->user()->isAdmin() ? 'Ke Panel Admin' : '➕ Buat Laporan' }}
                        </a>
                    @endguest
                </div>

                {{-- Live Counter Badges (FR-5) --}}
                <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-3 text-left">
                    <div class="p-3 bg-white rounded-xl border border-gray-100 shadow-xs">
                        <div class="text-xl font-black text-gray-900">{{ $stats['total_laporan'] }}</div>
                        <div class="text-[11px] text-gray-500 font-medium">Laporan Masuk</div>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100 shadow-xs">
                        <div class="text-xl font-black text-emerald-600">{{ $stats['selesai'] ?? 0 }}</div>
                        <div class="text-[11px] text-gray-500 font-medium">Laporan Tuntas</div>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100 shadow-xs">
                        <div class="text-xl font-black text-indigo-600">{{ $stats['total_moda'] ?? 5 }}</div>
                        <div class="text-[11px] text-gray-500 font-medium">Rute Transportasi</div>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100 shadow-xs">
                        <div class="text-xl font-black text-blue-600">{{ $stats['moda_normal'] ?? 4 }}</div>
                        <div class="text-[11px] text-gray-500 font-medium">Armada Normal</div>
                    </div>
                </div>
            </div>

            {{-- Right Mockup / Live Card --}}
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-md bg-gradient-to-b from-gray-900 to-gray-800 p-4 rounded-3xl shadow-2xl border border-gray-700">
                    <div class="bg-gray-950 rounded-2xl p-4 overflow-hidden text-white relative">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-800 text-xs">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                            </div>
                            <span class="text-gray-400 font-mono text-[11px]">dasbor-civictack.live</span>
                            <span class="text-emerald-400 font-bold text-[10px] bg-emerald-950 px-2 py-0.5 rounded-full border border-emerald-800">TERHUBUNG</span>
                        </div>

                        {{-- Simulated Live Transport & Problem Status --}}
                        <div class="py-4 space-y-3">
                            <div class="bg-gray-900/90 rounded-xl p-3 border border-gray-800 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-xl">🚌</span>
                                    <div>
                                        <p class="text-xs font-bold text-white">Trans Metro Bandung (K-1)</p>
                                        <p class="text-[11px] text-gray-400">Cicaheum ⇄ Leuwipanjang</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">NORMAL</span>
                            </div>

                            <div class="bg-gray-900/90 rounded-xl p-3 border border-gray-800 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-xl">🌊</span>
                                    <div>
                                        <p class="text-xs font-bold text-white">Banjir Dayeuhkolot / Bojongsoang</p>
                                        <p class="text-[11px] text-gray-400">Pompa SDA Aktif • Surut</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">SELESAI</span>
                            </div>

                            <div class="bg-gray-900/90 rounded-xl p-3 border border-gray-800 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-xl">🕳️</span>
                                    <div>
                                        <p class="text-xs font-bold text-white">Jalan Rusak Kiaracondong</p>
                                        <p class="text-[11px] text-gray-400">Unit Reaksi Cepat Menambal</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-purple-400 bg-purple-950 px-2 py-0.5 rounded border border-purple-800">DIPROSES</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-800 flex items-center justify-between text-[11px] text-gray-400">
                            <span>Sistem Transparansi Pelayanan Publik</span>
                            <span class="text-indigo-400 font-semibold">CIVIC TRACK</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 2. Dua Pilar Utama Layanan --}}
<section class="py-16 bg-white border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-primary bg-purple-100/60 px-3 py-1 rounded-full">Dua Komponen Utama</span>
            <h2 class="text-3xl font-black text-gray-900 mt-3">Integrasi Dua Solusi dalam Satu Dasbor</h2>
            <p class="text-sm text-gray-500 mt-2">Menjawab kebutuhan pelaporan masalah warga sekaligus mempermudah mobilitas masyarakat dengan transportasi umum.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Pilar 1: Pelaporan Masalah Wilayah --}}
            <div class="bg-gradient-to-br from-purple-50/70 to-indigo-50/40 rounded-3xl p-8 border border-purple-100 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-primary text-white flex items-center justify-center text-2xl mb-4 shadow-md shadow-purple-200">
                        📢
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">1. Dasbor Pelaporan Masalah Kota & Desa</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-6">
                        Sarana digital bagi Warga untuk menyampaikan permasalahan lingkungan seperti kerusakan jalan, lampu penerangan yang padam, penumpukan sampah liar, serta genangan banjir secara terstruktur dengan dokumentasi foto dan titik koordinat akurat.
                    </p>
                    <ul class="space-y-2 text-xs font-medium text-gray-700 mb-6">
                        <li class="flex items-center gap-2">✓ Verifikasi dan transparansi tindak lanjut dari admin</li>
                        <li class="flex items-center gap-2">✓ Pemantauan status laporan (Diterima, Diproses, Selesai)</li>
                        <li class="flex items-center gap-2">✓ Peta persebaran spasial interaktif Leaflet</li>
                    </ul>
                </div>
                <div class="pt-4 border-t border-purple-200/50 flex items-center gap-3">
                    <a href="{{ route('peta.index') }}" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-dark text-white text-xs font-bold transition-all shadow-sm">
                        Buka Peta Masalah →
                    </a>
                    <a href="{{ route('informasi-layanan.index') }}" class="px-4 py-2.5 rounded-xl bg-white text-gray-700 hover:bg-gray-100 text-xs font-semibold transition-all">
                        Panduan Melapor
                    </a>
                </div>
            </div>

            {{-- Pilar 2: Pemantauan Transportasi Umum --}}
            <div class="bg-gradient-to-br from-blue-50/70 to-cyan-50/40 rounded-3xl p-8 border border-blue-100 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-secondary text-white flex items-center justify-center text-2xl mb-4 shadow-md shadow-blue-200">
                        🚌
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">2. Dasbor Pemantauan Transportasi Umum</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-6">
                        Menyediakan informasi transportasi secara terpusat untuk membantu masyarakat merencanakan perjalanan dengan efisien. Menampilkan jenis armada, rute asal-tujuan, jadwal operasional, urutan halte/titik pemberhentian, dan status kondisi operasional real-time.
                    </p>
                    <ul class="space-y-2 text-xs font-medium text-gray-700 mb-6">
                        <li class="flex items-center gap-2">✓ Pencarian rute berdasarkan titik keberangkatan & tujuan</li>
                        <li class="flex items-center gap-2">✓ Informasi tarif resmi dan jadwal keberangkatan</li>
                        <li class="flex items-center gap-2">✓ Pembaruan kondisi operasional terkini (Normal/Terkendala)</li>
                    </ul>
                </div>
                <div class="pt-4 border-t border-blue-200/50 flex items-center gap-3">
                    <a href="{{ route('transportasi.index') }}" class="px-5 py-2.5 rounded-xl bg-secondary hover:bg-secondary-dark text-white text-xs font-bold transition-all shadow-sm">
                        Pantau Transportasi →
                    </a>
                    <a href="{{ route('informasi-layanan.index') }}" class="px-4 py-2.5 rounded-xl bg-white text-gray-700 hover:bg-gray-100 text-xs font-semibold transition-all">
                        Panduan Layanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 3. Cuplikan Laporan Terkini & Rute Transportasi --}}
<section class="py-16 bg-gray-50/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Laporan Terkini --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Perkembangan Laporan Terbaru</h2>
                <p class="text-xs text-gray-500">Laporan warga yang telah diverifikasi dan sedang ditindaklanjuti.</p>
            </div>
            <a href="{{ route('peta.index') }}" class="text-xs font-bold text-primary hover:underline">
                Lihat Seluruh Peta →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            @forelse($laporanTerbaru as $lap)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-xs hover:shadow-md transition-shadow p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                {{ $lap->kategori->ikon }} {{ $lap->kategori->nama_kategori }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold
                                {{ $lap->status == 'diverifikasi' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $lap->status == 'diproses' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $lap->status == 'selesai' ? 'bg-emerald-100 text-emerald-800' : '' }}">
                                {{ $lap->status_label }}
                            </span>
                        </div>
                        <h3 class="font-bold text-gray-900 text-sm mb-1 line-clamp-1">{{ $lap->judul ?? $lap->kategori->nama_kategori }}</h3>
                        <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed mb-3">{{ $lap->deskripsi }}</p>
                    </div>
                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-400">
                        <span>📍 {{ $lap->wilayah }}</span>
                        <a href="{{ route('peta.show', $lap->id) }}" class="text-primary font-semibold hover:underline">Detail →</a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8 text-gray-400 text-sm">Belum ada laporan aktif.</div>
            @endforelse
        </div>

        {{-- Cuplikan Rute Transportasi --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Rute Transportasi Umum Terpopuler</h2>
                <p class="text-xs text-gray-500">Informasi operasional moda transportasi kota dan antar wilayah.</p>
            </div>
            <a href="{{ route('transportasi.index') }}" class="text-xs font-bold text-secondary hover:underline">
                Lihat Semua Moda →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($transportasiUtama as $tr)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-xs p-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">{{ $tr->tipe }}</span>
                            <span class="text-[10px] font-bold text-emerald-600">{{ ucfirst($tr->status_operasional) }}</span>
                        </div>
                        <h4 class="font-bold text-gray-900 text-sm mb-1">{{ $tr->nama_moda }}</h4>
                        <p class="text-xs text-gray-500 line-clamp-1 mb-2">{{ $tr->rute_asal }} ⇄ {{ $tr->rute_tujuan }}</p>
                    </div>
                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                        <span class="font-semibold text-emerald-600">{{ $tr->tarif }}</span>
                        <a href="{{ route('transportasi.show', $tr->id) }}" class="text-indigo-600 hover:underline font-semibold">Halte →</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
