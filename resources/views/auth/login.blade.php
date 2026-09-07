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
                <p class="text-gray-500 text-sm mt-1">Masuk ke akun SIGAP Anda</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6] transition-all text-sm"
                           placeholder="email@contoh.com">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30 focus:border-[#6C3DE6] transition-all text-sm"
                           placeholder="••••••••">
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
