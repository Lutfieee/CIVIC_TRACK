@extends('layouts.dashboard')

@section('title', 'Kelola Transportasi Umum')
@section('page-title', 'Kelola Transportasi Umum')

@section('content')
<div class="p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Data Master Transportasi Umum</h2>
            <p class="text-xs text-gray-500">Kelola armada, rute, jadwal operasional, dan status kondisi operasional transportasi umum.</p>
        </div>
        <div>
            <a href="{{ route('admin.transportasi.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary hover:bg-primary-dark text-white text-xs font-bold transition-all shadow-sm">
                <span>➕</span> Tambah Moda / Rute Baru
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
                        <th class="px-5 py-3.5">Moda & Trayek</th>
                        <th class="px-5 py-3.5">Rute Perjalanan</th>
                        <th class="px-5 py-3.5">Jadwal & Tarif</th>
                        <th class="px-5 py-3.5">Status Operasional</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transportasis as $t)
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-bold text-gray-900 text-sm mb-0.5">{{ $t->nama_moda }}</div>
                                <span class="inline-block px-2 py-0.5 rounded-md text-[11px] font-semibold bg-indigo-50 text-indigo-700">
                                    {{ $t->tipe }} • {{ $t->kode_trayek }}
                                </span>
                            </td>
                            <td class="px-5 py-4 max-w-xs">
                                <div class="font-medium text-gray-800">{{ $t->rute_asal }} ⇄ {{ $t->rute_tujuan }}</div>
                                <div class="text-[11px] text-gray-400 truncate mt-0.5" title="{{ $t->titik_pemberhentian }}">
                                    Halte: {{ $t->titik_pemberhentian }}
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-medium text-gray-800">{{ $t->jadwal_operasional }}</div>
                                <div class="text-[11px] text-emerald-600 font-semibold">{{ $t->tarif }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold
                                    {{ $t->status_operasional == 'normal' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $t->status_operasional == 'terkendala' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $t->status_operasional == 'perbaikan' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $t->status_operasional == 'tidak_beroperasi' ? 'bg-rose-100 text-rose-800' : '' }}">
                                    {{ ucfirst($t->status_operasional) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.transportasi.edit', $t->id) }}" class="px-2.5 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.transportasi.destroy', $t->id) }}" class="inline" onsubmit="return confirm('Hapus data transportasi ini?')">
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
                            <td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada data transportasi umum.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $transportasis->links() }}
        </div>
    </div>
</div>
@endsection
