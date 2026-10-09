@extends('layouts.dashboard')

@section('title', 'Edit Wilayah')
@section('page-title', 'Edit Data Wilayah')

@section('content')
<div class="p-6 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.wilayah.index') }}" class="text-xs text-gray-500 hover:text-primary transition-colors">
            ← Kembali ke Daftar Wilayah
        </a>
        <h2 class="text-xl font-bold text-gray-900 mt-2">Edit Data Wilayah: {{ $wilayah->nama }}</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.wilayah.update', $wilayah->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Wilayah *</label>
                <input type="text" name="nama" value="{{ old('nama', $wilayah->nama) }}" required
                       class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tingkat / Tipe *</label>
                    <select name="tipe" required class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-white">
                        <option value="Kecamatan" {{ old('tipe', $wilayah->tipe) == 'Kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                        <option value="Kelurahan" {{ old('tipe', $wilayah->tipe) == 'Kelurahan' ? 'selected' : '' }}>Kelurahan</option>
                        <option value="Desa" {{ old('tipe', $wilayah->tipe) == 'Desa' ? 'selected' : '' }}>Desa</option>
                        <option value="Kota/Kabupaten" {{ old('tipe', $wilayah->tipe) == 'Kota/Kabupaten' ? 'selected' : '' }}>Kota / Kabupaten</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kode Pos</label>
                    <input type="text" name="kode_pos" value="{{ old('kode_pos', $wilayah->kode_pos) }}"
                           class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Deskripsi Wilayah</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">{{ old('deskripsi', $wilayah->deskripsi) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.wilayah.index') }}" class="px-4 py-2.5 rounded-xl text-gray-600 text-xs font-semibold hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dark text-white text-xs font-bold shadow-sm transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
