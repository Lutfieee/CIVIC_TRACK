@extends('layouts.app')

@section('title', 'Laporan Cepat')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-cepat { height: 260px; border-radius: 16px; z-index: 10; }
    .category-chip.selected {
        border-color: #6C3DE6 !important;
        background-color: #f5f3ff !important;
        box-shadow: 0 4px 12px rgba(108, 61, 230, 0.15);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#F8F8FF] via-white to-[#EEF2FF] py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">

        {{-- Header Card --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-gradient-to-r from-amber-500/10 to-purple-500/10 border border-amber-300/40 text-amber-700 text-xs font-bold mb-3 shadow-xs">
                <span>⚡</span> Fitur Laporan Cepat 1 Menit
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kirim Laporan Cepat</h1>
            <p class="text-gray-500 text-sm mt-1.5">Foto masalah, tentukan titik lokasi, dan laporkan langsung ke dinas pemerintah terkait.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 text-sm px-5 py-4 rounded-2xl mb-6 shadow-sm">
                <div class="font-bold mb-1 flex items-center gap-2 text-red-700">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    Mohon lengkapi data laporan berikut:
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('warga.laporan.store-cepat') }}" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 sm:p-8 space-y-6">
            @csrf

            {{-- 1. Kategori Masalah (Pilih Cepat) --}}
            <div>
                <label class="block text-sm font-bold text-gray-800 mb-2">
                    1. Pilih Kategori Masalah <span class="text-red-500">*</span>
                </label>
                <input type="hidden" name="kategori_id" id="kategori_id" value="{{ old('kategori_id') }}" required>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    @foreach($kategoris as $k)
                        <button type="button"
                                onclick="pilihKategori({{ $k->id }}, '{{ addslashes($k->nama_kategori) }}', '{{ addslashes($k->dinas_terkait ?? 'Pemerintah Kabupaten Bandung') }}')"
                                id="btn-kat-{{ $k->id }}"
                                class="category-chip text-left p-3 rounded-2xl border-2 border-gray-100 hover:border-purple-300 transition-all flex flex-col justify-between group bg-white cursor-pointer">
                            <div class="text-2xl mb-1.5">{{ $k->ikon }}</div>
                            <div>
                                <div class="text-xs font-bold text-gray-800 group-hover:text-[#6C3DE6] line-clamp-1 leading-snug">{{ $k->nama_kategori }}</div>
                                <div class="text-[10px] text-gray-400 mt-0.5 line-clamp-1">🏛️ {{ Str::limit($k->dinas_terkait ?? 'Dinas Terkait', 22) }}</div>
                            </div>
                        </button>
                    @endforeach
                </div>

                <div id="info-dinas-banner" class="hidden mt-3 p-3 rounded-xl bg-purple-50 border border-purple-200 text-xs text-purple-900 flex items-center gap-2">
                    <span class="text-base">🏛️</span>
                    <span>Laporan ini akan diteruskan ke: <strong id="nama-dinas-terpilih" class="font-bold text-purple-950"></strong></span>
                </div>
            </div>

            {{-- 2. Foto Kejadian --}}
            <div>
                <label class="block text-sm font-bold text-gray-800 mb-2">
                    2. Ambil Foto Masalah / Bukti <span class="text-xs text-gray-400 font-normal">(Disarankan)</span>
                </label>
                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-4 text-center hover:border-[#6C3DE6] transition-colors relative bg-gray-50/50">
                    <input type="file" name="foto" id="foto-input" accept="image/*" capture="environment"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           onchange="previewFoto(event)">
                    <div id="foto-placeholder" class="py-3">
                        <div class="w-12 h-12 mx-auto rounded-full bg-purple-100 text-[#6C3DE6] flex items-center justify-center text-xl mb-2">📷</div>
                        <p class="text-xs font-semibold text-gray-700">Klik untuk ambil foto / upload gambar</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Format JPG, PNG, WEBP (Maksimal 5MB)</p>
                    </div>
                    <div id="foto-preview-wrapper" class="hidden relative">
                        <img id="foto-preview" src="#" alt="Preview Foto" class="max-h-48 mx-auto rounded-xl object-cover shadow-sm">
                        <p class="text-xs text-emerald-600 font-semibold mt-2">✓ Foto terpilih. Klik lagi untuk mengganti.</p>
                    </div>
                </div>
            </div>

            {{-- 3. Lokasi Kejadian (GPS Auto) --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-bold text-gray-800">
                        3. Lokasi Titik Masalah <span class="text-red-500">*</span>
                    </label>
                    <button type="button" onclick="deteksiLokasiSaya()"
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-semibold transition-all">
                        <span id="gps-spinner" class="hidden animate-spin text-xs">⏳</span>
                        <span>📍 Deteksi GPS Saya</span>
                    </button>
                </div>

                <div class="mb-2">
                    <select name="wilayah" id="wilayah-select"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs bg-white focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30">
                        <option value="">-- Pilih Kecamatan / Wilayah --</option>
                        @foreach($kecamatan as $kec)
                            <option value="{{ $kec }}">{{ $kec }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="map-cepat" class="border border-gray-200 shadow-xs mb-2"></div>
                <div class="flex items-center justify-between text-[11px] text-gray-400">
                    <span>* Geser pin merah untuk menandai titik presisi kejadian.</span>
                    <span id="coords-label" class="font-mono text-gray-600"></span>
                </div>

                <input type="hidden" name="latitude" id="lat-input" value="{{ old('latitude', $initLat) }}">
                <input type="hidden" name="longitude" id="lng-input" value="{{ old('longitude', $initLng) }}">
            </div>

            {{-- 4. Deskripsi Singkat --}}
            <div>
                <label for="deskripsi" class="block text-sm font-bold text-gray-800 mb-1.5">
                    4. Keterangan Singkat Kejadian <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="3" required
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6] text-sm"
                          placeholder="Contoh: Ada tumpukan sampah liar menumpuk di pinggir jalan dekat jembatan, baunya menyengat...">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- 5. Tingkat Keparahan --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Tingkat Keparahan Masalah</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="flex items-center justify-center gap-1.5 p-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 text-xs font-semibold text-gray-700 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:text-emerald-700">
                        <input type="radio" name="tingkat_keparahan" value="rendah" class="sr-only">
                        <span>🟢 Rendah</span>
                    </label>
                    <label class="flex items-center justify-center gap-1.5 p-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 text-xs font-semibold text-gray-700 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 has-[:checked]:text-amber-700">
                        <input type="radio" name="tingkat_keparahan" value="sedang" checked class="sr-only">
                        <span>🟡 Sedang</span>
                    </label>
                    <label class="flex items-center justify-center gap-1.5 p-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 text-xs font-semibold text-gray-700 has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 has-[:checked]:text-rose-700">
                        <input type="radio" name="tingkat_keparahan" value="tinggi" class="sr-only">
                        <span>🔴 Mendesak</span>
                    </label>
                </div>
            </div>

            {{-- Submit --}}
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-[#6C3DE6] via-indigo-600 to-[#3B82F6] text-white font-bold hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 text-base shadow-md shadow-[#6C3DE6]/25 flex items-center justify-center gap-2">
                    <span>🚀</span> Kirim Laporan Cepat Sekarang
                </button>
                <p class="text-center text-[11px] text-gray-400 mt-2.5">
                    Laporan akan otomatis diverifikasi dan diteruskan ke dinas terkait Kabupaten Bandung.
                </p>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let curLat = parseFloat("{{ $initLat }}") || -6.9175;
let curLng = parseFloat("{{ $initLng }}") || 107.6191;

const mapCepat = L.map('map-cepat').setView([curLat, curLng], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
}).addTo(mapCepat);

let marker = L.marker([curLat, curLng], { draggable: true }).addTo(mapCepat);

function updateCoords(lat, lng) {
    document.getElementById('lat-input').value = lat.toFixed(6);
    document.getElementById('lng-input').value = lng.toFixed(6);
    document.getElementById('coords-label').textContent = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
}
updateCoords(curLat, curLng);

marker.on('dragend', function (e) {
    const pos = marker.getLatLng();
    updateCoords(pos.lat, pos.lng);
});

mapCepat.on('click', function(e) {
    marker.setLatLng(e.latlng);
    updateCoords(e.latlng.lat, e.latlng.lng);
});

function pilihKategori(id, nama, dinas) {
    document.querySelectorAll('.category-chip').forEach(el => el.classList.remove('selected'));
    const btn = document.getElementById('btn-kat-' + id);
    if (btn) btn.classList.add('selected');
    document.getElementById('kategori_id').value = id;

    const banner = document.getElementById('info-dinas-banner');
    document.getElementById('nama-dinas-terpilih').textContent = dinas;
    banner.classList.remove('hidden');
}

function previewFoto(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            document.getElementById('foto-placeholder').classList.add('hidden');
            document.getElementById('foto-preview').src = evt.target.result;
            document.getElementById('foto-preview-wrapper').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function deteksiLokasiSaya() {
    const spinner = document.getElementById('gps-spinner');
    if (!navigator.geolocation) {
        alert('Perangkat Anda tidak mendukung geolokasi GPS.');
        return;
    }
    spinner.classList.remove('hidden');
    navigator.geolocation.getCurrentPosition(
        function(pos) {
            spinner.classList.add('hidden');
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            mapCepat.setView([lat, lng], 16);
            marker.setLatLng([lat, lng]);
            updateCoords(lat, lng);
        },
        function(err) {
            spinner.classList.add('hidden');
            alert('Tidak dapat mendeteksi lokasi GPS Anda. Silakan geser pin di peta secara manual.');
        },
        { enableHighAccuracy: true, timeout: 8000 }
    );
}

// Auto select first category if old value exists
document.addEventListener('DOMContentLoaded', function() {
    const oldId = "{{ old('kategori_id') }}";
    if (oldId) {
        const btn = document.getElementById('btn-kat-' + oldId);
        if (btn) btn.click();
    }
});
</script>
@endpush
