@extends('layouts.app')

@section('title', 'Pemantauan Transportasi Umum')

@section('content')
<div class="bg-gradient-to-b from-indigo-50/60 to-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="text-center max-w-3xl mx-auto mb-10">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-100/80 text-indigo-700 text-xs font-semibold mb-3">
                🚌 Pemantauan Transportasi Terpadu
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                Informasi & Pemantauan Transportasi Umum
            </h1>
            <p class="mt-3 text-base text-gray-600">
                Akses informasi rute, jadwal operasional, titik pemberhentian, tarif, serta kondisi operasional moda transportasi kota dan desa secara terpusat.
            </p>
        </div>

        {{-- Statistik Operasional --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl font-bold">🚍</div>
                <div>
                    <div class="text-2xl font-black text-gray-900">{{ $stats['total'] }}</div>
                    <div class="text-xs font-medium text-gray-500">Total Moda & Trayek</div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl font-bold">✅</div>
                <div>
                    <div class="text-2xl font-black text-emerald-600">{{ $stats['normal'] }}</div>
                    <div class="text-xs font-medium text-gray-500">Beroperasi Normal</div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl font-bold">⚠️</div>
                <div>
                    <div class="text-2xl font-black text-amber-600">{{ $stats['terkendala'] }}</div>
                    <div class="text-xs font-medium text-gray-500">Terkendala / Padat</div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl font-bold">🛠️</div>
                <div>
                    <div class="text-2xl font-black text-blue-600">{{ $stats['perbaikan'] }}</div>
                    <div class="text-xs font-medium text-gray-500">Pengalihan / Perbaikan</div>
                </div>
            </div>
        </div>

        {{-- Form Pencarian Rute & Filter (FR-22) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-5 mb-8">
            <form method="GET" action="{{ route('transportasi.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Cari Asal / Tujuan / Moda</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">🔍</span>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Contoh: Cicaheum, Leuwipanjang, Alun-alun..." 
                               class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Jenis Moda</label>
                    <select name="tipe" class="w-full py-2.5 px-3 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-white">
                        <option value="">Semua Jenis Moda</option>
                        <option value="Bus" {{ request('tipe') == 'Bus' ? 'selected' : '' }}>Bus Kota (TMB / TMP)</option>
                        <option value="Angkot" {{ request('tipe') == 'Angkot' ? 'selected' : '' }}>Angkutan Kota (Angkot)</option>
                        <option value="Kereta" {{ request('tipe') == 'Kereta' ? 'selected' : '' }}>Kereta / LRT / Feeder</option>
                        <option value="Feeder" {{ request('tipe') == 'Feeder' ? 'selected' : '' }}>Feeder Khusus</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Status Kondisi</label>
                    <select name="status" class="w-full py-2.5 px-3 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-white">
                        <option value="">Semua Status</option>
                        <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="terkendala" {{ request('status') == 'terkendala' ? 'selected' : '' }}>Terkendala</option>
                        <option value="perbaikan" {{ request('status') == 'perbaikan' ? 'selected' : '' }}>Pengalihan / Perbaikan</option>
                    </select>
                </div>
                <div class="md:col-span-4 flex items-center justify-end gap-3 pt-2">
                    @if(request()->hasAny(['q', 'tipe', 'status']))
                        <a href="{{ route('transportasi.index') }}" class="text-xs text-gray-500 hover:text-gray-700">Reset Filter</a>
                    @endif
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary-dark transition-colors shadow-sm">
                        Cari Informasi Rute
                    </button>
                </div>
            </form>
        </div>

        {{-- Daftar Moda & Rute Transportasi (FR-21, FR-23, FR-26) --}}
        @if($transportasis->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">🚏</div>
                <h3 class="text-lg font-bold text-gray-800">Tidak ada data rute transportasi ditemukan</h3>
                <p class="text-sm text-gray-500 mt-1">Coba gunakan kata kunci asal, tujuan, atau filter yang berbeda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($transportasis as $t)
                    <div class="bg-white rounded-2xl border border-gray-200/90 shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col justify-between">
                        <div>
                            {{-- Header Card --}}
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 mb-1">
                                        {{ $t->tipe }} • {{ $t->kode_trayek }}
                                    </span>
                                    <h3 class="font-bold text-lg text-gray-900">{{ $t->nama_moda }}</h3>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $t->status_operasional == 'normal' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $t->status_operasional == 'terkendala' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $t->status_operasional == 'perbaikan' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $t->status_operasional == 'tidak_beroperasi' ? 'bg-rose-100 text-rose-700' : '' }}">
                                    {{ ucfirst($t->status_operasional) }}
                                </span>
                            </div>

                            {{-- Rute Asal - Tujuan --}}
                            <div class="bg-gray-50 rounded-xl p-3 mb-4 border border-gray-100">
                                <div class="flex items-center gap-2 text-xs font-medium text-gray-500">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span class="truncate">Asal: <strong class="text-gray-800">{{ $t->rute_asal }}</strong></span>
                                </div>
                                <div class="w-px h-3 bg-gray-300 ml-1 my-0.5"></div>
                                <div class="flex items-center gap-2 text-xs font-medium text-gray-500">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span class="truncate">Tujuan: <strong class="text-gray-800">{{ $t->rute_tujuan }}</strong></span>
                                </div>
                            </div>

                            {{-- Keterangan Operasional --}}
                            @if($t->keterangan_operasional)
                                <div class="text-xs text-gray-600 mb-4 bg-amber-50/60 border border-amber-200/60 rounded-lg p-2.5">
                                    <span class="font-semibold text-amber-800">Kondisi Terkini:</span> {{ $t->keterangan_operasional }}
                                </div>
                            @endif

                            {{-- Info Detail: Jadwal & Tarif --}}
                            <div class="grid grid-cols-2 gap-2 text-xs mb-4">
                                <div class="p-2 rounded-lg bg-gray-50">
                                    <span class="text-gray-400 block">Jadwal</span>
                                    <span class="font-semibold text-gray-800">{{ $t->jadwal_operasional }}</span>
                                </div>
                                <div class="p-2 rounded-lg bg-gray-50">
                                    <span class="text-gray-400 block">Tarif</span>
                                    <span class="font-semibold text-gray-800">{{ $t->tarif }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Action: Detail Rute (FR-23) --}}
                        <div class="border-t border-gray-100 pt-4 mt-2">
                            <a href="{{ route('transportasi.show', $t->id) }}" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-gray-100 hover:bg-primary hover:text-white text-gray-700 text-xs font-semibold transition-colors">
                                <span>Lihat Titik Pemberhentian & Halte</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
