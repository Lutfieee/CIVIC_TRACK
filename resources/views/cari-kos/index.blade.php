@extends('layouts.app')

@section('title', 'Cari Kos Aman — Analisis Riwayat Risiko')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #cari-map-wrapper {
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #cari-map {
        height: 540px;
        border-radius: 18px;
        cursor: crosshair !important;
        transition: height 0.3s ease;
    }
    #cari-map.tall-map {
        height: 720px !important;
    }
    #cari-map-wrapper.is-fullscreen {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 9999 !important;
        border-radius: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        background: #0f172a;
    }
    #cari-map-wrapper.is-fullscreen #cari-map {
        height: 100vh !important;
        border-radius: 0 !important;
    }
    #hasil-scroll {
        max-height: 540px;
        overflow-y: auto;
    }
    .custom-kos-pin {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pin-pulse {
        position: absolute;
        width: 44px;
        height: 44px;
        background: rgba(108, 61, 230, 0.45);
        border-radius: 50%;
        animation: pin-pulse-anim 2s infinite cubic-bezier(0.25, 1, 0.5, 1);
        pointer-events: none;
    }
    @keyframes pin-pulse-anim {
        0% { transform: scale(0.6); opacity: 1; }
        100% { transform: scale(2.2); opacity: 0; }
    }
</style>
@endpush

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="text-center max-w-3xl mx-auto mb-10">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-100 text-[#6C3DE6] text-xs font-semibold mb-3">
            <span>⭐</span> Fitur Andalan Mahasiswa & Pencari Kos
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">Cari Kos Aman Bebas Risiko</h1>
        <p class="text-gray-500 text-sm sm:text-base mt-2">
            Pilih lokasi calon kos langsung di peta Bandung. Sistem menghitung radius riwayat banjir, titik macet, dan kondisi lingkungan sekitar.
        </p>
    </div>

    {{-- Search Form Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-4">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Titik Acuan Cepat (Landmark)</label>
                <select id="select-preset" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30">
                    <option value="">-- Pilih Landmark / Kos Acuan --</option>
                    @foreach($lokasiRekomendasi as $lr)
                        <option value="{{ $lr->latitude }},{{ $lr->longitude }}">{{ $lr->nama }} ({{ $lr->wilayah }})</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Latitude</label>
                <input type="number" id="lat-input" step="any" placeholder="-6.9740" value="{{ $initialLat ?? -6.974026 }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Longitude</label>
                <input type="number" id="lng-input" step="any" placeholder="107.6303" value="{{ $initialLng ?? 107.630328 }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Radius Pantau</label>
                <select id="radius-input" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 font-medium">
                    <option value="0.5">0.5 km (Sangat Dekat / Jalan Kaki)</option>
                    <option value="1" selected>1 km (Lingkungan Sekitar Kos)</option>
                    <option value="2">2 km (Standar Kos Mahasiswa)</option>
                    <option value="3">3 km (Area Kawasan Kampus)</option>
                    <option value="5">5 km (Satu Kecamatan)</option>
                </select>
            </div>

            <div class="md:col-span-2 flex gap-2">
                <button type="button" id="btn-lokasiku" title="Gunakan GPS Lokasi Saya Saat Ini"
                        class="p-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors flex items-center justify-center">
                    📍
                </button>
                <button type="button" id="btn-cari"
                        class="flex-1 py-2.5 px-4 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white font-bold text-sm shadow-md shadow-indigo-200 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    Analisis
                </button>
            </div>
        </div>

        {{-- Quick Street / Area Search & Helper Note --}}
        <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="relative flex-1 w-full max-w-lg">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                <input type="text" id="input-cari-jalan" placeholder="Cari nama jalan / kawasan (contoh: Dago, Sukabirus, Dipatiukur)..."
                       class="w-full pl-9 pr-20 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30">
                <button type="button" id="btn-geosearch" class="absolute right-1 top-1/2 -translate-y-1/2 px-3 py-1 rounded-lg bg-[#6C3DE6] text-white text-xs font-semibold hover:bg-[#5b32c4] transition-colors">
                    Cari
                </button>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-500 flex-shrink-0">
                <span>💡 Interaksi Peta:</span>
                <span class="bg-purple-50 text-[#6C3DE6] px-2.5 py-1 rounded-lg font-semibold">Klik peta langsung</span>
                <span>atau</span>
                <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-lg font-semibold">Geser (drag) pin 🏠</span>
            </div>
        </div>
    </div>

    {{-- Map + Results Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Map Column --}}
        <div class="lg:col-span-7" id="map-column">
            <div id="cari-map-wrapper" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-3 overflow-hidden">
                
                {{-- Map Toolbar --}}
                <div class="flex items-center justify-between px-2 pb-3 mb-1 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                        <span class="text-xs font-bold text-gray-700">Peta Calon Kos</span>
                        <span class="hidden sm:inline-block text-[11px] bg-purple-100 text-[#6C3DE6] px-2 py-0.5 rounded-full font-semibold">
                            Bisa Diklik & Digeser (Drag)
                        </span>
                    </div>
                    
                    <div class="flex items-center gap-1.5">
                        {{-- Tombol Fokus Target --}}
                        <button type="button" id="btn-recenter" title="Pusatkan Peta ke Titik Calon Kos"
                                class="px-2.5 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center gap-1 transition-colors">
                            🎯 <span class="hidden sm:inline">Pusatkan</span>
                        </button>

                        {{-- Tombol Toggle Tinggi Peta --}}
                        <button type="button" id="btn-toggle-height" title="Ubah Ukuran Tinggi Peta (540px / 720px)"
                                class="px-2.5 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center gap-1 transition-colors">
                            <span id="label-height-icon">↕️</span> <span class="hidden sm:inline" id="label-height-text">Tinggi Lega</span>
                        </button>

                        {{-- Tombol Layar Penuh (Fullscreen) --}}
                        <button type="button" id="btn-fullscreen" title="Perbesar Peta Layar Penuh (Fullscreen)"
                                class="px-3 py-1.5 text-xs font-bold text-white bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] hover:shadow-md rounded-lg flex items-center gap-1 transition-all">
                            ⛶ <span id="label-fullscreen">Perbesar Peta</span>
                        </button>
                    </div>
                </div>

                {{-- Floating Topbar for Fullscreen Mode --}}
                <div id="fullscreen-topbar" class="hidden absolute top-4 left-4 right-4 z-[99999] bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/50 px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-[#6C3DE6] text-white flex items-center justify-center font-bold text-sm">
                            🏠
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-800">Mode Peta Layar Penuh — Cari Kos Aman</h3>
                            <p class="text-[11px] text-gray-500">Klik di mana saja pada peta atau geser pin 🏠 untuk memindahkan target kos</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" id="btn-close-fullscreen" class="px-3 py-1.5 rounded-xl bg-red-50 text-red-600 font-bold text-xs hover:bg-red-100 transition-colors flex items-center gap-1">
                            ✕ Perkecil Peta <kbd class="text-[10px] bg-white px-1.5 py-0.5 rounded border border-red-200">Esc</kbd>
                        </button>
                    </div>
                </div>

                {{-- The Leaflet Map --}}
                <div id="cari-map"></div>

                {{-- Help Tooltip Footer on Map --}}
                <div class="mt-2.5 px-2 flex items-center justify-between text-[11px] text-gray-400">
                    <span>🖱️ <strong>Klik peta</strong> atau <strong>geser pin 🏠</strong> untuk memilih titik baru</span>
                    <span id="target-coords-label" class="font-mono text-gray-500"></span>
                </div>
            </div>
        </div>

        {{-- Results & Analysis --}}
        <div class="lg:col-span-5 space-y-4">
            {{-- Summary Card --}}
            <div id="card-summary" class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Hasil Analisis Keamanan</span>
                    <span id="badge-status" class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Aman</span>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center pt-2 border-t border-gray-50">
                    <div class="bg-blue-50/70 p-2.5 rounded-xl">
                        <p class="text-xl font-black text-blue-600" id="stat-banjir">0</p>
                        <p class="text-[11px] text-gray-500 font-medium mt-0.5">🌊 Riwayat Banjir</p>
                    </div>
                    <div class="bg-purple-50/70 p-2.5 rounded-xl">
                        <p class="text-xl font-black text-purple-600" id="stat-macet">0</p>
                        <p class="text-[11px] text-gray-500 font-medium mt-0.5">🚦 Titik Macet</p>
                    </div>
                    <div class="bg-emerald-50/70 p-2.5 rounded-xl">
                        <p class="text-xl font-black text-emerald-600" id="stat-kumuh">0</p>
                        <p class="text-[11px] text-gray-500 font-medium mt-0.5">🏘️ Kawasan Padat</p>
                    </div>
                </div>
            </div>

            {{-- Detail List Card --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                <h3 class="font-bold text-gray-800 text-base mb-4 flex items-center justify-between" id="hasil-title">
                    <span>Riwayat Risiko di Sekitar</span>
                    <span class="text-xs font-normal text-gray-400" id="hasil-count">-</span>
                </h3>

                <div id="hasil-scroll" class="space-y-3">
                    <div class="text-center py-12 text-gray-400">
                        <span class="text-4xl block mb-2">🎯</span>
                        <p class="text-sm font-medium">Klik peta atau tombol "Analisis"</p>
                        <p class="text-xs text-gray-400 mt-1">Sistem akan memindai riwayat laporan terverifikasi terdekat.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const defaultLat = {{ $initialLat ?? -6.974026 }};
const defaultLng = {{ $initialLng ?? 107.630328 }};

const map = L.map('cari-map').setView([defaultLat, defaultLng], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

let targetMarker = null;
let radiusCircle = null;
const riskLayer = L.layerGroup().addTo(map);
const kosLayer = L.layerGroup().addTo(map);

// Set & update target marker (draggable)
function setTarget(lat, lng) {
    document.getElementById('lat-input').value = Number(lat).toFixed(6);
    document.getElementById('lng-input').value = Number(lng).toFixed(6);
    document.getElementById('target-coords-label').textContent = `${Number(lat).toFixed(5)}, ${Number(lng).toFixed(5)}`;

    if (targetMarker) {
        targetMarker.setLatLng([lat, lng]);
    } else {
        targetMarker = L.marker([lat, lng], {
            title: 'Titik Calon Kos (Klik peta atau geser pin ini!)',
            draggable: true,
            icon: L.divIcon({
                html: `
                    <div class="custom-kos-pin relative">
                        <div class="pin-pulse"></div>
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-[#6C3DE6] to-[#3B82F6] text-white flex items-center justify-center text-lg shadow-2xl border-2 border-white cursor-grab active:cursor-grabbing hover:scale-110 transition-transform">
                            🏠
                        </div>
                    </div>`,
                className: 'custom-kos-pin-wrapper',
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            })
        }).addTo(map);

        targetMarker.bindTooltip("🏠 <strong>Titik Calon Kos</strong><br><span style='font-size:11px;color:#666'>Klik peta atau geser pin ini untuk pindah</span>", {
            permanent: false,
            direction: 'top',
            offset: [0, -22]
        });

        // Event saat pin selesai digeser (dragend)
        targetMarker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            setTarget(pos.lat, pos.lng);
            executeSearch();
        });
    }
}

// Preset select change
document.getElementById('select-preset').addEventListener('change', function() {
    if (this.value) {
        const [lat, lng] = this.value.split(',');
        setTarget(lat, lng);
        map.setView([lat, lng], 16);
        executeSearch();
    }
});

// Map click -> langsung pilih titik target kos baru
map.on('click', e => {
    setTarget(e.latlng.lat, e.latlng.lng);
    executeSearch();
});

// GPS click
document.getElementById('btn-lokasiku').addEventListener('click', () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            const lat = pos.coords.latitude, lng = pos.coords.longitude;
            setTarget(lat, lng);
            map.setView([lat, lng], 16);
            executeSearch();
        }, () => alert('Gagal mendeteksi lokasi browser.'));
    }
});

// Search button
document.getElementById('btn-cari').addEventListener('click', executeSearch);

// Radius change auto search
document.getElementById('radius-input').addEventListener('change', executeSearch);

// Recenter Button
document.getElementById('btn-recenter').addEventListener('click', () => {
    if (targetMarker) {
        map.setView(targetMarker.getLatLng(), 16);
    }
});

// Toggle Height Button (Normal vs Lega)
const mapEl = document.getElementById('cari-map');
const btnToggleHeight = document.getElementById('btn-toggle-height');
const labelHeightText = document.getElementById('label-height-text');
const labelHeightIcon = document.getElementById('label-height-icon');

btnToggleHeight.addEventListener('click', () => {
    const isTall = mapEl.classList.toggle('tall-map');
    if (isTall) {
        labelHeightText.textContent = 'Normal (540px)';
        labelHeightIcon.textContent = '🔽';
    } else {
        labelHeightText.textContent = 'Tinggi Lega (720px)';
        labelHeightIcon.textContent = '↕️';
    }
    setTimeout(() => {
        map.invalidateSize();
    }, 300);
});

// Fullscreen Toggle
const wrapper = document.getElementById('cari-map-wrapper');
const btnFullscreen = document.getElementById('btn-fullscreen');
const btnCloseFullscreen = document.getElementById('btn-close-fullscreen');
const fullscreenTopBar = document.getElementById('fullscreen-topbar');

function toggleFullscreen() {
    const isFs = wrapper.classList.toggle('is-fullscreen');
    if (isFs) {
        fullscreenTopBar.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('label-fullscreen').textContent = 'Perkecil';
    } else {
        fullscreenTopBar.classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('label-fullscreen').textContent = 'Perbesar Peta';
    }
    setTimeout(() => {
        map.invalidateSize();
        if (targetMarker) map.panTo(targetMarker.getLatLng());
    }, 250);
}

btnFullscreen.addEventListener('click', toggleFullscreen);
btnCloseFullscreen.addEventListener('click', toggleFullscreen);

window.addEventListener('keydown', e => {
    if (e.key === 'Escape' && wrapper.classList.contains('is-fullscreen')) {
        toggleFullscreen();
    }
});

// Geocoding Quick Search (Pencarian Jalan / Kampus di Bandung)
const inputCariJalan = document.getElementById('input-cari-jalan');
const btnGeosearch = document.getElementById('btn-geosearch');

function doGeosearch() {
    const query = inputCariJalan.value.trim();
    if (!query) return;

    btnGeosearch.textContent = '...';
    btnGeosearch.disabled = true;

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ', Bandung')}&limit=1`)
        .then(r => r.json())
        .then(results => {
            btnGeosearch.textContent = 'Cari';
            btnGeosearch.disabled = false;
            if (results && results.length > 0) {
                const lat = parseFloat(results[0].lat);
                const lng = parseFloat(results[0].lon);
                setTarget(lat, lng);
                map.setView([lat, lng], 16);
                executeSearch();
            } else {
                alert('Lokasi "' + query + '" tidak ditemukan di Bandung. Anda bisa langsung klik di peta.');
            }
        })
        .catch(() => {
            btnGeosearch.textContent = 'Cari';
            btnGeosearch.disabled = false;
            alert('Layanan pencarian nama jalan sedang sibuk. Silakan klik langsung pada titik peta.');
        });
}

btnGeosearch.addEventListener('click', doGeosearch);
inputCariJalan.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
        e.preventDefault();
        doGeosearch();
    }
});

// Execute Search Function
function executeSearch() {
    const lat = document.getElementById('lat-input').value;
    const lng = document.getElementById('lng-input').value;
    const radius = document.getElementById('radius-input').value;

    if (!lat || !lng) {
        alert('Tentukan koordinat terlebih dahulu.');
        return;
    }

    // Update Circle
    if (radiusCircle) map.removeLayer(radiusCircle);
    radiusCircle = L.circle([lat, lng], {
        radius: radius * 1000,
        color: '#6C3DE6',
        fillColor: '#6C3DE6',
        fillOpacity: 0.09,
        weight: 2,
        dashArray: '5, 5'
    }).addTo(map);

    // Zoom lebih dekat dan fokus ke titik kos
    const targetZoom = radius <= 0.5 ? 17 : (radius <= 1 ? 16 : (radius <= 2 ? 15 : 14));
    map.setView([lat, lng], targetZoom);

    const hasilList = document.getElementById('hasil-scroll');
    hasilList.innerHTML = '<div class="text-center py-8 text-sm text-gray-400">Sedang memindai risiko dalam radius ' + radius + ' km...</div>';

    fetch(`{{ route('cari-kos.search') }}?latitude=${lat}&longitude=${lng}&radius=${radius}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(res => {
        riskLayer.clearLayers();
        kosLayer.clearLayers();

        // Update Summary Card
        const cardSummary = document.getElementById('card-summary');
        cardSummary.classList.remove('hidden');

        const badgeStatus = document.getElementById('badge-status');
        badgeStatus.textContent = res.ringkasan.status_aman;
        if (res.ringkasan.status_warna === 'red') {
            badgeStatus.className = 'px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700';
        } else if (res.ringkasan.status_warna === 'yellow') {
            badgeStatus.className = 'px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700';
        } else {
            badgeStatus.className = 'px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700';
        }

        document.getElementById('stat-banjir').textContent = res.ringkasan.banjir;
        document.getElementById('stat-macet').textContent = res.ringkasan.macet;
        document.getElementById('stat-kumuh').textContent = res.ringkasan.permukiman;
        document.getElementById('hasil-count').textContent = `${res.laporans.length} isu terdeteksi`;

        if (res.laporans.length === 0) {
            hasilList.innerHTML = `
                <div class="text-center py-10 bg-emerald-50/50 rounded-2xl border border-emerald-100 p-6">
                    <span class="text-4xl block mb-2">🎉</span>
                    <h4 class="font-bold text-emerald-800 text-sm">Lokasi Bersih & Bebas Risiko!</h4>
                    <p class="text-xs text-emerald-600 mt-1">Tidak ditemukan riwayat banjir atau kemacetan dalam radius ${radius} km.</p>
                </div>`;
            return;
        }

        hasilList.innerHTML = '';
        res.laporans.forEach(l => {
            const item = document.createElement('div');
            item.className = 'p-3.5 rounded-2xl border border-gray-100 hover:border-purple-200 hover:bg-purple-50/30 transition-all cursor-pointer';
            item.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0" style="background: ${l.warna}18; color: ${l.warna}">
                        ${l.ikon}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-xs text-gray-800">${l.kategori}</h4>
                            <span class="text-[11px] font-bold text-[#6C3DE6] bg-purple-50 px-2 py-0.5 rounded-md">${l.jarak} km</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">${l.deskripsi}</p>
                        <div class="flex items-center gap-2 mt-2 text-[11px] text-gray-400">
                            <span>📍 ${l.wilayah}</span>
                            <span>•</span>
                            <span class="font-semibold ${l.tingkat_keparahan === 'tinggi' ? 'text-red-500' : 'text-yellow-600'}">Keparahan: ${l.tingkat_keparahan}</span>
                        </div>
                    </div>
                </div>`;

            item.addEventListener('click', () => {
                map.setView([l.latitude, l.longitude], 17);
            });
            hasilList.appendChild(item);

            // Add marker to map
            L.circleMarker([l.latitude, l.longitude], {
                radius: 9,
                fillColor: l.warna,
                color: '#ffffff',
                weight: 2,
                fillOpacity: 0.95
            }).bindPopup(`
                <div class="font-sans text-xs">
                    <strong>${l.ikon} ${l.kategori}</strong>
                    <p class="mt-1">${l.deskripsi}</p>
                    <p class="text-gray-400 mt-1">Jarak: ${l.jarak} km | Wilayah: ${l.wilayah}</p>
                </div>
            `).addTo(riskLayer);
        });
    });
}

// Inisialisasi titik target pertama
setTarget(defaultLat, defaultLng);
executeSearch();
</script>
@endpush
