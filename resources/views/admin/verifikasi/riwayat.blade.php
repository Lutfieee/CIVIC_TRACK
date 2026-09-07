@extends('layouts.dashboard')

@section('title', 'Riwayat Verifikasi')
@section('page-title', 'Riwayat Verifikasi')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-bold text-gray-800">Riwayat Verifikasi Saya</h2>
    </div>

    @if($laporans->isEmpty())
        <div class="text-center py-16">
            <p class="text-gray-500 text-sm">Belum ada riwayat verifikasi.</p>
        </div>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Wilayah</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Tanggal Verifikasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($laporans as $laporan)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <span>{{ $laporan->kategori->ikon }}</span>
                            <span class="font-medium text-gray-800">{{ $laporan->kategori->nama_kategori }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-600">{{ $laporan->wilayah }}</td>
                    <td class="px-5 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $laporan->status === 'diverifikasi' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $laporan->status === 'diverifikasi' ? 'Diverifikasi' : 'Ditolak' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-gray-500">{{ $laporan->verifikasi?->tanggal_verifikasi?->format('d M Y') ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $laporans->links() }}</div>
    @endif
</div>
@endsection
