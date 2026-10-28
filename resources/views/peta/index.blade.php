@extends('layouts.app')

@section('title', 'Peta Kejadian')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: calc(100vh - 64px - 56px); min-height: 500px; }
    .leaflet-popup-content-wrapper { border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.18); border: 1px solid #f0f0f0; }
    .leaflet-popup-content { margin: 0; padding: 0; }
    .custom-marker { width: 36px; height: 36px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-size: 16px; }
    .popup-inner { padding: 14px 16px 16px; font-family: Inter, sans-serif; min-width: 240px; max-width: 290px; }
    .popup-click { padding: 16px; font-family: Inter, sans-serif; min-width: 220px; }
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
                <option value="rendah">Rendah</option>
                <option value="sedang">Sedang</option>
                <option value="tinggi">Tinggi</option>
            </select>
            <button id="btn-filter" class="text-sm px-4 py-1.5 rounded-lg bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white font-medium hover:shadow-md transition-all">Terapkan</button>
            <a href="{{ route('warga.laporan.cepat') }}"
               class="text-xs px-3.5 py-1.5 rounded-lg bg-gradient-to-r from-amber-500 to-purple-600 text-white font-bold hover:shadow-md hover:scale-105 transition-all flex items-center gap-1 shadow-xs ml-1">
                <span>⚡</span> <span>Laporan Cepat</span>
            </a>
            <span id="count-info" class="text-xs text-gray-500 ml-auto font-medium"></span>
            <span class="text-xs text-gray-400 italic hidden sm:inline">&#128161; Klik area kosong untuk membuat laporan baru</span>
        </div>
    </div>
    {{-- Map container --}}
    <div id="map"></div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('map').setView([-6.9175, 107.6191], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

let markers = L.layerGroup().addTo(map);

@auth
const _isLoggedIn = true;
const _isWarga    = {{ auth()->user()->role === 'warga' ? 'true' : 'false' }};
const _createUrl  = '{{ route("warga.laporan.create") }}';
const _loginUrl   = '{{ route("login") }}';
const _registerUrl= '{{ route("register") }}';
@else
const _isLoggedIn  = false;
const _isWarga     = false;
const _createUrl   = '';
const _loginUrl    = '{{ route("login") }}';
const _registerUrl = '{{ route("register") }}';
@endauth

const _petaDataUrl = '{{ route("peta.data") }}';

function makeIcon(warna, ikon) {
    return L.divIcon({
        html: '<div class="custom-marker" style="background:' + warna + '">' + ikon + '</div>',
        className: '',
        iconSize: [36, 36],
        iconAnchor: [18, 18],
        popupAnchor: [0, -22],
    });
}

function keparahanBadge(level) {
    if (level === 'rendah') return '<span style="display:inline-block;padding:2px 8px;border-radius:999px;font-size:11px;background:#dcfce7;color:#15803d;font-weight:600">Rendah</span>';
    if (level === 'sedang') return '<span style="display:inline-block;padding:2px 8px;border-radius:999px;font-size:11px;background:#fef9c3;color:#854d0e;font-weight:600">Sedang</span>';
    if (level === 'tinggi') return '<span style="display:inline-block;padding:2px 8px;border-radius:999px;font-size:11px;background:#fee2e2;color:#b91c1c;font-weight:600">Tinggi</span>';
    return '';
}

function statusBadge(status, label) {
    var s = 'background:#f3f4f6;color:#374151';
    if (status === 'diverifikasi') s = 'background:#dbeafe;color:#1d4ed8';
    if (status === 'diproses')     s = 'background:#ede9fe;color:#6d28d9';
    if (status === 'selesai')      s = 'background:#d1fae5;color:#065f46';
    return '<span style="display:inline-block;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;' + s + '">' + label + '</span>';
}

function loadMarkers() {
    var kategoriId = document.getElementById('filter-kategori').value;
    var keparahan  = document.getElementById('filter-keparahan').value;
    var url = _petaDataUrl + '?';
    if (kategoriId) url += 'kategori_id=' + kategoriId + '&';
    if (keparahan)  url += 'keparahan=' + keparahan + '&';

    fetch(url)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            markers.clearLayers();
            document.getElementById('count-info').textContent = data.length + ' laporan ditemukan';

            data.forEach(function(l) {
                var icon = makeIcon(l.warna, l.ikon);

                var fotoHtml = l.foto
                    ? '<img src="' + l.foto + '" style="width:100%;height:120px;object-fit:cover;border-radius:10px;margin-bottom:10px" />'
                    : '';

                var tindakHtml = l.tindak_lanjut
                    ? '<div style="background:#eef2ff;border:1px solid #c7d2fe;border-radius:8px;padding:8px 10px;margin:8px 0"><p style="font-size:10px;font-weight:700;color:#4338ca;margin:0 0 3px 0;text-transform:uppercase">&#128226; Tindak Lanjut</p><p style="font-size:11px;color:#3730a3;margin:0;line-height:1.5">' + l.tindak_lanjut + '</p></div>'
                    : '';

                var komentarHtml = l.komentar_count > 0
                    ? '<span style="font-size:11px;color:#6b7280">&#128172; ' + l.komentar_count + ' komentar</span>'
                    : '<span style="font-size:11px;color:#9ca3af">&#128172; Belum ada komentar</span>';

                // Tombol buat laporan di titik marker (khusus warga)
                var buatLaporanBtn = _isWarga
                    ? '<a href="' + _createUrl + '?lat=' + l.latitude + '&lng=' + l.longitude + '" style="display:flex;align-items:center;justify-content:center;gap:5px;background:#f5f3ff;color:#6C3DE6;border:1.5px solid #ddd6fe;padding:7px 14px;border-radius:10px;font-size:11px;font-weight:600;text-decoration:none;margin-top:6px">&#10133; Buat Laporan di Titik Ini</a>'
                    : '';

                var dinasHtml = l.dinas
                    ? '<div style="font-size:10px;color:#1e40af;background:#eff6ff;padding:2px 7px;border-radius:6px;margin-top:3px;font-weight:600;display:inline-block">🏛️ ' + l.dinas + '</div>'
                    : '';

                var popup =
                    '<div class="popup-inner">' +
                    fotoHtml +
                    '<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;margin-bottom:6px">' +
                        '<div>' +
                            '<div style="display:flex;align-items:center;gap:5px;margin-bottom:4px">' +
                                '<span style="font-size:18px">' + l.ikon + '</span>' +
                                '<strong style="font-size:13px;color:#111827">' + l.kategori + '</strong>' +
                            '</div>' +
                            '<p style="font-size:12px;color:#6b7280;margin:0">&#128205; ' + l.wilayah + '</p>' +
                            dinasHtml +
                        '</div>' +
                        '<div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;flex-shrink:0">' +
                            statusBadge(l.status, l.status_label) +
                            keparahanBadge(l.tingkat_keparahan) +
                        '</div>' +
                    '</div>' +
                    '<p style="font-size:12px;color:#374151;margin:6px 0;line-height:1.55">' + l.deskripsi + '</p>' +
                    tindakHtml +
                    '<div style="display:flex;align-items:center;justify-content:space-between;margin-top:8px">' +
                        '<span style="font-size:11px;color:#9ca3af">&#128100; ' + l.pelapor + ' &middot; ' + l.tanggal + '</span>' +
                        komentarHtml +
                    '</div>' +
                    '<a href="' + l.url + '" style="display:flex;align-items:center;justify-content:center;gap:6px;background:linear-gradient(135deg,#6C3DE6,#3B82F6);color:white;padding:8px 14px;border-radius:10px;font-size:12px;font-weight:700;text-decoration:none;margin-top:10px;box-shadow:0 3px 10px rgba(108,61,230,0.3)">' +
                        '&#128172; Lihat Detail &amp; Komentar &#8594;' +
                    '</a>' +
                    buatLaporanBtn +
                    '</div>';

                var marker = L.marker([l.latitude, l.longitude], { icon: icon });
                marker.bindPopup(popup, { maxWidth: 300 });

                // Klik marker: tampilkan popup laporan,
                // lalu juga trigger popup buat laporan di area kosong via map click
                marker.on('click', function(ev) {
                    // Hentikan propagasi agar map.on('click') tidak tumpang tindih
                    L.DomEvent.stopPropagation(ev);
                });

                marker.addTo(markers);
            });
        });
}

// Klik area KOSONG di peta -> popup buat laporan baru
map.on('click', function(e) {
    var lat = e.latlng.lat.toFixed(6);
    var lng = e.latlng.lng.toFixed(6);
    var actionHtml;

    if (_isLoggedIn && _isWarga) {
        actionHtml =
            '<a href="' + _createUrl + '?lat=' + lat + '&lng=' + lng + '" ' +
            'style="display:flex;align-items:center;justify-content:center;gap:6px;background:linear-gradient(135deg,#6C3DE6,#3B82F6);color:white;padding:9px 14px;border-radius:10px;font-size:12px;font-weight:700;text-decoration:none;box-shadow:0 3px 10px rgba(108,61,230,0.3)">' +
            '&#10133; Buat Laporan di Titik Ini</a>';
    } else if (_isLoggedIn) {
        // Admin: tidak perlu buat laporan dari peta
        return;
    } else {
        actionHtml =
            '<p style="font-size:11px;color:#6b7280;margin:0 0 10px 0;text-align:center">Login sebagai warga untuk membuat laporan di titik ini.</p>' +
            '<div style="display:flex;gap:8px">' +
                '<a href="' + _loginUrl + '" style="flex:1;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#6C3DE6,#3B82F6);color:white;padding:8px;border-radius:10px;font-size:12px;font-weight:700;text-decoration:none">Masuk</a>' +
                '<a href="' + _registerUrl + '" style="flex:1;display:flex;align-items:center;justify-content:center;background:#f5f3ff;color:#6C3DE6;border:1.5px solid #ddd6fe;padding:8px;border-radius:10px;font-size:12px;font-weight:600;text-decoration:none">Daftar</a>' +
            '</div>';
    }

    var popupContent =
        '<div class="popup-click">' +
            '<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">' +
                '<div style="width:32px;height:32px;background:linear-gradient(135deg,#6C3DE6,#3B82F6);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0">&#128205;</div>' +
                '<div>' +
                    '<strong style="font-size:13px;color:#111827;display:block">Laporkan Masalah di Sini</strong>' +
                    '<span style="font-size:10px;color:#9ca3af">' + lat + ', ' + lng + '</span>' +
                '</div>' +
            '</div>' +
            '<p style="font-size:11px;color:#6b7280;margin:0 0 12px 0;line-height:1.5">Temukan masalah lingkungan atau fasilitas publik? Buat laporan agar segera ditindaklanjuti.</p>' +
            actionHtml +
        '</div>';

    L.popup({ maxWidth: 280 })
        .setLatLng(e.latlng)
        .setContent(popupContent)
        .openOn(map);
});

document.getElementById('btn-filter').addEventListener('click', loadMarkers);
loadMarkers();

window.addEventListener('resize', function() {
    if (typeof map !== 'undefined' && map) {
        map.invalidateSize();
    }
});
setTimeout(function() {
    if (typeof map !== 'undefined' && map) {
        map.invalidateSize();
    }
}, 500);
</script>
@endpush