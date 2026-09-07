@extends('layouts.dashboard')

@section('title', 'Review Laporan')
@section('page-title', 'Review Laporan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>#mini-map { height: 220px; border-radius: 12px; }</style>
@endpush

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($laporan->foto)
            <img src="{{ asset('storage/' . $laporan->foto) }}" class="w-full h-52 object-cover">
        @endif
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-2xl">{{ $laporan->kategori->ikon }}</span>
                        <h2 class="text-xl font-bold text-gray-800">{{ $laporan->kategori->nama_kategori }}</h2>
                    </div>
                    <p class="text-sm text-gray-500">{{ $laporan->wilayah }} - oleh <strong>{{ $laporan->user->name }}</strong> - {{ $laporan->created_at->format('d M Y') }}</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                    {{ $laporan->tingkat_keparahan === 'tinggi' ? 'bg-red-100 text-red-700' : ($laporan->tingkat_keparahan === 'sedang' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                    {{ ucfirst($laporan->tingkat_keparahan) }}
                </span>
            </div>

            <p class="text-gray-700 text-sm leading-relaxed mb-6">{{ $laporan->deskripsi }}</p>

            <div id="mini-map" class="border border-gray-200 mb-6"></div>

            @if($laporan->status === 'pending')
                <div class="border-t border-gray-100 pt-6">
                    <h3 class="font-bold text-gray-800 mb-4">Tindakan Verifikasi</h3>
                    <div class="flex gap-4">
                        <form method="POST" action="{{ route('admin.laporan.approve', $laporan) }}" class="flex-1">
                            @csrf
                            <button type="submit" onclick="return confirm('Verifikasi dan publikasikan laporan ini?')"
                                    class="w-full py-3 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold text-sm hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                Verifikasi & Publikasikan
                            </button>
                        </form>
                        <div class="flex-1">
                            <button id="btn-tolak-toggle"
                                    class="w-full py-3 rounded-xl border-2 border-red-300 text-red-600 font-semibold text-sm hover:bg-red-50 transition-all">
                                Tolak Laporan
                            </button>
                        </div>
                    </div>

                    <form id="form-tolak" method="POST" action="{{ route('admin.laporan.reject', $laporan) }}" class="hidden mt-4">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Alasan Penolakan *</label>
                        <textarea name="alasan_penolakan" rows="3" required minlength="10"
                                  class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400 resize-none"
                                  placeholder="Jelaskan alasan penolakan laporan ini..."></textarea>
                        <div class="flex gap-3 mt-3">
                            <button type="button" id="btn-tolak-cancel" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50">Batal</button>
                            <button type="submit" class="flex-1 py-2.5 rounded-xl bg-red-500 text-white text-sm font-semibold hover:shadow-md transition-all">Konfirmasi Tolak</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="border-t border-gray-100 pt-4">
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold
                        {{ $laporan->status === 'diverifikasi' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        Laporan ini sudah {{ $laporan->status }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    <a href="{{ route('admin.antrian') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#6C3DE6] transition-colors">
        Kembali ke Antrian
    </a>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('mini-map').setView([{{ $laporan->latitude }}, {{ $laporan->longitude }}], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution:'OSM'}).addTo(map);
L.circleMarker([{{ $laporan->latitude }}, {{ $laporan->longitude }}], {
    radius: 10, fillColor: '{{ $laporan->kategori->warna }}', color: 'white', weight: 3, fillOpacity: 0.9
}).addTo(map);

const btn = document.getElementById('btn-tolak-toggle');
const form = document.getElementById('form-tolak');
const cancel = document.getElementById('btn-tolak-cancel');
if (btn) btn.addEventListener('click', () => form.classList.toggle('hidden'));
if (cancel) cancel.addEventListener('click', () => form.classList.add('hidden'));
</script>
@endpush
