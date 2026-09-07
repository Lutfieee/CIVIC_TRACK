@extends('layouts.app')

@section('title', 'Peta Kejadian')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: calc(100vh - 64px - 80px); min-height: 500px; }
    .leaflet-popup-content-wrapper { border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
    .custom-marker { width: 36px; height: 36px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-size: 16px; }
</style>
@endpush

@section('content')
<div class="pt-0">
    {{-- Filter bar --}}
    <div class="bg-white border-b border-gray-200 px-4 py-3 sticky top-16 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center gap-3">
            <span class="text-sm font-semibold text-gray-700">Filter:</span>
            <select id="filter-kategori" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}">{{ $k->ikon }} {{ $k->nama_kategori }}</option>
                @endforeach
            </select>
            <select id="filter-keparahan" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30">
                <option value="">Semua Keparahan</option>
                <option value="rendah">🟢 Rendah</option>
                <option value="sedang">🟡 Sedang</option>
                <option value="tinggi">🔴 Tinggi</option>
            </select>
            <button id="btn-filter" class="text-sm px-4 py-1.5 rounded-lg bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white font-medium hover:shadow-md transition-all">
                Terapkan
            </button>
            <span id="count-info" class="text-xs text-gray-500 ml-auto"></span>
        </div>
    </div>

    {{-- Map --}}
    <div id="map"></div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('map').setView([-6.9175, 107.6191], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

let markers = L.layerGroup().addTo(map);

function getIcon(warna, ikon) {
    return L.divIcon({
        html: `<div class="custom-marker" style="background:${warna}">${ikon}</div>`,
        className: '',
        iconSize: [36, 36],
        iconAnchor: [18, 18],
        popupAnchor: [0, -20],
    });
}

function loadMarkers() {
    const kategoriId = document.getElementById('filter-kategori').value;
    const keparahan = document.getElementById('filter-keparahan').value;

    let url = `{{ route('peta.data') }}?`;
    if (kategoriId) url += `kategori_id=${kategoriId}&`;
    if (keparahan) url += `keparahan=${keparahan}&`;

    fetch(url)
        .then(r => r.json())
        .then(data => {
            markers.clearLayers();
            document.getElementById('count-info').textContent = `${data.length} laporan ditemukan`;

            data.forEach(l => {
                const icon = getIcon(l.warna, l.ikon);
                const keparahanBadge = {
                    rendah: '<span class="inline-block px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700">Rendah</span>',
                    sedang: '<span class="inline-block px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700">Sedang</span>',
                    tinggi: '<span class="inline-block px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700">Tinggi</span>',
                }[l.tingkat_keparahan] || '';

                const popup = `
                    <div style="min-width:220px;font-family:Inter,sans-serif">
                        ${l.foto ? `<img src="${l.foto}" class="w-full h-32 object-cover rounded-lg mb-3" />` : ''}
                        <div class="flex items-center gap-2 mb-2">
                            <span style="color:${l.warna};font-size:18px">${l.ikon}</span>
                            <strong style="font-size:14px">${l.kategori}</strong>
                            ${keparahanBadge}
                        </div>
                        <p style="font-size:12px;color:#666;margin-bottom:6px">📍 ${l.wilayah}</p>
                        <p style="font-size:12px;color:#444;margin-bottom:8px">${l.deskripsi}</p>
                        <p style="font-size:11px;color:#999">👤 ${l.pelapor} · ${l.tanggal}</p>
                        <a href="${l.url}" style="display:block;margin-top:10px;text-align:center;background:linear-gradient(135deg,#6C3DE6,#3B82F6);color:white;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none">
                            Lihat Detail →
                        </a>
                        <a href="/cari-kos?lat=${l.latitude}&lng=${l.longitude}" style="display:block;margin-top:6px;text-align:center;background:#f5f3ff;color:#6C3DE6;border:1px solid #ddd6fe;padding:6px 12px;border-radius:8px;font-size:11px;font-weight:600;text-decoration:none;transition:all 0.2s">
                            🏠 Analisis Cari Kos di Titik Ini →
                        </a>
                    </div>`;

                L.marker([l.latitude, l.longitude], { icon })
                    .bindPopup(popup)
                    .addTo(markers);
            });
        });
}

// Klik di peta untuk memilih titik calon kos
map.on('click', function(e) {
    const lat = e.latlng.lat.toFixed(6);
    const lng = e.latlng.lng.toFixed(6);
    
    const popupContent = `
        <div style="min-width:210px;font-family:Inter,sans-serif;padding:4px">
            <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px">
                <span style="font-size:20px">🎯</span>
                <strong style="font-size:13px;color:#1e293b">Pilih Calon Lokasi Kos</strong>
            </div>
            <p style="font-size:11px;color:#64748b;margin:0 0 10px 0">Koordinat: ${lat}, ${lng}</p>
            <a href="/cari-kos?lat=${lat}&lng=${lng}" 
               style="display:flex;align-items:center;justify-content:center;gap:6px;background:linear-gradient(135deg,#6C3DE6,#3B82F6);color:white;padding:8px 12px;border-radius:10px;font-size:12px;font-weight:700;text-decoration:none;box-shadow:0 4px 12px rgba(108,61,230,0.3)">
                🏠 Analisis Cari Kos di Sini →
            </a>
        </div>
    `;
    
    L.popup()
        .setLatLng(e.latlng)
        .setContent(popupContent)
        .openOn(map);
});

document.getElementById('btn-filter').addEventListener('click', loadMarkers);
loadMarkers();
</script>
@endpush
