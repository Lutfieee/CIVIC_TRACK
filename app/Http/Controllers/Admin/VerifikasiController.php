<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Notifikasi;
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

        Verifikasi::updateOrCreate(
            ['laporan_id' => $laporan->id],
            [
                Verifikasi::administratorColumn() => Auth::id(),
                'status' => 'diverifikasi',
                'alasan_penolakan' => null,
                'tanggal_verifikasi' => now(),
            ]
        );

        $laporan->update([
            'status' => 'diverifikasi',
            'tindak_lanjut' => $request->tindak_lanjut ?? 'Laporan telah diverifikasi oleh administrator dan diteruskan ke dinas terkait.',
        ]);

        // Kirim notifikasi ke warga (FR-29)
        Notifikasi::create([
            'user_id' => $laporan->user_id,
            'laporan_id' => $laporan->id,
            'judul' => 'Laporan Berhasil Diverifikasi',
            'pesan' => 'Laporan "' . ($laporan->judul ?? 'Masalah #' . $laporan->id) . '" telah disetujui dan dipublikasikan.',
        ]);

        return redirect()->route('admin.antrian')
            ->with('success', 'Laporan berhasil diverifikasi dan dipublikasikan.');
    }

    public function reject(Request $request, Laporan $laporan)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:10|max:500',
        ]);

        if ($laporan->status !== 'pending') {
            return back()->with('error', 'Laporan ini sudah diproses.');
        }

        Verifikasi::updateOrCreate(
            ['laporan_id' => $laporan->id],
            [
                Verifikasi::administratorColumn() => Auth::id(),
                'status' => 'ditolak',
                'alasan_penolakan' => $request->alasan_penolakan,
                'tanggal_verifikasi' => now(),
            ]
        );

        $laporan->update(['status' => 'ditolak']);

        // Kirim notifikasi ke warga (FR-29)
        Notifikasi::create([
            'user_id' => $laporan->user_id,
            'laporan_id' => $laporan->id,
            'judul' => 'Laporan Ditolak',
            'pesan' => 'Laporan "' . ($laporan->judul ?? 'Masalah #' . $laporan->id) . '" ditolak dengan alasan: ' . $request->alasan_penolakan,
        ]);

        return redirect()->route('admin.antrian')
            ->with('success', 'Laporan telah ditolak.');
    }

    /**
     * Memperbarui status penanganan dan mencatat tindak lanjut laporan (FR-14, FR-15)
     */
    public function updateStatus(Request $request, Laporan $laporan)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,diverifikasi,diproses,selesai,ditolak',
            'tindak_lanjut' => 'nullable|string|max:1000',
        ]);

        $updates = [
            'status' => $validated['status'],
            'tindak_lanjut' => $validated['tindak_lanjut'],
        ];

        if ($validated['status'] === 'selesai' && !$laporan->tanggal_selesai) {
            $updates['tanggal_selesai'] = now();
        }

        $laporan->update($updates);

        // Buat atau perbarui catatan verifikasi
        Verifikasi::updateOrCreate(
            ['laporan_id' => $laporan->id],
            [
                Verifikasi::administratorColumn() => Auth::id(),
                'status' => $validated['status'] === 'ditolak' ? 'ditolak' : 'diverifikasi',
                'alasan_penolakan' => $validated['status'] === 'ditolak' ? $validated['tindak_lanjut'] : null,
                'tanggal_verifikasi' => now(),
            ]
        );

        // Notifikasi ke warga (FR-29)
        $statusLabels = [
            'diverifikasi' => 'Terverifikasi',
            'diproses' => 'Sedang Diproses / Ditindaklanjuti',
            'selesai' => 'Selesai Ditangani',
            'ditolak' => 'Ditolak',
            'pending' => 'Menunggu Verifikasi',
        ];

        Notifikasi::create([
            'user_id' => $laporan->user_id,
            'laporan_id' => $laporan->id,
            'judul' => 'Status Laporan: ' . ($statusLabels[$validated['status']] ?? $validated['status']),
            'pesan' => 'Laporan "' . ($laporan->judul ?? 'Laporan #' . $laporan->id) . '" statusnya kini ' . ($statusLabels[$validated['status']] ?? $validated['status']) . ($validated['tindak_lanjut'] ? '. Catatan: ' . $validated['tindak_lanjut'] : ''),
        ]);

        return back()->with('success', 'Status dan tindak lanjut laporan berhasil diperbarui!');
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
