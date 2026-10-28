<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — CIVIC TRACK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
        #dashboard-sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @media (max-width: 1023px) {
            #dashboard-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 9999 !important;
                transform: translateX(-100%);
            }
            #dashboard-sidebar.sidebar-open {
                transform: translateX(0);
            }
        }
        @media (min-width: 1024px) {
            #dashboard-sidebar.sidebar-collapsed {
                margin-left: -16rem; /* -w-64 */
            }
        }
        #sidebar-backdrop {
            transition: opacity 0.3s ease;
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

{{-- Mobile Sidebar Backdrop (NO BLUR, ONLY FOR MOBILE DISMISS) --}}
<div id="sidebar-backdrop"
     class="fixed inset-0 z-[9990] bg-black/10 lg:hidden hidden"
     onclick="toggleSidebar()"></div>

<div class="flex h-screen overflow-hidden">

    {{-- ═══════════════ SIDEBAR (WHITE THEME) ═══════════════ --}}
    <aside id="dashboard-sidebar"
           class="w-64 bg-white shadow-2xl lg:shadow-none border-r border-gray-100 text-gray-800 flex flex-col flex-shrink-0 z-30">

        {{-- Sidebar Header --}}
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#6C3DE6] to-[#3B82F6] flex items-center justify-center text-white font-bold text-sm shadow-md">
                    S
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-bold text-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] bg-clip-text text-transparent">CIVIC TRACK</span>
                    <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded bg-purple-50 text-[#6C3DE6] capitalize">{{ auth()->user()->role }}</span>
                </div>
            </a>
            {{-- Mobile Close Button --}}
            <button onclick="toggleSidebar()"
                    class="lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Sidebar Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @if(auth()->user()->isAdminOrDinas())
                {{-- 1. Menu Admin / Dinas --}}
                <div class="pt-1 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                    {{ auth()->user()->isDinas() ? 'Menu Petugas Dinas' : 'Menu Admin' }}
                </div>
                @if(auth()->user()->isDinas() && auth()->user()->instansi)
                    <div class="mx-3 mb-2 px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-100 text-[11px] font-semibold text-blue-700 leading-tight">
                        🏛️ {{ auth()->user()->instansi }}
                    </div>
                @endif
                <x-sidebar-link href="{{ route('admin.dashboard') }}" icon="" label="Dashboard" :active="request()->routeIs('admin.dashboard')" />
                <x-sidebar-link href="{{ route('admin.laporan.index') }}" icon="" label="Semua Laporan" :active="request()->routeIs('admin.laporan.index')" />
                <x-sidebar-link href="{{ route('admin.antrian') }}" icon="" label="Antrian Verifikasi" :active="request()->routeIs('admin.antrian')" />
                <x-sidebar-link href="{{ route('admin.riwayat') }}" icon="" label="Riwayat Verifikasi" :active="request()->routeIs('admin.riwayat')" />

                {{-- Permohonan Hapus dengan badge count --}}
                <a href="{{ route('admin.permohonan-hapus') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.permohonan-hapus') ? 'bg-gradient-to-r from-purple-50 to-blue-50 text-[#6C3DE6] font-semibold shadow-xs' : 'text-gray-700 hover:bg-gray-50 hover:text-[#6C3DE6]' }}">
                    <span class="text-base"></span>
                    <span class="flex-1">Permohonan Hapus</span>
                    @php $jumlahHapus = \App\Models\Laporan::where('status_penghapusan', 'menunggu_verifikasi')->count(); @endphp
                    @if($jumlahHapus > 0)
                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center shadow">{{ $jumlahHapus }}</span>
                    @endif
                </a>

                @if(auth()->user()->isAdmin())
                    <div class="pt-3 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-gray-400">Data Master & Operasional</div>
                    <x-sidebar-link href="{{ route('admin.transportasi.index') }}" icon="" label="Kelola Transportasi" :active="request()->routeIs('admin.transportasi.*')" />
                    <x-sidebar-link href="{{ route('admin.wilayah.index') }}" icon="" label="Kelola Wilayah" :active="request()->routeIs('admin.wilayah.*')" />
                    <x-sidebar-link href="{{ route('admin.kategori.index') }}" icon="" label="Kategori Masalah" :active="request()->routeIs('admin.kategori.*')" />
                    <x-sidebar-link href="{{ route('admin.users.index') }}" icon="" label="Pengguna & Akses" :active="request()->routeIs('admin.users.*')" />
                @endif
            @else
                {{-- 1. Layanan Warga --}}
                <div class="pt-1 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-gray-400">Layanan Warga</div>
                <x-sidebar-link href="{{ route('warga.laporan.index') }}" icon="" label="Laporan Saya" :active="request()->routeIs('warga.laporan.index')" />
                <x-sidebar-link href="{{ route('warga.laporan.create') }}" icon="" label="Buat Laporan" :active="request()->routeIs('warga.laporan.create')" />
            @endif

            {{-- 2. Fitur Publik --}}
            <div class="pt-3 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-gray-400">Fitur Publik</div>
            <x-sidebar-link href="{{ route('peta.index') }}" icon="" label="Peta Masalah" :active="request()->routeIs('peta.*')" />
            <x-sidebar-link href="{{ route('transportasi.index') }}" icon="" label="Transportasi Umum" :active="request()->routeIs('transportasi.*')" />
            <x-sidebar-link href="{{ route('informasi-layanan.index') }}" icon="" label="Panduan Layanan" :active="request()->routeIs('informasi-layanan.*')" />
            <x-sidebar-link href="{{ route('home') }}" icon="" label="Beranda Publik" :active="request()->routeIs('home')" />
        </nav>

        {{-- Sidebar Footer --}}
        <div class="px-4 py-4 border-t border-gray-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#6C3DE6] to-[#3B82F6] flex items-center justify-center font-bold text-white text-sm flex-shrink-0 shadow-sm">
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
    </aside>

    {{-- ═══════════════ MAIN CONTENT AREA ═══════════════ --}}
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        {{-- Top Bar Header --}}
        <header class="bg-white/95 backdrop-blur-md border-b border-gray-200/80 px-4 sm:px-6 py-3.5 flex items-center justify-between flex-shrink-0 z-20">
            {{-- Kiri: Toggle Sidebar & Page Title --}}
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()"
                        class="p-2 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-[#6C3DE6] transition-colors focus:outline-none"
                        title="Buka / Tutup Sidebar"
                        aria-label="Toggle sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-base sm:text-lg font-bold text-gray-800 leading-tight">@yield('page-title', 'Dashboard')</h1>
                </div>
            </div>

            {{-- Kanan: Status Flash Messages, Notifikasi, dan Profil --}}
            <div class="flex items-center gap-2 sm:gap-3">
                @if(session('success'))
                    <div class="hidden md:flex bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-xl items-center gap-1.5 shadow-sm">
                        <span>✓</span> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="hidden md:flex bg-red-50 border border-red-200 text-red-700 text-xs font-semibold px-3 py-1.5 rounded-xl items-center gap-1.5 shadow-sm">
                        <span>✕</span> {{ session('error') }}
                    </div>
                @endif

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

                    {{-- Dropdown Card --}}
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
                            <span>🏠</span> Beranda CIVIC TRACK
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
            </div>
        </header>

        {{-- Page Main Content --}}
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
            @yield('content')
        </main>
    </div>
</div>

<script>
    function applyDashboardSidebarState() {
        const sidebar = document.getElementById('dashboard-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        if (!sidebar) return;
        const saved = localStorage.getItem('sigap_sidebar_state');
        if (window.innerWidth >= 1024) {
            if (saved === 'closed') {
                sidebar.classList.add('sidebar-collapsed');
            } else {
                sidebar.classList.remove('sidebar-collapsed');
            }
            if (backdrop) backdrop.classList.add('hidden');
        } else {
            if (saved === 'open') {
                sidebar.classList.add('sidebar-open');
                if (backdrop) backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.remove('sidebar-open');
                if (backdrop) backdrop.classList.add('hidden');
            }
        }
    }

    function toggleSidebar() {
        const sidebar = document.getElementById('dashboard-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        if (!sidebar) return;
        if (window.innerWidth < 1024) {
            const isOpen = sidebar.classList.contains('sidebar-open');
            if (isOpen) {
                sidebar.classList.remove('sidebar-open');
                if (backdrop) backdrop.classList.add('hidden');
                localStorage.setItem('sigap_sidebar_state', 'closed');
            } else {
                sidebar.classList.add('sidebar-open');
                if (backdrop) backdrop.classList.remove('hidden');
                localStorage.setItem('sigap_sidebar_state', 'open');
            }
        } else {
            sidebar.classList.toggle('sidebar-collapsed');
            const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
            localStorage.setItem('sigap_sidebar_state', isCollapsed ? 'closed' : 'open');
        }
    }

    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profile-dropdown');
        if (dropdown) dropdown.classList.toggle('hidden');
    }

    // Restore state on load
    document.addEventListener('DOMContentLoaded', applyDashboardSidebarState);

    // Close dropdown on outside click
    document.addEventListener('click', function(e) {
        const container = document.getElementById('profile-dropdown-container');
        const dropdown = document.getElementById('profile-dropdown');
        if (container && dropdown && !container.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const dropdown = document.getElementById('profile-dropdown');
            if (dropdown) dropdown.classList.add('hidden');

            const sidebar = document.getElementById('dashboard-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar && sidebar.classList.contains('sidebar-open')) {
                sidebar.classList.remove('sidebar-open');
                if (backdrop) backdrop.classList.add('hidden');
                localStorage.setItem('sigap_sidebar_state', 'closed');
            }
        }
    });
</script>

@stack('scripts')
</body>
</html>
