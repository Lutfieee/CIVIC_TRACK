@extends('layouts.dashboard')

@section('title', 'Notifikasi Laporan')
@section('page-title', 'Notifikasi Laporan')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pemberitahuan & Notifikasi Laporan</h1>
            <p class="text-sm text-gray-500">Informasi riwayat pembaruan status dan tindak lanjut laporan Anda.</p>
        </div>
        @if($notifikasis->where('is_read', false)->count() > 0)
            <form method="POST" action="{{ route('notifikasi.read-all') }}">
                @csrf
                <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs font-semibold text-gray-700 transition-colors">
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <span>✓</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden divide-y divide-gray-100">
        @forelse($notifikasis as $n)
            <div class="p-5 flex items-start justify-between gap-4 {{ $n->is_read ? 'bg-white' : 'bg-indigo-50/40' }} transition-colors">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl {{ $n->is_read ? 'bg-gray-100 text-gray-500' : 'bg-primary text-white' }} flex items-center justify-center text-lg flex-shrink-0 shadow-xs">
                        🔔
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-sm text-gray-900">{{ $n->judul }}</h3>
                            @if(!$n->is_read)
                                <span class="w-2 h-2 rounded-full bg-primary inline-block"></span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ $n->pesan }}</p>
                        <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                            <span>🕒 {{ $n->created_at->diffForHumans() }}</span>
                            @if($n->laporan_id)
                                <span>•</span>
                                <a href="{{ auth()->user()->isAdmin() ? route('admin.laporan.show', $n->laporan_id) : route('warga.laporan.show', $n->laporan_id) }}" class="text-primary hover:underline font-medium">
                                    Lihat Detail Laporan →
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                @if(!$n->is_read)
                    <form method="POST" action="{{ route('notifikasi.read', $n->id) }}">
                        @csrf
                        <button type="submit" title="Tandai dibaca" class="text-xs text-gray-400 hover:text-primary p-1">
                            ✓
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-2xl mx-auto mb-3">📭</div>
                <h4 class="font-bold text-gray-800 text-sm">Belum Ada Notifikasi</h4>
                <p class="text-xs text-gray-500 mt-1">Setiap kali ada pembaruan status laporan, pemberitahuan akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $notifikasis->links() }}
    </div>
</div>
@endsection
