@extends('layouts.dashboard')

@section('title', 'Manajemen Profil')
@section('page-title', 'Manajemen Profil')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pengaturan Profil Pengguna</h1>
        <p class="text-sm text-gray-500">Kelola informasi identitas akun, email, dan keamanan kata sandi Anda.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <span>✓</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <form method="POST" action="{{ route('profil.update') }}" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-4 pb-6 border-b border-gray-100">
                <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-primary to-secondary text-white font-extrabold text-2xl flex items-center justify-center shadow">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ $user->name }}</h2>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->isAdmin() ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            Peran: {{ ucfirst($user->role) }}
                        </span>
                        <span class="text-xs text-gray-400">• Terdaftar sejak {{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 mb-1">Ubah Kata Sandi (Opsional)</h3>
                <p class="text-xs text-gray-500 mb-4">Kosongkan kolom berikut jika tidak ingin mengubah kata sandi Anda.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" placeholder="Masukkan password lama"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                        @error('current_password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kata Sandi Baru</label>
                            <input type="password" name="password" placeholder="Minimal 8 karakter"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex items-center justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dark text-white text-sm font-bold transition-all shadow-sm">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
