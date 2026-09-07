@extends('layouts.dashboard')

@section('title', 'Laporan Saya')
@section('page-title', 'Laporan Saya')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Laporan Saya</h2>
        <p class="text-sm text-gray-500 mt-0.5">Riwayat semua laporan yang Anda kirimkan</p>
    </div>
    <a href="{{ route('warga.laporan.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all">
        ➕ Buat Laporan
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    @if($laporans->isEmpty())
        <div class="text-center py-16">
            <span class="text-5xl block mb-4">📋</span>
            <h3 class="text-lg font-semibold text-gray-700">Belum Ada Laporan</h3>
            <p class="text-sm text-gray-400 mt-2 mb-6">Anda belum pernah mengirim laporan. Mulai sekarang!</p>
            <a href="{{ route('warga.laporan.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold hover:shadow-lg transition-all">
                Buat Laporan Pertama
            </a>
        </div>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Wilayah</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Keparahan</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($laporans as $laporan)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">{{ $laporan->kategori->ikon }}</span>
                            <span class="font-medium text-gray-800">{{ $laporan->kategori->nama_kategori }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-600">{{ $laporan->wilayah }}</td>
                    <td class="px-5 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $laporan->tingkat_keparahan === 'tinggi' ? 'bg-red-100 text-red-700' : ($laporan->tingkat_keparahan === 'sedang' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                            {{ ucfirst($laporan->tingkat_keparahan) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        @if($laporan->isMenungguHapus())
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                ⏳ Menunggu Verifikasi Hapus
                            </span>
                        @elseif($laporan->status_penghapusan === 'ditolak_hapus')
                            <div class="flex flex-col gap-1">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $laporan->status === 'diverifikasi' ? 'bg-green-100 text-green-700' : ($laporan->status === 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $laporan->status === 'diverifikasi' ? '✓ Terverifikasi' : ($laporan->status === 'ditolak' ? '✕ Ditolak' : '⏳ Pending') }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-xs bg-red-50 text-red-500">✕ Hapus Ditolak Admin</span>
                            </div>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $laporan->status === 'diverifikasi' ? 'bg-green-100 text-green-700' : ($laporan->status === 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ $laporan->status === 'diverifikasi' ? '✓ Terverifikasi' : ($laporan->status === 'ditolak' ? '✕ Ditolak' : '⏳ Pending') }}
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-gray-500">{{ $laporan->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('warga.laporan.show', $laporan) }}" class="text-xs px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">Detail</a>
                            @if($laporan->status === 'pending')
                                <a href="{{ route('warga.laporan.edit', $laporan) }}" class="text-xs px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">Edit</a>
                            @endif
                            {{-- Tombol Hapus / Batalkan --}}
                            @if($laporan->isMenungguHapus())
                                <form method="POST" action="{{ route('warga.laporan.batal-hapus', $laporan) }}">
                                    @csrf
                                    <button type="submit" class="text-xs px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors">Batalkan</button>
                                </form>
                            @elseif($laporan->status_penghapusan !== 'menunggu_verifikasi')
                                <button type="button"
                                        onclick="openDeleteModal({{ $laporan->id }}, '{{ addslashes($laporan->kategori->nama_kategori) }} - {{ addslashes($laporan->wilayah) }}')"
                                        class="text-xs px-2.5 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                    Hapus
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $laporans->links() }}
        </div>
    @endif
</div>

{{-- Modal Permohonan Hapus --}}
<div id="modal-hapus" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-xl">🗑️</div>
                <div>
                    <h3 class="font-bold text-gray-800">Ajukan Penghapusan Laporan</h3>
                    <p id="modal-laporan-info" class="text-xs text-gray-500 mt-0.5"></p>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-4 text-xs text-amber-700">
                ⚠️ Permohonan hapus akan dikirim ke admin untuk diverifikasi terlebih dahulu. Laporan tidak langsung dihapus.
            </div>

            <form id="form-hapus" method="POST" action="">
                @csrf
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alasan Penghapusan <span class="text-red-500">*</span></label>
                <textarea name="alasan_penghapusan" rows="3" required minlength="10" maxlength="500"
                          placeholder="Jelaskan mengapa laporan ini perlu dihapus (min. 10 karakter)..."
                          class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400 resize-none"></textarea>
                <div class="flex gap-3 mt-4">
                    <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 rounded-xl bg-red-500 text-white text-sm font-semibold hover:bg-red-600 hover:shadow-lg transition-all">
                        Kirim Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openDeleteModal(laporanId, laporanInfo) {
    document.getElementById('modal-laporan-info').textContent = laporanInfo;
    document.getElementById('form-hapus').action = `/warga/laporan/${laporanId}/ajukan-hapus`;
    document.getElementById('modal-hapus').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeDeleteModal() {
    document.getElementById('modal-hapus').classList.add('hidden');
    document.body.style.overflow = '';
}
// Tutup modal saat klik backdrop
document.getElementById('modal-hapus').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
@endpush

@endsection
