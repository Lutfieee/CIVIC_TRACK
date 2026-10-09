<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIGAP - Sistem Informasi Geospasial Aman & Peduli Kota Bandung">
    <title>@yield('title', 'SIGAP') — SIGAP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: { DEFAULT: '#6C3DE6', dark: '#5529C5', light: '#8B5CF6' },
                        secondary: { DEFAULT: '#3B82F6', dark: '#2563EB' },
                    }
                }
            }
        }
    </script>
    <style>
        #app-sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(-100%);
            z-index: 9999 !important;
        }
        #app-sidebar.open {
            transform: translateX(0);
        }
        #sidebar-overlay {
            transition: opacity 0.3s ease;
            opacity: 0;
            pointer-events: none;
            z-index: 9990 !important;
        }
        #sidebar-overlay.open {
            opacity: 1;
            pointer-events: all;
        }
        .sidebar-nav-link.active {
            background: linear-gradient(135deg, rgba(108,61,230,0.10), rgba(59,130,246,0.10));
            color: #6C3DE6;
            font-weight: 600;
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-gray-900">

{{-- Mobile Sidebar Overlay (NO BLUR, ONLY FOR MOBILE DISMISS) --}}
<div id="sidebar-overlay"
     class="fixed inset-0 bg-black/10 lg:hidden"
     onclick="closeSidebar()"></div>

{{-- ═══════════════ SIDEBAR ═══════════════ --}}
<aside id="app-sidebar"
       class="fixed top-0 left-0 h-full w-72 bg-white shadow-2xl border-r border-gray-100 flex flex-col">

    {{-- Header --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 flex-shrink-0">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#6C3DE6] to-[#3B82F6] flex items-center justify-center text-white font-bold text-sm shadow-md">S</div>
            <span class="font-bold text-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] bg-clip-text text-transparent">SIGAP</span>
            @auth
                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded bg-purple-50 text-[#6C3DE6] capitalize">{{ auth()->user()->role }}</span>
            @endauth
        </a>
        <button onclick="closeSidebar()"
                class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 flex items-center justify-center transition-colors"
                title="Tutup Menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        @auth
            @if(auth()->user()->isAdmin())
                <p class="px-3 pt-1 pb-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">Menu Admin</p>
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="text-lg">📊</span><span class="font-medium">Panel Admin</span>
                </a>
                <a href="{{ route('admin.laporan.index') }}"
                   class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <span class="text-lg">📁</span><span class="font-medium">Semua Laporan</span>
                </a>
                <a href="{{ route('admin.antrian') }}"
                   class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all {{ request()->routeIs('admin.antrian') ? 'active' : '' }}">
                    <span class="text-lg">⏳</span><span class="font-medium">Antrian Verifikasi</span>
                </a>
                <a href="{{ route('admin.riwayat') }}"
                   class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all {{ request()->routeIs('admin.riwayat') ? 'active' : '' }}">
                    <span class="text-lg">📋</span><span class="font-medium">Riwayat Verifikasi</span>
                </a>
            @else
                <p class="px-3 pt-1 pb-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">Layanan Warga</p>
                <a href="{{ route('warga.laporan.index') }}"
                   class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all {{ request()->routeIs('warga.laporan.index') ? 'active' : '' }}">
                    <span class="text-lg">📋</span><span class="font-medium">Laporan Saya</span>
                </a>
                <a href="{{ route('warga.laporan.create') }}"
                   class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all {{ request()->routeIs('warga.laporan.create') ? 'active' : '' }}">
                    <span class="text-lg">➕</span><span class="font-medium">Buat Laporan</span>
                </a>
            @endif

            <p class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">Fitur Publik</p>
            <a href="{{ route('peta.index') }}"
               class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all {{ request()->routeIs('peta.*') ? 'active' : '' }}">
                <span class="text-lg">🗺️</span><span class="font-medium">Peta Masalah</span>
            </a>
            <a href="{{ route('transportasi.index') }}"
               class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all {{ request()->routeIs('transportasi.*') ? 'active' : '' }}">
                <span class="text-lg">🚌</span><span class="font-medium">Transportasi Umum</span>
            </a>
            <a href="{{ route('informasi-layanan.index') }}"
               class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all {{ request()->routeIs('informasi-layanan.*') ? 'active' : '' }}">
                <span class="text-lg">📖</span><span class="font-medium">Panduan Layanan</span>
            </a>
            <a href="{{ route('home') }}"
               class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all {{ request()->routeIs('home') ? 'active' : '' }}">
                <span class="text-lg">🏠</span><span class="font-medium">Beranda Publik</span>
            </a>
        @else
            <p class="px-3 pt-1 pb-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">Fitur Publik</p>
            <a href="{{ route('peta.index') }}"
               class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all {{ request()->routeIs('peta.*') ? 'active' : '' }}">
                <span class="text-lg">🗺️</span><span class="font-medium">Peta Masalah</span>
            </a>
            <a href="{{ route('transportasi.index') }}"
               class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all {{ request()->routeIs('transportasi.*') ? 'active' : '' }}">
                <span class="text-lg">🚌</span><span class="font-medium">Transportasi Umum</span>
            </a>
            <a href="{{ route('informasi-layanan.index') }}"
               class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all {{ request()->routeIs('informasi-layanan.*') ? 'active' : '' }}">
                <span class="text-lg">📖</span><span class="font-medium">Panduan Layanan</span>
            </a>
            <a href="{{ route('home') }}"
               class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all {{ request()->routeIs('home') ? 'active' : '' }}">
                <span class="text-lg">🏠</span><span class="font-medium">Beranda Publik</span>
            </a>

            <p class="px-3 pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">Bergabung</p>
            <a href="{{ route('login') }}"
               class="sidebar-nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-all">
                <span class="text-lg">🔑</span><span class="font-medium">Masuk</span>
            </a>
            <a href="{{ route('register') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] hover:shadow-md transition-all mt-1">
                <span class="text-lg">✨</span><span>Daftar Sekarang</span>
            </a>
        @endauth
    </nav>

    {{-- Footer user info + logout --}}
    @auth
    <div class="px-4 py-4 border-t border-gray-100 flex-shrink-0">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#6C3DE6] to-[#3B82F6] flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-red-500 hover:bg-red-50 transition-colors font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
    @endauth
</aside>

{{-- ═══════════════ NAVBAR ═══════════════ --}}
<nav class="fixed top-0 inset-x-0 z-[9900] bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-xs">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">

            {{-- Kiri: Hamburger + Logo --}}
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()"
                        class="p-2 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-[#6C3DE6] transition-colors focus:outline-none"
                        title="Buka / Tutup Sidebar"
                        aria-label="Buka menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#6C3DE6] to-[#3B82F6] flex items-center justify-center text-white font-bold text-sm shadow-md">S</div>
                    <span class="font-bold text-lg bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] bg-clip-text text-transparent hidden sm:block">SIGAP</span>
                </a>
            </div>

            {{-- Kanan: Notif + Profil --}}
            <div class="flex items-center gap-2 sm:gap-3">
                @auth
                    {{-- Bell Notifikasi --}}
                    <a href="{{ route('notifikasi.index') }}"
                       class="relative p-2.5 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-[#6C3DE6] transition-colors"
                       title="Notifikasi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @php $nb = auth()->user()->unreadNotifikasisCount(); @endphp
                        @if($nb > 0)
                            <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center shadow">
                                {{ $nb > 9 ? '9+' : $nb }}
                            </span>
                        @endif
                    </a>

                    {{-- Profil Dropdown --}}
                    <div class="relative" id="profile-dropdown-container">
                        <button onclick="toggleProfileDropdown()"
                                type="button"
                                class="flex items-center gap-2 pl-1.5 pr-2.5 py-1.5 rounded-xl hover:bg-gray-100 transition-colors group focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#6C3DE6] to-[#3B82F6] flex items-center justify-center text-white font-bold text-sm shadow group-hover:shadow-md transition-shadow">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <span class="text-sm font-semibold text-gray-700 group-hover:text-[#6C3DE6] transition-colors block max-w-[120px] truncate leading-tight">
                                    {{ explode(' ', auth()->user()->name)[0] }}
                                </span>
                                <span class="text-[10px] text-gray-400 capitalize block leading-none font-medium">
                                    {{ auth()->user()->role }}
                                </span>
                            </div>
                            <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-gray-600 transition-colors hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div id="profile-dropdown"
                             class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                            <div class="px-4 py-2.5 border-b border-gray-100">
                                <p class="text-sm font-bold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                <span class="inline-block mt-1 text-[10px] font-bold px-2 py-0.5 rounded-md {{ auth()->user()->isAdmin() ? 'bg-purple-100 text-[#6C3DE6]' : 'bg-blue-100 text-[#3B82F6]' }}">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                            </div>
                            <a href="{{ route('profil.edit') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-colors">
                                <span>👤</span> Profil Saya
                            </a>
                            <a href="{{ route('notifikasi.index') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-colors">
                                <span>🔔</span> Notifikasi
                                @if($nb > 0)
                                    <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $nb }}</span>
                                @endif
                            </a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                   class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-colors">
                                    <span>📊</span> Panel Admin
                                </a>
                            @else
                                <a href="{{ route('warga.laporan.index') }}"
                                   class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-colors">
                                    <span>📋</span> Laporan Saya
                                </a>
                            @endif
                            <a href="{{ route('home') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6] transition-colors">
                                <span>🏠</span> Beranda SIGAP
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-[#6C3DE6] transition-colors px-3 py-1.5 rounded-xl hover:bg-gray-100">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
@if(session('success') || session('error'))
    <div class="fixed top-20 right-4 z-[9950] max-w-sm" id="flash-msg">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 text-sm">
                <span class="text-green-500 font-bold">✓</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 text-sm">
                <span class="text-red-500 font-bold">✕</span> {{ session('error') }}
            </div>
        @endif
    </div>
    <script>setTimeout(function(){var e=document.getElementById('flash-msg');if(e){e.style.opacity='0';e.style.transition='opacity .4s';setTimeout(function(){e.remove();},400);}},4000);</script>
@endif

<main class="pt-16">
    @yield('content')
</main>

<footer class="bg-gray-900 text-gray-400 py-12 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#6C3DE6] to-[#3B82F6] flex items-center justify-center text-white font-bold text-sm">S</div>
                    <span class="font-bold text-white text-lg">SIGAP</span>
                </div>
                <p class="text-sm leading-relaxed">Sistem Informasi Geospasial Aman & Peduli — platform partisipasi warga untuk kota Bandung yang lebih baik.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Navigasi</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="{{ route('peta.index') }}" class="hover:text-white transition-colors">Peta Kejadian</a></li>
                    <li><a href="{{ route('transportasi.index') }}" class="hover:text-white transition-colors">Transportasi Umum</a></li>
                    <li><a href="{{ route('informasi-layanan.index') }}" class="hover:text-white transition-colors">Panduan Layanan</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Kontak</h4>
                <p class="text-sm">📧 sigap@bandung.go.id</p>
                <p class="text-sm mt-1">🏛️ Pemerintah Kota Bandung</p>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-6 text-center text-xs">
            &copy; {{ date('Y') }} SIGAP — Kota Bandung. Dibuat dengan &#10084;&#65039; untuk warga.
        </div>
    </div>
</footer>

<script>
var sidebar = document.getElementById('app-sidebar');
var overlay = document.getElementById('sidebar-overlay');

function openSidebar() {
    if (!sidebar) return;
    sidebar.classList.add('open');
    if (overlay && window.innerWidth < 1024) overlay.classList.add('open');
    localStorage.setItem('sigap_sidebar_state', 'open');
}

function closeSidebar() {
    if (!sidebar) return;
    sidebar.classList.remove('open');
    if (overlay) overlay.classList.remove('open');
    localStorage.setItem('sigap_sidebar_state', 'closed');
}

function toggleSidebar() {
    if (!sidebar) return;
    if (sidebar.classList.contains('open')) {
        closeSidebar();
    } else {
        openSidebar();
    }
}

function toggleProfileDropdown() {
    var dropdown = document.getElementById('profile-dropdown');
    if (dropdown) dropdown.classList.toggle('hidden');
}

// Restore sidebar state across pages
document.addEventListener('DOMContentLoaded', function() {
    var savedState = localStorage.getItem('sigap_sidebar_state');
    if (savedState === 'open' && window.innerWidth >= 1024) {
        if (sidebar) sidebar.classList.add('open');
    }
});

// Close dropdown on outside click
document.addEventListener('click', function(e) {
    var container = document.getElementById('profile-dropdown-container');
    var dropdown = document.getElementById('profile-dropdown');
    if (container && dropdown && !container.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        var dropdown = document.getElementById('profile-dropdown');
        if (dropdown) dropdown.classList.add('hidden');
        closeSidebar();
    }
});
</script>

@stack('scripts')
</body>
</html>