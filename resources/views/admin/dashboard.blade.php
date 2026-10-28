@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
@if(auth()->user()->isDinas())
    <div class="mb-6 p-5 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white shadow-lg flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-2xl">🏛️</div>
            <div>
                <h2 class="text-lg font-bold">Portal Penanganan: {{ auth()->user()->instansi }}</h2>
                <p class="text-xs text-white/85 mt-0.5">Dashboard difilter otomatis khusus untuk pemantauan dan penyelesaian laporan di bawah tanggung jawab dinas Anda.</p>
            </div>
        </div>
        <a href="{{ route('admin.antrian') }}" class="px-4 py-2 rounded-xl bg-white text-indigo-700 font-bold text-xs hover:bg-white/90 transition-all shadow">
            Cek Antrian Verifikasi ({{ $stats['pending'] }}) →
        </a>
    </div>
@endif

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['icon' => '📋', 'label' => 'Total Laporan', 'value' => $stats['total_laporan'], 'color' => 'from-purple-500 to-purple-600'],
        ['icon' => '⏳', 'label' => 'Menunggu Verifikasi', 'value' => $stats['pending'], 'color' => 'from-amber-400 to-amber-500'],
        ['icon' => '⚙️', 'label' => 'Sedang Diproses', 'value' => $stats['diproses'] ?? 0, 'color' => 'from-blue-500 to-blue-600'],
        ['icon' => '✅', 'label' => 'Selesai Ditangani', 'value' => $stats['selesai'] ?? 0, 'color' => 'from-emerald-500 to-emerald-600'],
        ['icon' => '🚌', 'label' => 'Rute Transportasi', 'value' => $stats['total_transportasi'] ?? 0, 'color' => 'from-indigo-500 to-indigo-600'],
        ['icon' => '🗺️', 'label' => 'Master Wilayah', 'value' => $stats['total_wilayah'] ?? 0, 'color' => 'from-sky-500 to-sky-600'],
        ['icon' => '👤', 'label' => 'Warga Terdaftar', 'value' => $stats['total_warga'], 'color' => 'from-teal-500 to-teal-600'],
        ['icon' => '🛡️', 'label' => 'Administrator', 'value' => $stats['total_admin'], 'color' => 'from-gray-700 to-gray-800'],
    ] as $stat)
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $stat['color'] }} text-white flex items-center justify-center text-lg mb-3">
                {{ $stat['icon'] }}
            </div>
            <p class="text-3xl font-extrabold text-gray-900">{{ $stat['value'] }}</p>
            <p class="text-xs font-medium text-gray-500 mt-1">{{ $stat['label'] }}</p>
        </div>
    @endforeach
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4">Laporan per Kategori</h3>
        <canvas id="chart-kategori" height="220"></canvas>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4">Tren Laporan 6 Bulan Terakhir</h3>
        <canvas id="chart-tren" height="220"></canvas>
    </div>
</div>

{{-- Recent Reports Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-gray-800">Laporan Masuk Terbaru</h3>
        <a href="{{ route('admin.laporan.index') }}" class="text-xs font-bold text-primary hover:underline">
            Lihat Semua Laporan →
        </a>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Judul & Kategori</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Pelapor</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Wilayah</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($laporanTerbaru as $l)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3.5">
                    <div class="font-bold text-gray-900 text-sm">{{ $l->judul ?? 'Laporan #' . $l->id }}</div>
                    <span class="text-xs text-gray-500">{{ $l->kategori->ikon }} {{ $l->kategori->nama_kategori }}</span>
                </td>
                <td class="px-5 py-3.5 font-medium text-gray-800">{{ $l->user->name }}</td>
                <td class="px-5 py-3.5 text-gray-500">{{ $l->wilayah }}</td>
                <td class="px-5 py-3.5">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold
                        {{ $l->status == 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                        {{ $l->status == 'diverifikasi' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $l->status == 'diproses' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $l->status == 'selesai' ? 'bg-emerald-100 text-emerald-800' : '' }}
                        {{ $l->status == 'ditolak' ? 'bg-rose-100 text-rose-800' : '' }}">
                        {{ $l->status_label }}
                    </span>
                </td>
                <td class="px-5 py-3.5">
                    <a href="{{ route('admin.laporan.show', $l->id) }}" class="text-xs font-semibold text-primary hover:underline">
                        Kelola →
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
const kategoriData = @json($perKategori);
const trenData = @json($perBulan);

// Kategori pie chart
new Chart(document.getElementById('chart-kategori'), {
    type: 'doughnut',
    data: {
        labels: kategoriData.map(d => d.label),
        datasets: [{
            data: kategoriData.map(d => d.total),
            backgroundColor: kategoriData.map(d => d.warna),
            borderWidth: 2, borderColor: '#fff',
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

// Trend line chart
new Chart(document.getElementById('chart-tren'), {
    type: 'line',
    data: {
        labels: trenData.map(d => d.bulan),
        datasets: [{
            label: 'Jumlah Laporan',
            data: trenData.map(d => d.total),
            borderColor: '#6C3DE6',
            backgroundColor: 'rgba(108,61,230,0.1)',
            tension: 0.4, fill: true,
            pointBackgroundColor: '#1a5528', pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } },
            x: { grid: { display: false } }
        },
        plugins: { legend: { display: false } }
    }
});
</script>
@endpush
