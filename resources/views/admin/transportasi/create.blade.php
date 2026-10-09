@extends('layouts.dashboard')

@section('title', 'Tambah Transportasi')
@section('page-title', 'Tambah Moda & Rute Transportasi')

@section('content')
<div class="p-6 max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.transportasi.index') }}" class="text-xs text-gray-500 hover:text-primary transition-colors">
            ← Kembali ke Daftar Transportasi
        </a>
        <h2 class="text-xl font-bold text-gray-900 mt-2">Tambah Data Moda & Rute Transportasi</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.transportasi.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Moda Transportasi *</label>
                    <input type="text" name="nama_moda" value="{{ old('nama_moda') }}" required placeholder="Contoh: Trans Metro Bandung (TMB) Koridor 1"
                           class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                    @error('nama_moda') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jenis Moda *</label>
                    <select name="tipe" required class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-white">
                        <option value="Bus">Bus</option>
                        <option value="Angkot">Angkot</option>
                        <option value="Kereta">Kereta</option>
                        <option value="Feeder">Feeder</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kode Trayek *</label>
                    <input type="text" name="kode_trayek" value="{{ old('kode_trayek') }}" required placeholder="Contoh: TMB-K1, AK-08"
                           class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Titik Awal (Asal) *</label>
                    <input type="text" name="rute_asal" value="{{ old('rute_asal') }}" required placeholder="Contoh: Terminal Cicaheum"
                           class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Titik Akhir (Tujuan) *</label>
                    <input type="text" name="rute_tujuan" value="{{ old('rute_tujuan') }}" required placeholder="Contoh: Terminal Leuwipanjang"
                           class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Titik Pemberhentian & Halte (Pisahkan dengan tanda '-') *</label>
                <textarea name="titik_pemberhentian" rows="2" required placeholder="Contoh: Cicaheum - Cicadas - Kosambi - Alun-Alun - Kebon Kelapa - Leuwipanjang"
                          class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">{{ old('titik_pemberhentian') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jadwal Operasional *</label>
                    <input type="text" name="jadwal_operasional" value="{{ old('jadwal_operasional', '05:30 - 21:00 WIB (Interval 15 Menit)') }}" required
                           class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tarif *</label>
                    <input type="text" name="tarif" value="{{ old('tarif', 'Rp 4.000') }}" required
                           class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status Operasional *</label>
                    <select name="status_operasional" required class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-white">
                        <option value="normal">Normal</option>
                        <option value="terkendala">Terkendala</option>
                        <option value="perbaikan">Perbaikan / Pengalihan</option>
                        <option value="tidak_beroperasi">Tidak Beroperasi</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Keterangan / Catatan Operasional Terkini</label>
                <textarea name="keterangan_operasional" rows="2" placeholder="Contoh: Seluruh armada beroperasi lancar tanpa kendala cuaca."
                          class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">{{ old('keterangan_operasional') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.transportasi.index') }}" class="px-4 py-2.5 rounded-xl text-gray-600 text-xs font-semibold hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dark text-white text-xs font-bold shadow-sm transition-all">
                    Simpan Moda Transportasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
