@extends('layouts.dashboard')

@section('title', 'Manajemen Lokasi Kos')
@section('page-title', 'Manajemen Lokasi Kos')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-gray-500">{{ $lokasis->total() }} lokasi kos terdaftar di Bandung</p>
    </div>
    <a href="{{ route('admin.lokasi.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all">
        ➕ Tambah Lokasi Kos
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    @if($lokasis->isEmpty())
        <div class="text-center py-16">
            <span class="text-5xl block mb-3">🏡</span>
            <p class="font-semibold text-gray-700">Belum ada lokasi kos</p>
            <p class="text-sm text-gray-400 mt-1">Tambahkan lokasi kos pertama Anda sekarang.</p>
        </div>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Kos</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Wilayah / Alamat</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Harga</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Fasilitas / Kategori</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($lokasis as $lokasi)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if($lokasi->foto)
                                <img src="{{ asset('storage/' . $lokasi->foto) }}" class="w-12 h-12 object-cover rounded-xl border border-gray-100 shadow-sm flex-shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl flex-shrink-0">
                                    🏡
                                </div>
                            @endif
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $lokasi->nama }}</h4>
                                <p class="text-xs text-gray-400 mt-0.5">Kontak: {{ $lokasi->kontak ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-medium text-gray-700 text-xs">📍 {{ $lokasi->wilayah }}</p>
                        <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $lokasi->alamat }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <span class="font-semibold text-emerald-600 text-xs">
                            {{ $lokasi->harga_per_bulan ? 'Rp ' . number_format((float)$lokasi->harga_per_bulan, 0, ',', '.') . '/bln' : '-' }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex flex-wrap gap-1 max-w-xs">
                            @forelse($lokasi->kategoris as $k)
                                <span class="px-2 py-0.5 text-[11px] rounded-md font-medium border"
                                      style="background: {{ $k->warna }}10; color: {{ $k->warna }}; border-color: {{ $k->warna }}30">
                                    {{ $k->ikon }} {{ $k->nama_kategori }}
                                </span>
                            @empty
                                <span class="text-xs text-gray-400">-</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $lokasi->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $lokasi->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.lokasi.edit', $lokasi) }}" class="text-xs px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors font-medium">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.lokasi.destroy', $lokasi) }}" onsubmit="return confirm('Hapus data lokasi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs px-2.5 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors font-medium">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $lokasis->links() }}</div>
    @endif
</div>
@endsection
