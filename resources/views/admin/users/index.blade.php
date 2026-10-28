@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-gray-500">{{ $users->total() }} pengguna terdaftar</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#6C3DE6] to-[#3B82F6] text-white text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all">
        ➕ Tambah Pengguna
    </a>
</div>

{{-- Filter --}}
<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="search" placeholder="Cari nama / email..." value="{{ request('search') }}"
           class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30">
    <select name="role" class="px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#6C3DE6]/30">
        <option value="">Semua Role</option>
        <option value="warga" {{ request('role') === 'warga' ? 'selected' : '' }}>Warga</option>
        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
    </select>
    <button type="submit" class="px-4 py-2.5 rounded-xl bg-gray-800 text-white text-sm font-medium">Filter</button>
</form>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Nama</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Email</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Role</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($users as $user)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#6C3DE6] to-[#3B82F6] text-white text-xs font-bold flex items-center justify-center">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="font-medium text-gray-800">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="px-5 py-4 text-gray-500">{{ $user->email }}</td>
                <td class="px-5 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'dinas' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                        {{ $user->role === 'dinas' ? 'Petugas Dinas' : ucfirst($user->role) }}
                    </span>
                    @if($user->instansi)
                        <p class="text-[10px] text-blue-600 font-medium mt-1 truncate max-w-[200px]" title="{{ $user->instansi }}">
                            🏛️ {{ $user->instansi }}
                        </p>
                    @endif
                </td>
                <td class="px-5 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-xs px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">Edit</a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs px-2.5 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">Hapus</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-gray-100">{{ $users->withQueryString()->links() }}</div>
</div>
@endsection
