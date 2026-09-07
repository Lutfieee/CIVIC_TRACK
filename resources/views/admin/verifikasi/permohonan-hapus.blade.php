@extends('layouts.dashboard')

@section('title', 'Permohonan Hapus Laporan')
@section('page-title', 'Permohonan Hapus Laporan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-gray-800">Permohonan Penghapusan Laporan</h2>
            <p class="text-xs text-gray-500 mt-0.5">Laporan yang diajukan warga untuk dihapus, menunggu verifikasi Anda.</p>
        </div>
        <span class="text-sm text-gray-500">{{ $laporans->total() }} permohonan</span>
    </div>

    @if(session('success'))
        <div class="mx-6 mt-4 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-center gap-2">
            <span>✓</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mx-6 mt-4 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($laporans->isEmpty())
        <div class="text-center py-16">
            <span class="text-5xl block mb-4">✅</span>
            <p class="font-semibold text-gray-700">Tidak Ada Permohonan Hapus</p>
            <p class="text-sm text-gray-400 mt-1">Semua permohonan penghapusan sudah diproses.</p>
        </div>
    @else
        <div class="divide-y divide-gray-50">
            @foreach($laporans as $laporan)
                <div class="px-6 py-5 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start gap-4">
                        {{-- Ikon Kategori --}}
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0"
                             style="background: {{ $laporan->kategori->warna }}20">
                            {{ $laporan->kategori->ikon }}
                        </div>

                        {{-- Info Laporan --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $laporan->kategori->nama_kategori }}</p>
                                    <p class="text-sm text-gray-500">📍 {{ $laporan->wilayah }} · oleh <strong>{{ $laporan->user->name }}</strong></p>
                                    <p class="text-xs text-gray-400 mt-0.5">Laporan dibuat: {{ $laporan->created_at->format('d M Y') }}</p>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $laporan->tingkat_keparahan === 'tinggi' ? 'bg-red-100 text-red-700' : ($laporan->tingkat_keparahan === 'sedang' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                        {{ ucfirst($laporan->tingkat_keparahan) }}
                                    </span>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $laporan->status === 'diverifikasi' ? 'bg-green-100 text-green-700' : ($laporan->status === 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ $laporan->status === 'diverifikasi' ? '✓ Terverifikasi' : ($laporan->status === 'ditolak' ? '✕ Ditolak' : '⏳ Pending') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Alasan Penghapusan --}}
                            <div class="bg-red-50 border border-red-100 rounded-xl p-3 mb-3">
                                <p class="text-xs font-semibold text-red-700 mb-1">🗑️ Alasan Penghapusan dari Warga:</p>
                                <p class="text-sm text-red-600">"{{ $laporan->alasan_penghapusan }}"</p>
                                <p class="text-xs text-red-400 mt-1">Diajukan: {{ $laporan->tanggal_pengajuan_hapus->format('d M Y, H:i') }} WIB</p>
                            </div>

                            {{-- Cuplikan Deskripsi --}}
                            <p class="text-xs text-gray-400 mb-3 line-clamp-2">{{ $laporan->deskripsi }}</p>

                            {{-- Tombol Aksi --}}
                            <div class="flex items-center gap-3 flex-wrap">
                                <a href="{{ route('admin.laporan.show', $laporan) }}"
                                   class="text-xs px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                                    Lihat Detail Laporan
                                </a>

                                {{-- Approve Delete --}}
                                <form id="form-approve-{{ $laporan->id }}" method="POST" action="{{ route('admin.laporan.approve-delete', $laporan) }}">
                                    @csrf
                                    <button type="button"
                                            onclick="showApproveModal('form-approve-{{ $laporan->id }}', '{{ addslashes($laporan->kategori->nama_kategori) }}', '{{ addslashes($laporan->user->name) }}')"
                                            class="text-xs px-3 py-1.5 rounded-lg bg-red-500 text-white hover:bg-red-600 hover:shadow-md transition-all font-semibold">
                                        🗑️ Setujui Hapus
                                    </button>
                                </form>

                                {{-- Reject Delete --}}
                                <form id="form-reject-{{ $laporan->id }}" method="POST" action="{{ route('admin.laporan.reject-delete', $laporan) }}">
                                    @csrf
                                    <button type="button"
                                            onclick="showRejectModal('form-reject-{{ $laporan->id }}', '{{ addslashes($laporan->kategori->nama_kategori) }}', '{{ addslashes($laporan->user->name) }}')"
                                            class="text-xs px-3 py-1.5 rounded-lg border-2 border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors font-semibold">
                                        ✕ Tolak Permohonan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $laporans->links() }}
        </div>
    @endif
</div>

{{-- Modal Konfirmasi Setujui Hapus --}}
<div id="modal-approve-delete" class="fixed inset-0 z-[9999] flex items-center justify-center hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="hideApproveModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6 transform transition-all scale-95 opacity-0" id="modal-approve-box">
        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 text-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800 text-center mb-1">Setujui Hapus Laporan?</h3>
        <p class="text-sm text-gray-500 text-center mb-3">Laporan ini akan <strong>dihapus permanen</strong> dari sistem. Tindakan ini tidak dapat dibatalkan.</p>
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-2.5 mb-5 text-center">
            <p class="text-xs text-gray-500 font-medium">Laporan: <span id="approve-target-info" class="text-gray-800 font-semibold">-</span></p>
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="hideApproveModal()" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button type="button" onclick="executeApproveDelete()" class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold hover:shadow-lg transition-all">
                Ya, Hapus Permanen
            </button>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Tolak Permohonan --}}
<div id="modal-reject-delete" class="fixed inset-0 z-[9999] flex items-center justify-center hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="hideRejectModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6 transform transition-all scale-95 opacity-0" id="modal-reject-box">
        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-orange-100 text-orange-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800 text-center mb-1">Tolak Permohonan Hapus?</h3>
        <p class="text-sm text-gray-500 text-center mb-3">Permohonan penghapusan dari warga akan ditolak dan status laporan akan tetap aktif.</p>
        
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-2.5 mb-4 text-center">
            <p class="text-xs text-gray-500 font-medium">Laporan: <span id="reject-target-info" class="text-gray-800 font-semibold">-</span></p>
        </div>

        {{-- Input Alasan Penolakan --}}
        <div class="mb-5 text-left">
            <label for="reject-alasan-input" class="block text-xs font-semibold text-gray-700 mb-1.5">
                Alasan Penolakan <span class="text-red-500">*</span>
            </label>
            <textarea id="reject-alasan-input" rows="3"
                      placeholder="Contoh: Laporan masih relevan dan sedang dalam penanganan petugas..."
                      class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 resize-none text-gray-800 placeholder-gray-400"></textarea>
            <p id="reject-alasan-error" class="text-xs text-red-500 mt-1 hidden font-medium">⚠️ Alasan penolakan wajib diisi.</p>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="hideRejectModal()" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button type="button" onclick="executeRejectDelete()" class="flex-1 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold hover:shadow-lg transition-all">
                Ya, Tolak Permohonan
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let targetApproveForm = null;
let targetRejectForm  = null;

function showApproveModal(formId, kategoriNama, pembuat) {
    targetApproveForm = document.getElementById(formId);
    document.getElementById('approve-target-info').textContent = `${kategoriNama} (${pembuat})`;
    const modal = document.getElementById('modal-approve-delete');
    const box   = document.getElementById('modal-approve-box');
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        box.classList.remove('scale-95', 'opacity-0');
        box.classList.add('scale-100', 'opacity-100');
    });
}

function hideApproveModal() {
    const modal = document.getElementById('modal-approve-delete');
    const box   = document.getElementById('modal-approve-box');
    box.classList.remove('scale-100', 'opacity-100');
    box.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        targetApproveForm = null;
    }, 200);
}

function executeApproveDelete() {
    if (targetApproveForm) {
        const btn = document.querySelector('#modal-approve-box button[onclick="executeApproveDelete()"]');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Menghapus...';
        }
        targetApproveForm.submit();
    }
}

function showRejectModal(formId, kategoriNama, pembuat) {
    targetRejectForm = document.getElementById(formId);
    document.getElementById('reject-target-info').textContent = `${kategoriNama} (${pembuat})`;
    
    // Reset input alasan & error
    const inputAlasan = document.getElementById('reject-alasan-input');
    const errorMsg    = document.getElementById('reject-alasan-error');
    if (inputAlasan) {
        inputAlasan.value = '';
        inputAlasan.classList.remove('border-red-500');
    }
    if (errorMsg) {
        errorMsg.classList.add('hidden');
    }

    const modal = document.getElementById('modal-reject-delete');
    const box   = document.getElementById('modal-reject-box');
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        box.classList.remove('scale-95', 'opacity-0');
        box.classList.add('scale-100', 'opacity-100');
        if (inputAlasan) inputAlasan.focus();
    });
}

function hideRejectModal() {
    const modal = document.getElementById('modal-reject-delete');
    const box   = document.getElementById('modal-reject-box');
    box.classList.remove('scale-100', 'opacity-100');
    box.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        targetRejectForm = null;
    }, 200);
}

function executeRejectDelete() {
    const inputAlasan = document.getElementById('reject-alasan-input');
    const errorMsg    = document.getElementById('reject-alasan-error');
    const alasan      = inputAlasan?.value?.trim();

    if (!alasan) {
        if (errorMsg) errorMsg.classList.remove('hidden');
        if (inputAlasan) {
            inputAlasan.classList.add('border-red-500');
            inputAlasan.focus();
        }
        return;
    }

    if (targetRejectForm) {
        // Masukkan atau update input hidden alasan_penolakan_hapus di target form
        let hiddenInput = targetRejectForm.querySelector('input[name="alasan_penolakan_hapus"]');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'alasan_penolakan_hapus';
            targetRejectForm.appendChild(hiddenInput);
        }
        hiddenInput.value = alasan;

        const btn = document.querySelector('#modal-reject-box button[onclick="executeRejectDelete()"]');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Memproses...';
        }
        targetRejectForm.submit();
    }
}

// Tutup modal dengan tombol Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        hideApproveModal();
        hideRejectModal();
    }
});
</script>
@endpush
