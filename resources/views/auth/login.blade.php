@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#F8F8FF] via-white to-[#EEF2FF] flex items-center justify-center px-4 py-20">
    <div class="w-full max-w-md">
        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#6C3DE6] to-[#3B82F6] flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">S</div>
                <h1 class="text-2xl font-bold text-gray-900">Selamat Datang</h1>
                <p class="text-gray-500 text-sm mt-1">Masuk ke akun CIVIC TRACK Anda</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3.5 rounded-2xl mb-6 shadow-sm flex items-start gap-2.5">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-semibold text-red-700">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50/40 text-red-900 focus:ring-red-200 focus:border-red-500' : 'border-gray-200 bg-gray-50 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6]' }} focus:outline-none focus:ring-2 transition-all text-sm"
                           placeholder="email@contoh.com">
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
                           placeholder="••••••••">
                    @error('password')
                        <p class="text-xs text-red-600 mt-1.5 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#6C3DE6]">
                        Ingat saya
                    </label>
                </div>
                <button type="submit"
                        class="w-full py-3 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white font-semibold hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 text-sm">
                    Masuk
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-[#6C3DE6] font-semibold hover:underline">Daftar sekarang</a>
            </p>
        </div>
    </div>
</div>
@endsection
