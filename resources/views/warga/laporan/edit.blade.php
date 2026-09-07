@extends('layouts.dashboard')

@section('title', 'Edit Laporan')
@section('page-title', 'Edit Laporan')

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

        <form method="POST" action="{{ route('warga.laporan.update', $laporan) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori *</label>
                    <select name="kategori_id" required class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id', $laporan->kategori_id) == $k->id ? 'selected' : '' }}>{{ $k->ikon }} {{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Wilayah *</label>
                    <select name="wilayah" required class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
                        @foreach($kecamatan as $k)
                            <option value="{{ $k }}" {{ old('wilayah', $laporan->wilayah) === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tingkat Keparahan *</label>
                <div class="flex gap-3">
                    @foreach(['rendah' => '🟢 Rendah', 'sedang' => '🟡 Sedang', 'tinggi' => '🔴 Tinggi'] as $val => $label)
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="tingkat_keparahan" value="{{ $val }}" {{ old('tingkat_keparahan', $laporan->tingkat_keparahan) === $val ? 'checked' : '' }} class="sr-only peer">
                            <div class="text-center px-3 py-2.5 rounded-xl border-2 border-gray-200 peer-checked:border-[#6C3DE6] peer-checked:bg-[#6C3DE6]/5 transition-all text-sm font-medium">{{ $label }}</div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi *</label>
                <textarea name="deskripsi" rows="4" required minlength="20"
                          class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6] resize-none">{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto (opsional)</label>
                @if($laporan->foto)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $laporan->foto) }}" class="w-32 h-24 object-cover rounded-xl border border-gray-200">
                        <p class="text-xs text-gray-400 mt-1">Foto saat ini. Upload baru untuk mengganti.</p>
                    </div>
                @endif
                <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg"
                       class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#6C3DE6]/10 file:text-[#6C3DE6]">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kejadian *</label>
                <div id="picker-map" class="border border-gray-200 mb-3"></div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-gray-500">Latitude</label>
                        <input type="number" id="lat-display" name="latitude" step="any" readonly value="{{ old('latitude', $laporan->latitude) }}"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm bg-gray-50">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Longitude</label>
                        <input type="number" id="lng-display" name="longitude" step="any" readonly value="{{ old('longitude', $laporan->longitude) }}"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm bg-gray-50">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('warga.laporan.index') }}" class="flex-1 py-3 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium text-center hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold hover:shadow-lg transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('picker-map').setView([{{ $laporan->latitude }}, {{ $laporan->longitude }}], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution:'© OSM'}).addTo(map);
let marker = L.marker([{{ $laporan->latitude }}, {{ $laporan->longitude }}]).addTo(map);
map.on('click', e => {
    marker.setLatLng(e.latlng);
    document.getElementById('lat-display').value = e.latlng.lat.toFixed(6);
    document.getElementById('lng-display').value = e.latlng.lng.toFixed(6);
});
</script>
@endpush
