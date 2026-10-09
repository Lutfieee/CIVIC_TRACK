@extends('layouts.dashboard')

@section('title', 'Kelola Data Wilayah')
@section('page-title', 'Kelola Master Wilayah')

@section('content')
<div class="p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Data Master Wilayah Administrasi</h2>
            <p class="text-xs text-gray-500">Kelola cakupan wilayah (Kota/Kabupaten, Kecamatan, Desa, Kelurahan) untuk integrasi pelaporan masalah.</p>
        </div>
        <div>
            <a href="{{ route('admin.wilayah.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary hover:bg-primary-dark text-white text-xs font-bold transition-all shadow-sm">
                <span>➕</span> Tambah Wilayah Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <span>✓</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Nama Wilayah</th>
                        <th class="px-5 py-3.5">Tingkat / Tipe</th>
                        <th class="px-5 py-3.5">Kode Pos</th>
                        <th class="px-5 py-3.5">Deskripsi / Catatan</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($wilayahs as $w)
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="px-5 py-4 font-bold text-gray-900 text-sm">
                                {{ $w->nama }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-gray-100 text-gray-700">
                                    {{ $w->tipe }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-mono text-gray-500">
                                {{ $w->kode_pos ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-gray-500 max-w-sm truncate">
                                {{ $w->deskripsi ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.wilayah.edit', $w->id) }}" class="px-2.5 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.wilayah.destroy', $w->id) }}" class="inline" onsubmit="return confirm('Hapus data wilayah ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada data master wilayah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $wilayahs->links() }}
        </div>
    </div>
</div>
@endsection
