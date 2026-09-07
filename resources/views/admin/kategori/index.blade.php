@extends('layouts.dashboard')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Form Tambah Kategori --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span>✨</span> Tambah Kategori Baru
        </h3>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-xs px-4 py-3 rounded-xl mb-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.kategori.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Kategori *</label>
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" required placeholder="Contoh: Banjir"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Ikon Terpilih *</label>
                    <input type="text" id="tambah-ikon-input" name="ikon" value="{{ old('ikon', '⚠️') }}" required placeholder="⚠️" maxlength="10"
                           class="w-full px-3.5 py-2.5 text-center text-xl rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Warna Hex *</label>
                    <div class="flex items-center gap-2">
                        <input type="color" id="color-picker-new" value="{{ old('warna', '#6C3DE6') }}"
                               class="w-10 h-10 p-0.5 rounded-xl border border-gray-200 cursor-pointer"
                               oninput="document.getElementById('warna-input-new').value = this.value">
                        <input type="text" id="warna-input-new" name="warna" value="{{ old('warna', '#6C3DE6') }}" required
                               class="w-full px-2.5 py-2.5 font-mono text-xs rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]"
                               oninput="document.getElementById('color-picker-new').value = this.value">
                    </div>
                </div>
            </div>

            {{-- Preset Emoji Picker Grid --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Emoji Cepat:</label>
                <div class="grid grid-cols-6 gap-1.5 p-2 bg-gray-50 rounded-xl border border-gray-100 max-h-36 overflow-y-auto">
                    @foreach(['🚦', '🌊', '🏘️', '🕳️', '🗑️', '🔥', '🌳', '💡', '🚧', '🚗', '🚨', '⚠️', '🌧️', '⚡', '🏥', '🏢', '💧', '🛑', '🚲', '🌋', '💨', '🛡️', '📦', '⚠️'] as $emoji)
                        <button type="button" onclick="selectEmoji('tambah-ikon-input', '{{ $emoji }}')"
                                class="w-9 h-9 text-lg rounded-lg hover:bg-white hover:shadow-sm hover:scale-110 flex items-center justify-center transition-all bg-transparent">
                            {{ $emoji }}
                        </button>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold shadow-md shadow-indigo-200 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                Simpan Kategori
            </button>
        </form>
    </div>

    {{-- Daftar Kategori --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Daftar Kategori</h3>
                <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full font-medium">{{ $kategoris->count() }} kategori</span>
            </div>

            @if($kategoris->isEmpty())
                <div class="text-center py-12">
                    <span class="text-4xl block mb-2">🏷️</span>
                    <p class="text-gray-500 text-sm">Belum ada kategori terdaftar.</p>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($kategoris as $kategori)
                        <div class="p-5 flex items-center justify-between hover:bg-gray-50/70 transition-colors" id="kategori-row-{{ $kategori->id }}">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-black/5"
                                     style="background: {{ $kategori->warna }}18; color: {{ $kategori->warna }}">
                                    {{ $kategori->ikon }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-gray-800 text-base">{{ $kategori->nama_kategori }}</h4>
                                        <span class="inline-block w-3 h-3 rounded-full border border-white shadow-sm" style="background: {{ $kategori->warna }}"></span>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $kategori->laporans_count }} laporan terkait · Kode Warna: <code class="font-mono text-gray-600">{{ $kategori->warna }}</code></p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button onclick="openEditModal({{ $kategori->id }}, '{{ addslashes($kategori->nama_kategori) }}', '{{ $kategori->ikon }}', '{{ $kategori->warna }}')"
                                        class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('admin.kategori.destroy', $kategori) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" {{ $kategori->laporans_count > 0 ? 'disabled' : '' }}
                                            class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                                            title="{{ $kategori->laporans_count > 0 ? 'Tidak dapat dihapus karena memiliki laporan' : 'Hapus Kategori' }}">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="edit-modal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 text-lg">Edit Kategori</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">✕</button>
        </div>

        <form id="edit-form" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Nama Kategori *</label>
                <input type="text" id="edit-nama" name="nama_kategori" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Ikon Terpilih *</label>
                    <input type="text" id="edit-ikon" name="ikon" required maxlength="10"
                           class="w-full px-3.5 py-2.5 text-center text-xl rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Warna Hex *</label>
                    <div class="flex items-center gap-2">
                        <input type="color" id="edit-color-picker"
                               class="w-10 h-10 p-0.5 rounded-xl border border-gray-200 cursor-pointer"
                               oninput="document.getElementById('edit-warna').value = this.value">
                        <input type="text" id="edit-warna" name="warna" required
                               class="w-full px-2.5 py-2.5 font-mono text-xs rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]"
                               oninput="document.getElementById('edit-color-picker').value = this.value">
                    </div>
                </div>
            </div>

            {{-- Preset Emoji Picker Grid for Modal --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Emoji Cepat:</label>
                <div class="grid grid-cols-6 gap-1.5 p-2 bg-gray-50 rounded-xl border border-gray-100 max-h-36 overflow-y-auto">
                    @foreach(['🚦', '🌊', '🏘️', '🕳️', '🗑️', '🔥', '🌳', '💡', '🚧', '🚗', '🚨', '⚠️', '🌧️', '⚡', '🏥', '🏢', '💧', '🛑', '🚲', '🌋', '💨', '🛡️', '📦', '⚠️'] as $emoji)
                        <button type="button" onclick="selectEmoji('edit-ikon', '{{ $emoji }}')"
                                class="w-9 h-9 text-lg rounded-lg hover:bg-white hover:shadow-sm hover:scale-110 flex items-center justify-center transition-all bg-transparent">
                            {{ $emoji }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold hover:shadow-md transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectEmoji(inputId, emoji) {
    const input = document.getElementById(inputId);
    if (input) {
        input.value = emoji;
        input.classList.add('ring-2', 'ring-[#6C3DE6]');
        setTimeout(() => input.classList.remove('ring-2', 'ring-[#6C3DE6]'), 600);
    }
}

function openEditModal(id, nama, ikon, warna) {
    const modal = document.getElementById('edit-modal');
    const form = document.getElementById('edit-form');
    form.action = `/admin/kategori/${id}`;
    document.getElementById('edit-nama').value = nama;
    document.getElementById('edit-ikon').value = ikon;
    document.getElementById('edit-warna').value = warna;
    document.getElementById('edit-color-picker').value = warna;
    modal.classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
}
</script>
@endpush
