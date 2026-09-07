<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — SIGAP</title>
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
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    <aside class="w-64 bg-gradient-to-b from-[#6C3DE6] to-[#3B82F6] text-white flex flex-col shadow-2xl flex-shrink-0">
        <div class="px-6 py-5 border-b border-white/20">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center font-bold text-sm">S</div>
                <span class="font-bold text-xl">SIGAP</span>
            </a>
            <p class="text-xs text-white/60 mt-1">{{ ucfirst(auth()->user()->role) }}</p>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @if(auth()->user()->isAdmin())
                <x-sidebar-link href="{{ route('admin.dashboard') }}" icon="📊" label="Dashboard" :active="request()->routeIs('admin.dashboard')" />
                <x-sidebar-link href="{{ route('admin.antrian') }}" icon="⏳" label="Antrian Verifikasi" :active="request()->routeIs('admin.antrian')" />
                <x-sidebar-link href="{{ route('admin.riwayat') }}" icon="📋" label="Riwayat Verifikasi" :active="request()->routeIs('admin.riwayat')" />
                {{-- Permohonan Hapus dengan badge count --}}
                <a href="{{ route('admin.permohonan-hapus') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.permohonan-hapus') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base">🗑️</span>
                    <span class="flex-1">Permohonan Hapus</span>
                    @php $jumlahHapus = \App\Models\Laporan::where('status_penghapusan', 'menunggu_verifikasi')->count(); @endphp
                    @if($jumlahHapus > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[20px] text-center">{{ $jumlahHapus }}</span>
                    @endif
                </a>
                <x-sidebar-link href="{{ route('admin.users.index') }}" icon="👥" label="Pengguna" :active="request()->routeIs('admin.users.*')" />
                <x-sidebar-link href="{{ route('admin.kategori.index') }}" icon="🏷️" label="Kategori" :active="request()->routeIs('admin.kategori.*')" />
                <x-sidebar-link href="{{ route('admin.lokasi.index') }}" icon="🏠" label="Lokasi Kos" :active="request()->routeIs('admin.lokasi.*')" />
            @else
                <x-sidebar-link href="{{ route('warga.laporan.index') }}" icon="📋" label="Laporan Saya" :active="request()->routeIs('warga.laporan.index')" />
                <x-sidebar-link href="{{ route('warga.laporan.create') }}" icon="➕" label="Buat Laporan" :active="request()->routeIs('warga.laporan.create')" />
            @endif

            <div class="border-t border-white/20 pt-2 mt-2">
                <x-sidebar-link href="{{ route('peta.index') }}" icon="🗺️" label="Lihat Peta" :active="false" />
                <x-sidebar-link href="{{ route('cari-kos.index') }}" icon="🔍" label="Cari Kos" :active="false" />
            </div>
        </nav>

        <div class="px-3 py-4 border-t border-white/20">
            <div class="flex items-center gap-3 mb-3 px-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-white/60 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm text-white/70 hover:bg-white/10 hover:text-white transition-colors flex items-center gap-2">
                    🚪 Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Top bar --}}
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-3">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-3 py-1.5 rounded-lg flex items-center gap-1">
                        <span>✓</span> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-3 py-1.5 rounded-lg flex items-center gap-1">
                        <span>✕</span> {{ session('error') }}
                    </div>
                @endif
                <span class="text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
