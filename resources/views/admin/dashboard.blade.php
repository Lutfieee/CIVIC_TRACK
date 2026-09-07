@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
    @foreach([
        ['icon' => '📋', 'label' => 'Total Laporan', 'value' => $stats['total_laporan'], 'color' => 'from-purple-500 to-purple-600'],
        ['icon' => '⏳', 'label' => 'Pending', 'value' => $stats['pending'], 'color' => 'from-yellow-400 to-yellow-500'],
        ['icon' => '✅', 'label' => 'Terverifikasi', 'value' => $stats['diverifikasi'], 'color' => 'from-green-500 to-emerald-500'],
        ['icon' => '❌', 'label' => 'Ditolak', 'value' => $stats['ditolak'], 'color' => 'from-red-500 to-red-600'],
        ['icon' => '👤', 'label' => 'Warga', 'value' => $stats['total_warga'], 'color' => 'from-blue-500 to-blue-600'],
        ['icon' => '🛡️', 'label' => 'Administrator', 'value' => $stats['total_admin'], 'color' => 'from-indigo-500 to-indigo-600'],
    ] as $stat)
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $stat['color'] }} flex items-center justify-center text-lg mb-3">
                {{ $stat['icon'] }}
            </div>
            <p class="text-3xl font-extrabold text-gray-900">{{ $stat['value'] }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $stat['label'] }}</p>
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
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-800">Laporan Terbaru</h3>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Pelapor</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Wilayah</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($laporanTerbaru as $l)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3.5 font-medium text-gray-800">{{ $l->user->name }}</td>
                <td class="px-5 py-3.5">
                    <span class="flex items-center gap-1.5">
                        <span>{{ $l->kategori->ikon }}</span>
                        <span class="text-gray-700">{{ $l->kategori->nama_kategori }}</span>
                    </span>
                </td>
                <td class="px-5 py-3.5 text-gray-500">{{ $l->wilayah }}</td>
                <td class="px-5 py-3.5">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $l->status === 'diverifikasi' ? 'bg-green-100 text-green-700' : ($l->status === 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($l->status) }}
                    </span>
                </td>
                <td class="px-5 py-3.5 text-gray-400">{{ $l->created_at->format('d M Y') }}</td>
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
            pointBackgroundColor: '#6C3DE6', pointRadius: 4,
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
