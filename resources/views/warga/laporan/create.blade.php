@extends('layouts.dashboard')

@section('title', 'Buat Laporan')
@section('page-title', 'Buat Laporan Baru')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>#picker-map { height: 300px; border-radius: 16px; overflow: hidden; }</style>
@endpush

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-6">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('warga.laporan.store') }}" enctype="multipart/form-data" class="space-y-5" id="form-laporan">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Laporan *</label>
                <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Contoh: Aspal Rusak Berlubang Parah di Depan Puskesmas"
                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori *</label>
                    <select name="kategori_id" required class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
                        <option value="">Pilih kategori...</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->ikon }} {{ $k->nama_kategori }} @if($k->dinas_terkait) — 🏛️ {{ $k->dinas_terkait }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Wilayah (Kecamatan) *</label>
                    <select name="wilayah" required class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
                        <option value="">Pilih kecamatan...</option>
                        @foreach($kecamatan as $k)
                            <option value="{{ $k }}" {{ old('wilayah') === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tingkat Keparahan *</label>
                <div class="flex gap-3">
                    @foreach(['rendah' => ['🟢', 'Rendah', 'green'], 'sedang' => ['🟡', 'Sedang', 'yellow'], 'tinggi' => ['🔴', 'Tinggi', 'red']] as $val => [$icon, $label, $color])
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="tingkat_keparahan" value="{{ $val }}" {{ old('tingkat_keparahan', 'sedang') === $val ? 'checked' : '' }} class="sr-only peer">
                            <div class="text-center px-3 py-2.5 rounded-xl border-2 border-gray-200 peer-checked:border-[#6C3DE6] peer-checked:bg-[#6C3DE6]/5 transition-all text-sm font-medium">
                                {{ $icon }} {{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Kejadian *</label>
                <textarea name="deskripsi" rows="4" required minlength="20" maxlength="2000"
                          class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6] resize-none"
                          placeholder="Jelaskan kondisi yang terjadi secara detail...">{{ old('deskripsi') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto (opsional)</label>
                <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg"
                       class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#6C3DE6]/10 file:text-[#6C3DE6] hover:file:bg-[#6C3DE6]/20">
                <p class="text-xs text-gray-400 mt-1">Maks. 5MB · JPG, PNG</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pinpoint Lokasi di Peta *</label>
                <p class="text-xs text-gray-400 mb-2">Klik pada peta untuk menentukan titik kejadian</p>
                <div id="picker-map" class="border border-gray-200"></div>
                <div class="grid grid-cols-2 gap-3 mt-3">
                    <div>
                        <label class="text-xs text-gray-500">Latitude</label>
                        <input type="number" id="lat-display" name="latitude" step="any" readonly
                               value="{{ old('latitude', $initLat) }}"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Longitude</label>
                        <input type="number" id="lng-display" name="longitude" step="any" readonly
                               value="{{ old('longitude', $initLng) }}"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm bg-gray-50 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('warga.laporan.index') }}" class="flex-1 py-3 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium text-center hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="button" onclick="showKonfirmasiModal()" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Konfirmasi --}}
<div id="modal-konfirmasi" class="fixed inset-0 z-[9999] flex items-center justify-center hidden">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="hideKonfirmasiModal()"></div>

    {{-- Modal Box --}}
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6 transform transition-all scale-95 opacity-0" id="modal-box">
        {{-- Icon --}}
        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-[#6C3DE6]/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#6C3DE6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        {{-- Content --}}
        <h3 class="text-lg font-bold text-gray-800 text-center mb-1">Kirim Laporan?</h3>
        <p class="text-sm text-gray-500 text-center mb-6">Pastikan semua data yang Anda isi sudah benar sebelum mengirimkan laporan.</p>

        {{-- Actions --}}
        <div class="flex gap-3">
            <button type="button" onclick="hideKonfirmasiModal()" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition-colors">
                Periksa Lagi
            </button>
            <button type="button" onclick="submitLaporan()" class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold hover:shadow-lg transition-all">
                Ya, Kirim
            </button>
        </div>
    </div>
</div>

{{-- Modal Peringatan Data Belum Lengkap --}}
<div id="modal-peringatan" class="fixed inset-0 z-[9999] flex items-center justify-center hidden">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="hidePeringatanModal()"></div>

    {{-- Modal Box --}}
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6 transform transition-all scale-95 opacity-0" id="peringatan-box">
        {{-- Icon Warning --}}
        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-amber-100 text-amber-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        {{-- Content --}}
        <h3 class="text-lg font-bold text-gray-800 text-center mb-1">Data Belum Lengkap!</h3>
        <p class="text-sm text-gray-500 text-center mb-4">Mohon lengkapi data yang masih kosong sebelum mengirim laporan:</p>

        {{-- Daftar yang belum diisi --}}
        <div class="bg-amber-50 border border-amber-200/80 rounded-xl p-3.5 mb-5">
            <ul id="list-peringatan" class="space-y-1.5 text-xs font-medium text-amber-900">
                <!-- Diisi otomatis oleh JavaScript -->
            </ul>
        </div>

        {{-- Action Button --}}
        <button type="button" onclick="hidePeringatanModal()" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold hover:shadow-lg transition-all">
            Lengkapi Data Sekarang
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('picker-map').setView([-6.9175, 107.6191], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution:'© OSM'}).addTo(map);
let marker = null;

// Koordinat dari peta atau old() jika ada
const initLat = {{ old('latitude', $initLat ?? 0) ?: 0 }};
const initLng = {{ old('longitude', $initLng ?? 0) ?: 0 }};
if (initLat && initLng) {
    marker = L.marker([initLat, initLng]).addTo(map);
    map.setView([initLat, initLng], 16);
    document.getElementById('lat-display').value = initLat;
    document.getElementById('lng-display').value = initLng;
}

map.on('click', e => {
    if (marker) map.removeLayer(marker);
    marker = L.marker(e.latlng).addTo(map);
    document.getElementById('lat-display').value = e.latlng.lat.toFixed(6);
    document.getElementById('lng-display').value = e.latlng.lng.toFixed(6);
});

// ── Modal Konfirmasi ──────────────────────────────────────────
function showKonfirmasiModal() {
    const modal = document.getElementById('modal-konfirmasi');
    const box   = document.getElementById('modal-box');
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        box.classList.remove('scale-95', 'opacity-0');
        box.classList.add('scale-100', 'opacity-100');
    });
}

function hideKonfirmasiModal() {
    const modal = document.getElementById('modal-konfirmasi');
    const box   = document.getElementById('modal-box');
    box.classList.remove('scale-100', 'opacity-100');
    box.classList.add('scale-95', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 200);
}

// ── Validasi Form & Modal Peringatan ─────────────────────────
let firstInvalidElement = null;

function validateForm() {
    const errors = [];
    const form = document.getElementById('form-laporan');
    
    const kategori = form.querySelector('[name="kategori_id"]');
    const wilayah = form.querySelector('[name="wilayah"]');
    const deskripsi = form.querySelector('[name="deskripsi"]');
    const lat = document.getElementById('lat-display');
    const lng = document.getElementById('lng-display');

    if (!kategori || !kategori.value) {
        errors.push({ element: kategori, message: 'Kategori laporan belum dipilih' });
    }
    if (!wilayah || !wilayah.value) {
        errors.push({ element: wilayah, message: 'Wilayah (Kecamatan) belum dipilih' });
    }
    if (!deskripsi || !deskripsi.value.trim()) {
        errors.push({ element: deskripsi, message: 'Deskripsi kejadian belum diisi' });
    } else if (deskripsi.value.trim().length < 20) {
        errors.push({ element: deskripsi, message: `Deskripsi minimal 20 karakter (saat ini ${deskripsi.value.trim().length})` });
    }
    if (!lat || !lat.value || !lng || !lng.value) {
        errors.push({ element: document.getElementById('picker-map'), message: 'Titik lokasi kejadian pada peta belum ditentukan' });
    }

    return errors;
}

function showPeringatanModal(errors) {
    const modal = document.getElementById('modal-peringatan');
    const box = document.getElementById('peringatan-box');
    const list = document.getElementById('list-peringatan');

    list.innerHTML = '';
    errors.forEach(err => {
        const li = document.createElement('li');
        li.className = 'flex items-center gap-2';
        li.innerHTML = `<span class="text-amber-500 font-bold">•</span><span>${err.message}</span>`;
        list.appendChild(li);
    });

    firstInvalidElement = errors[0]?.element || null;

    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        box.classList.remove('scale-95', 'opacity-0');
        box.classList.add('scale-100', 'opacity-100');
    });
}

function hidePeringatanModal() {
    const modal = document.getElementById('modal-peringatan');
    const box = document.getElementById('peringatan-box');
    box.classList.remove('scale-100', 'opacity-100');
    box.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        if (firstInvalidElement) {
            firstInvalidElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (typeof firstInvalidElement.focus === 'function') {
                firstInvalidElement.focus();
            }
            firstInvalidElement = null;
        }
    }, 200);
}

function submitLaporan() {
    const errors = validateForm();
    if (errors.length > 0) {
        hideKonfirmasiModal();
        showPeringatanModal(errors);
        return;
    }

    // Jika valid, submit form
    document.getElementById('form-laporan').submit();
}

// Tutup modal dengan tombol Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        hideKonfirmasiModal();
        hidePeringatanModal();
    }
});
</script>
@endpush
