@extends('layouts.app')

@section('title', 'Informasi & Panduan Layanan')

@section('content')
<div class="py-12 bg-gradient-to-b from-gray-50 to-white min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary mb-3">
                📖 Panduan Layanan Sistem
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                Panduan Penggunaan Layanan CIVIC TRACK
            </h1>
            <p class="mt-3 text-base text-gray-600">
                Informasi komprehensif mengenai tata cara pelaporan masalah lingkungan dan fasilitas publik di wilayah kota dan desa, serta panduan pemantauan transportasi umum terpadu.
            </p>
        </div>

        <div class="space-y-12">
            {{-- Bagian 1: Alur Pelaporan Masalah --}}
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center text-xl font-bold">📢</div>
                    <h2 class="text-2xl font-bold text-gray-900">1. Alur Pelaporan Masalah Wilayah</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col">
                        <div class="w-8 h-8 rounded-full bg-primary text-white font-bold text-sm flex items-center justify-center mb-3">1</div>
                        <h3 class="font-bold text-sm text-gray-900 mb-1">Pendaftaran & Login</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">Warga mendaftar akun atau masuk ke sistem sebelum menyampaikan formulir laporan permasalahan.</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col">
                        <div class="w-8 h-8 rounded-full bg-primary text-white font-bold text-sm flex items-center justify-center mb-3">2</div>
                        <h3 class="font-bold text-sm text-gray-900 mb-1">Pengisian Laporan</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">Mengisi judul masalah, kategori, deskripsi detail, pin koordinat lokasi di peta, dan melampirkan foto bukti.</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col">
                        <div class="w-8 h-8 rounded-full bg-primary text-white font-bold text-sm flex items-center justify-center mb-3">3</div>
                        <h3 class="font-bold text-sm text-gray-900 mb-1">Verifikasi Administrator</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">Admin memeriksa validitas laporan di antrean verifikasi untuk disetujui, diproses, atau ditolak jika hoax.</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col">
                        <div class="w-8 h-8 rounded-full bg-primary text-white font-bold text-sm flex items-center justify-center mb-3">4</div>
                        <h3 class="font-bold text-sm text-gray-900 mb-1">Tindak Lanjut & Selesai</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">Instansi terkait melakukan tindakan di lapangan, status diperbarui menjadi selesai, dan warga menerima notifikasi.</p>
                    </div>
                </div>
            </div>

            {{-- Bagian 2: Kategori Permasalahan --}}
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-bold">🏷️</div>
                    <h2 class="text-2xl font-bold text-gray-900">2. Kategori Permasalahan yang Dilayani</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($kategoris as $k)
                        <div class="p-4 rounded-2xl border border-gray-100 bg-gray-50/50 flex items-center gap-3">
                            <span class="text-2xl p-2 rounded-xl bg-white shadow-xs border border-gray-100">{{ $k->ikon }}</span>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">{{ $k->nama_kategori }}</h3>
                                <span class="text-xs text-gray-500">Isu Perkotaan & Pedesaan</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Bagian 3: Panduan Pemantauan Transportasi --}}
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-bold">🚌</div>
                    <h2 class="text-2xl font-bold text-gray-900">3. Panduan Pemantauan Transportasi Umum</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm text-gray-600">
                    <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-2 flex items-center gap-2">
                            <span>🔍</span> Cari Rute Asal - Tujuan
                        </h3>
                        <p class="text-xs leading-relaxed">
                            Gunakan kotak pencarian pada menu Transportasi untuk mencari rute perjalanan berdasarkan titik awal, tujuan, nama halte, atau jenis moda kendaraan.
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-2 flex items-center gap-2">
                            <span>🚦</span> Status Kondisi Operasional
                        </h3>
                        <p class="text-xs leading-relaxed">
                            Setiap trayek dilengkapi label status operasional: <span class="text-emerald-600 font-semibold">Normal</span>, <span class="text-amber-600 font-semibold">Terkendala</span>, atau <span class="text-blue-600 font-semibold">Perbaikan/Pengalihan</span>.
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-2 flex items-center gap-2">
                            <span>🚏</span> Cek Halte & Jadwal
                        </h3>
                        <p class="text-xs leading-relaxed">
                            Klik tombol "Lihat Titik Pemberhentian & Halte" untuk melihat urutan halte yang dilintasi armada beserta estimasi jadwal keberangkatan.
                        </p>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ route('transportasi.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gradient-to-r from-primary to-secondary text-white text-sm font-semibold shadow hover:shadow-lg transition-all">
                        <span>Akses Dasbor Transportasi Sekarang</span>
                        <span>→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
