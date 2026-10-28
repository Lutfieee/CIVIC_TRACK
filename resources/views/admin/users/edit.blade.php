@extends('layouts.dashboard')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-6">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password (kosongkan jika tidak diubah)</label>
                <input type="password" name="password"
                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Role / Aktor *</label>
                <select name="role" id="role-select" required onchange="toggleInstansiField(this.value)"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6] bg-white">
                    <option value="warga" {{ old('role', $user->role) === 'warga' ? 'selected' : '' }}>Warga</option>
                    <option value="dinas" {{ old('role', $user->role) === 'dinas' ? 'selected' : '' }}>Petugas Dinas</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin / Pemerintah Pusat</option>
                </select>
            </div>
            <div id="instansi-group" class="{{ old('role', $user->role) === 'dinas' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Instansi Dinas Terkait</label>
                <select name="instansi" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6] bg-white">
                    <option value="">-- Pilih Dinas Terkait --</option>
                    @foreach(\App\Models\Kategori::DINAS_LIST as $dinas)
                        <option value="{{ $dinas }}" {{ old('instansi', $user->instansi) === $dinas ? 'selected' : '' }}>{{ $dinas }}</option>
                    @endforeach
                </select>
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active ? '1' : '') === '1' ? 'checked' : '' }} class="rounded border-gray-300 text-[#6C3DE6]">
                <span class="text-sm text-gray-700">Akun Aktif</span>
            </label>
            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.users.index') }}" class="flex-1 py-3 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium text-center hover:bg-gray-50">Batal</a>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold hover:shadow-lg transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleInstansiField(role) {
    const group = document.getElementById('instansi-group');
    if (role === 'dinas') {
        group.classList.remove('hidden');
    } else {
        group.classList.add('hidden');
    }
}
</script>
@endpush
