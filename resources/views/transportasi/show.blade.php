@extends('layouts.app')

@section('title', 'Detail Rute: ' . $transportasi->nama_moda)

@section('content')
<div class="py-10 bg-gray-50/60 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="mb-6">
            <a href="{{ route('transportasi.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-primary transition-colors">
                <span>←</span> Kembali ke Daftar Transportasi
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            {{-- Header --}}
            <div class="p-6 sm:p-8 bg-gradient-to-r from-indigo-700 via-primary to-blue-600 text-white">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-white/20 backdrop-blur-md mb-2">
                            {{ $transportasi->tipe }} • {{ $transportasi->kode_trayek }}
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-extrabold">{{ $transportasi->nama_moda }}</h1>
                        <p class="text-white/80 text-sm mt-1">Rute: {{ $transportasi->rute_asal }} ⇄ {{ $transportasi->rute_tujuan }}</p>
                    </div>
                    <div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-white text-gray-900 shadow">
                            Status: {{ ucfirst($transportasi->status_operasional) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-8">
                {{-- Quick specs --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <span class="text-xs text-gray-400 block font-medium">Jadwal Operasional</span>
                        <span class="text-sm font-bold text-gray-800 mt-1 block">{{ $transportasi->jadwal_operasional }}</span>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <span class="text-xs text-gray-400 block font-medium">Tarif Resmi</span>
                        <span class="text-sm font-bold text-gray-800 mt-1 block">{{ $transportasi->tarif }}</span>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <span class="text-xs text-gray-400 block font-medium">Status Operasional</span>
                        <span class="text-sm font-bold text-emerald-600 mt-1 block">{{ ucfirst($transportasi->status_operasional) }}</span>
                    </div>
                </div>

                {{-- Status / Keterangan Operasional --}}
                @if($transportasi->keterangan_operasional)
                    <div class="bg-blue-50/70 border border-blue-200 rounded-xl p-4 text-sm text-blue-900">
                        <div class="font-bold flex items-center gap-1.5 mb-1">
                            <span>ℹ️</span> Informasi Operasional Terkini:
                        </div>
                        <p class="text-blue-800 leading-relaxed">{{ $transportasi->keterangan_operasional }}</p>
                    </div>
                @endif

                {{-- Titik Pemberhentian & Halte (FR-23) --}}
                <div>
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span>🚏</span> Titik Pemberhentian & Urutan Halte
                    </h2>
                    @php
                        $halteList = array_map('trim', explode('-', $transportasi->titik_pemberhentian));
                        if (count($halteList) <= 1) {
                            $halteList = array_map('trim', explode(',', $transportasi->titik_pemberhentian));
                        }
                    @endphp
                    <div class="relative pl-6 space-y-4 before:absolute before:left-2.5 before:top-3 before:bottom-3 before:w-0.5 before:bg-indigo-200">
                        @foreach($halteList as $idx => $halte)
                            <div class="relative flex items-center gap-3">
                                <div class="absolute -left-6 w-5 h-5 rounded-full border-2 border-primary bg-white flex items-center justify-center">
                                    <div class="w-2 h-2 rounded-full {{ $loop->first ? 'bg-emerald-500' : ($loop->last ? 'bg-rose-500' : 'bg-primary') }}"></div>
                                </div>
                                <div class="bg-gray-50 hover:bg-indigo-50/50 transition-colors border border-gray-200/80 rounded-xl px-4 py-2.5 flex-1 flex items-center justify-between">
                                    <span class="font-medium text-sm text-gray-800">{{ $halte }}</span>
                                    <span class="text-xs text-gray-400 font-mono">Halte #{{ $idx + 1 }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6 flex items-center justify-between">
                    <p class="text-xs text-gray-400">Data terintegrasi Dasbor Pemantauan Transportasi Umum SIGAP.</p>
                    <a href="{{ route('transportasi.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-xs font-semibold text-gray-700 transition-colors">
                        Kembali ke Rute Lain
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
