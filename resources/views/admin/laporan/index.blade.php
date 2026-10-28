@extends('layouts.dashboard')

@section('title', 'Kelola Seluruh Laporan')
@section('page-title', 'Kelola Seluruh Laporan Masuk')

@section('content')
{{-- Header & Export Button --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Daftar Seluruh Laporan Masalah</h2>
        <p class="text-xs text-gray-500">Pantau dan kelola seluruh laporan masuk dari masyarakat kota dan desa secara terpusat.</p>
    </div>
    <div>
        <a href="{{ route('admin.laporan.export', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-sm">
            <span>📊</span> Ekspor CSV / Excel
        </a>
    </div>
</div>

{{-- Filter & Search Form --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-5 mb-6">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Kata Kunci / Judul</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Judul / deskripsi..." 
                       class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Status Penanganan</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="diverifikasi" {{ request('status') == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori Permasalahan</label>
                <select name="kategori_id" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->ikon }} {{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Wilayah</label>
                <select name="wilayah" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-white">
                    <option value="">Semua Wilayah</option>
                    @foreach($wilayahs as $w)
                        <option value="{{ $w->nama }}" {{ request('wilayah') == $w->nama ? 'selected' : '' }}>{{ $w->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2 md:col-span-4 flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                @if(request()->hasAny(['q', 'status', 'kategori_id', 'wilayah']))
                    <a href="{{ route('admin.laporan.index') }}" class="text-xs text-gray-500 hover:text-gray-700">Reset Filter</a>
                @endif
                <button type="submit" class="px-5 py-2 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-dark transition-colors">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Laporan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">ID & Waktu</th>
                        <th class="px-5 py-3.5">Judul & Kategori</th>
                        <th class="px-5 py-3.5">Pelapor</th>
                        <th class="px-5 py-3.5">Wilayah</th>
                        <th class="px-5 py-3.5">Status Penanganan</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($laporans as $item)
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="px-5 py-4 font-mono text-gray-400">
                                #{{ $item->id }}<br>
                                <span class="text-[10px] text-gray-400 font-sans">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-gray-900 text-sm mb-0.5">{{ $item->judul ?? 'Laporan #' . $item->id }}</div>
                                <div class="flex flex-wrap items-center gap-1 mt-1">
                                    <span class="inline-flex items-center gap-1 text-[11px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">
                                        {{ $item->kategori->ikon ?? '' }} {{ $item->kategori->nama_kategori ?? 'Umum' }}
                                    </span>
                                    @if($item->kategori && $item->kategori->dinas_terkait)
                                        <span class="inline-flex items-center gap-0.5 text-[10px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-medium border border-blue-100">
                                            🏛️ {{ $item->kategori->dinas_terkait }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-medium text-gray-900">{{ $item->user->name ?? 'Warga' }}</span><br>
                                <span class="text-[11px] text-gray-400">{{ $item->user->email ?? '-' }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-medium text-gray-800">{{ $item->wilayah }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold
                                    {{ $item->status == 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $item->status == 'diverifikasi' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $item->status == 'diproses' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $item->status == 'selesai' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $item->status == 'ditolak' ? 'bg-rose-100 text-rose-800' : '' }}">
                                    {{ $item->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.laporan.show', $item->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-primary hover:text-white text-gray-700 font-semibold transition-colors">
                                    Kelola / Detail →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                                Belum ada laporan yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $laporans->links() }}
        </div>
    </div>
@endsection
