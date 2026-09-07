<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Verifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiController extends Controller
{
    public function approve(Request $request, Laporan $laporan)
    {
        if ($laporan->status !== 'pending') {
            return back()->with('error', 'Laporan ini sudah diproses.');
        }

        Verifikasi::create([
            'laporan_id' => $laporan->id,
            Verifikasi::administratorColumn() => Auth::id(),
            'status' => 'diverifikasi',
            'alasan_penolakan' => null,
            'tanggal_verifikasi' => now(),
        ]);

        $laporan->update(['status' => 'diverifikasi']);

        return redirect()->route('admin.antrian')
            ->with('success', 'Laporan berhasil diverifikasi dan dipublikasikan ke peta.');
    }

    public function reject(Request $request, Laporan $laporan)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:10|max:500',
        ]);

        if ($laporan->status !== 'pending') {
            return back()->with('error', 'Laporan ini sudah diproses.');
        }

        Verifikasi::create([
            'laporan_id' => $laporan->id,
            Verifikasi::administratorColumn() => Auth::id(),
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
            'tanggal_verifikasi' => now(),
        ]);

        $laporan->update(['status' => 'ditolak']);

        return redirect()->route('admin.antrian')
            ->with('success', 'Laporan telah ditolak.');
    }

    public function approveDelete(Laporan $laporan)
    {
        if (! $laporan->isMenungguHapus()) {
            return back()->with('error', 'Laporan ini tidak sedang dalam permohonan penghapusan.');
        }

        // Hapus foto jika ada
        if ($laporan->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($laporan->foto);
        }

        $laporan->delete();

        return redirect()->route('admin.permohonan-hapus')
            ->with('success', 'Laporan berhasil dihapus sesuai permohonan warga.');
    }

    public function rejectDelete(Request $request, Laporan $laporan)
    {
        if (! $laporan->isMenungguHapus()) {
            return back()->with('error', 'Laporan ini tidak sedang dalam permohonan penghapusan.');
        }

        $request->validate([
            'alasan_penolakan_hapus' => 'required|string|max:1000',
        ], [
            'alasan_penolakan_hapus.required' => 'Alasan penolakan permohonan hapus wajib diisi.',
        ]);

        $laporan->update([
            'status_penghapusan'      => 'ditolak_hapus',
            'alasan_penolakan_hapus'  => $request->alasan_penolakan_hapus,
            'tanggal_pengajuan_hapus' => null,
        ]);

        return redirect()->route('admin.permohonan-hapus')
            ->with('success', 'Permohonan hapus laporan telah ditolak. Laporan tetap aktif.');
    }
}
