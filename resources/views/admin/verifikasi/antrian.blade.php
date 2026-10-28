@extends('layouts.dashboard')

@section('title', 'Antrian Verifikasi')
@section('page-title', 'Antrian Verifikasi')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-bold text-gray-800">Laporan Menunggu Verifikasi</h2>
        <span class="text-sm text-gray-500">{{ $laporans->total() }} total</span>
    </div>

    @if($laporans->isEmpty())
        <div class="text-center py-16">
            <p class="font-semibold text-gray-700">Antrian Kosong!</p>
            <p class="text-sm text-gray-400 mt-1">Semua laporan sudah diproses.</p>
        </div>
    @else
        <div class="divide-y divide-gray-50">
            @foreach($laporans as $laporan)
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl" style="background: {{ $laporan->kategori->warna }}20">
                        {{ $laporan->kategori->ikon }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="font-semibold text-gray-800">{{ $laporan->kategori->nama_kategori }}</p>
                            @if($laporan->kategori && $laporan->kategori->dinas_terkait)
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-medium border border-blue-100">
                                    🏛️ {{ $laporan->kategori->dinas_terkait }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500">{{ $laporan->wilayah }}</p>
                        <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $laporan->deskripsi }}</p>
                    </div>
                    <div class="text-right">
                        <span class="block px-2.5 py-1 rounded-full text-xs font-semibold mb-1
                            {{ $laporan->tingkat_keparahan === 'tinggi' ? 'bg-red-100 text-red-700' : ($laporan->tingkat_keparahan === 'sedang' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                            {{ ucfirst($laporan->tingkat_keparahan) }}
                        </span>
                        <p class="text-xs text-gray-400">{{ $laporan->created_at->format('d M Y') }}</p>
                    </div>
                    <a href="{{ route('admin.laporan.show', $laporan) }}"
                       class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        Review
                    </a>
                </div>
            @endforeach
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $laporans->links() }}
        </div>
    @endif
</div>
@endsection
