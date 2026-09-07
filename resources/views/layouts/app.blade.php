<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIGAP - Sistem Informasi Geospasial Aman & Peduli Kota Bandung">
    <title>@yield('title', 'SIGAP') - SIGAP</title>
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
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-gray-900">
    {{-- Navbar --}}
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-sm">S</div>
                    <span class="font-bold text-xl bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">SIGAP</span>
                </a>
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('peta.index') }}" class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">🗺️ Peta</a>
                    <a href="{{ route('cari-kos.index') }}" class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">🏠 Cari Kos</a>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('warga.laporan.index') }}" class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">Dashboard</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-gray-500 hover:text-red-500 transition-colors">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-primary transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-primary to-secondary text-white text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">Daftar</a>
                    @endauth
                </div>
                {{-- Mobile menu button --}}
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
        {{-- Mobile menu --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white px-4 py-3 space-y-2">
            <a href="{{ route('peta.index') }}" class="block py-2 text-sm text-gray-700 hover:text-primary">🗺️ Peta Kejadian</a>
            <a href="{{ route('cari-kos.index') }}" class="block py-2 text-sm text-gray-700 hover:text-primary">🏠 Cari Kos Aman</a>
            @auth
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('warga.laporan.index') }}" class="block py-2 text-sm text-gray-700 hover:text-primary">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="block py-2 text-sm text-red-500">Keluar</button></form>
            @else
                <a href="{{ route('login') }}" class="block py-2 text-sm text-gray-700">Masuk</a>
                <a href="{{ route('register') }}" class="block py-2 text-sm text-primary font-semibold">Daftar Sekarang</a>
            @endauth
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success') || session('error'))
        <div class="fixed top-20 right-4 z-50 max-w-sm" id="flash-msg">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-lg flex items-center gap-2">
                    <span class="text-green-500">✓</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-lg flex items-center gap-2">
                    <span class="text-red-500">✕</span> {{ session('error') }}
                </div>
            @endif
        </div>
        <script>setTimeout(() => { const el = document.getElementById('flash-msg'); if (el) el.style.display = 'none'; }, 4000);</script>
    @endif

    <main class="pt-16">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-400 py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-sm">S</div>
                        <span class="font-bold text-white text-lg">SIGAP</span>
                    </div>
                    <p class="text-sm leading-relaxed">Sistem Informasi Geospasial Aman & Peduli — platform partisipasi warga untuk kota Bandung yang lebih baik.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Navigasi</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ route('peta.index') }}" class="hover:text-white transition-colors">Peta Kejadian</a></li>
                        <li><a href="{{ route('cari-kos.index') }}" class="hover:text-white transition-colors">Cari Kos Aman</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Kontak</h4>
                    <p class="text-sm">📧 sigap@bandung.go.id</p>
                    <p class="text-sm mt-1">🏛️ Pemerintah Kota Bandung</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-6 text-center text-xs">
                © {{ date('Y') }} SIGAP — Kota Bandung. Dibuat dengan ❤️ untuk warga.
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
