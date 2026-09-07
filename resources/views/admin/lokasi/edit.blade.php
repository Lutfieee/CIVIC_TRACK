@extends('layouts.dashboard')

@section('title', 'Edit Lokasi Kos')
@section('page-title', 'Edit Lokasi Kos')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>#picker-map { height: 320px; border-radius: 16px; overflow: hidden; }</style>
@endpush

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-6">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.lokasi.update', $lokasi) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kos / Penginapan *</label>
                    <input type="text" name="nama" value="{{ old('nama', $lokasi->nama) }}" required
                           class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kecamatan / Wilayah *</label>
                    <select name="wilayah" required class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
                        @foreach($kecamatan as $k)
                            <option value="{{ $k }}" {{ old('wilayah', $lokasi->wilayah) === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Lengkap *</label>
                <input type="text" name="alamat" value="{{ old('alamat', $lokasi->alamat) }}" required
                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga per Bulan (Rp)</label>
                    <input type="number" name="harga_per_bulan" value="{{ old('harga_per_bulan', $lokasi->harga_per_bulan) }}"
                           class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kontak / WhatsApp</label>
                    <input type="text" name="kontak" value="{{ old('kontak', $lokasi->kontak) }}"
                           class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori / Fasilitas Lingkungan</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @php $selectedKats = old('kategoris', $lokasi->kategoris->pluck('id')->toArray()); @endphp
                    @foreach($kategoris as $kat)
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer text-xs font-medium text-gray-700">
                            <input type="checkbox" name="kategoris[]" value="{{ $kat->id }}"
                                   {{ in_array($kat->id, $selectedKats) ? 'checked' : '' }}
                                   class="rounded text-[#6C3DE6] focus:ring-[#6C3DE6]/30">
                            <span>{{ $kat->ikon }} {{ $kat->nama_kategori }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6] resize-none">{{ old('deskripsi', $lokasi->deskripsi) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto Kos</label>
                @if($lokasi->foto)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $lokasi->foto) }}" class="w-32 h-24 object-cover rounded-xl border border-gray-200">
                        <p class="text-xs text-gray-400 mt-1">Upload foto baru untuk mengganti.</p>
                    </div>
                @endif
                <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg"
                       class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#6C3DE6]/10 file:text-[#6C3DE6]">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Titik Koordinat Peta *</label>
                <p class="text-xs text-gray-400 mb-2">Klik atau geser marker pada peta untuk mengubah koordinat.</p>
                <div id="picker-map" class="border border-gray-200 mb-3"></div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-gray-500">Latitude</label>
                        <input type="number" id="lat-input" name="latitude" step="any" readonly value="{{ old('latitude', $lokasi->latitude) }}"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm bg-gray-50 font-mono">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Longitude</label>
                        <input type="number" id="lng-input" name="longitude" step="any" readonly value="{{ old('longitude', $lokasi->longitude) }}"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm bg-gray-50 font-mono">
                    </div>
                </div>
            </div>

            <label class="flex items-center gap-3 cursor-pointer pt-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $lokasi->is_active ? '1' : '') === '1' ? 'checked' : '' }} class="rounded border-gray-300 text-[#6C3DE6]">
                <span class="text-sm font-medium text-gray-700">Status Aktif (Tampilkan di pencarian publik)</span>
            </label>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.lokasi.index') }}" class="flex-1 py-3 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium text-center hover:bg-gray-50">Batal</a>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold hover:shadow-lg transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const curLat = {{ old('latitude', $lokasi->latitude) }};
const curLng = {{ old('longitude', $lokasi->longitude) }};

const map = L.map('picker-map').setView([curLat, curLng], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

let marker = L.marker([curLat, curLng], { draggable: true }).addTo(map);

marker.on('dragend', function (e) {
    const latlng = marker.getLatLng();
    document.getElementById('lat-input').value = latlng.lat.toFixed(6);
    document.getElementById('lng-input').value = latlng.lng.toFixed(6);
});

map.on('click', function (e) {
    marker.setLatLng(e.latlng);
    document.getElementById('lat-input').value = e.latlng.lat.toFixed(6);
    document.getElementById('lng-input').value = e.latlng.lng.toFixed(6);
});
</script>
@endpush
