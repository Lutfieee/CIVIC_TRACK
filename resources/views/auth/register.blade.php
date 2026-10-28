@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#F8F8FF] via-white to-[#EEF2FF] flex items-center justify-center px-4 py-20">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#6C3DE6] to-[#3B82F6] flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4 shadow-lg shadow-[#6C3DE6]/20">S</div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Akun</h1>
                <p class="text-gray-500 text-sm mt-1">Bergabung sebagai warga CIVIC TRACK</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3.5 rounded-2xl mb-6 shadow-sm">
                    <div class="flex items-center gap-2 font-semibold text-red-700 mb-2">
                        <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Periksa kembali data Anda:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-xs text-red-700 pl-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-red-400 bg-red-50/40 text-red-900 focus:ring-red-200 focus:border-red-500' : 'border-gray-200 bg-gray-50 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]' }} focus:outline-none focus:ring-2 transition-all text-sm"
                           placeholder="Nama Anda">
                    @error('name')
                        <p class="text-xs text-red-600 mt-1.5 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50/40 text-red-900 focus:ring-red-200 focus:border-red-500' : 'border-gray-200 bg-gray-50 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]' }} focus:outline-none focus:ring-2 transition-all text-sm"
                           placeholder="nama@email.com">
                    @error('email')
                        <p class="text-xs text-red-600 mt-1.5 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('password') ? 'border-red-400 bg-red-50/40 text-red-900 focus:ring-red-200 focus:border-red-500' : 'border-gray-200 bg-gray-50 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]' }} focus:outline-none focus:ring-2 transition-all text-sm"
                           placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="text-xs text-red-600 mt-1.5 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('password_confirmation') ? 'border-red-400 bg-red-50/40 text-red-900 focus:ring-red-200 focus:border-red-500' : 'border-gray-200 bg-gray-50 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]' }} focus:outline-none focus:ring-2 transition-all text-sm"
                           placeholder="Ulangi password di atas">
                    @error('password_confirmation')
                        <p class="text-xs text-red-600 mt-1.5 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white font-semibold hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 text-sm shadow-md shadow-[#6C3DE6]/20">
                    Daftar Sekarang
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-[#6C3DE6] font-semibold hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
