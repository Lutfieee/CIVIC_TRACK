<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportLaporanController extends Controller
{
    /**
     * Mengekspor data laporan ke format CSV (FR-31)
     */
    public function export(Request $request): StreamedResponse
    {
        $fileName = 'laporan_sigap_' . date('Y-m-d_His') . '.csv';

        $query = Laporan::with(['user', 'kategori', 'verifikasi']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if ($request->filled('wilayah')) {
            $query->where('wilayah', $request->wilayah);
        }

        $laporans = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($laporans) {
            $output = fopen('php://output', 'w');
            // Menulis BOM agar UTF-8 terbaca dengan baik di Microsoft Excel
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom
            fputcsv($output, [
                'ID Laporan',
                'Judul Laporan',
                'Kategori',
                'Pelapor',
                'Email Pelapor',
                'Wilayah',
                'Latitude',
                'Longitude',
                'Tingkat Keparahan',
                'Status Penanganan',
                'Tindak Lanjut / Catatan',
                'Tanggal Lapor',
                'Tanggal Selesai',
            ]);

            foreach ($laporans as $item) {
                fputcsv($output, [
                    $item->id,
                    $item->judul ?? 'Laporan #' . $item->id,
                    $item->kategori->nama_kategori ?? '-',
                    $item->user->name ?? '-',
                    $item->user->email ?? '-',
                    $item->wilayah,
                    $item->latitude,
                    $item->longitude,
                    ucfirst($item->tingkat_keparahan),
                    $item->status_label,
                    $item->tindak_lanjut ?? ($item->verifikasi->alasan_penolakan ?? '-'),
                    $item->created_at->format('Y-m-d H:i:s'),
                    $item->tanggal_selesai ? $item->tanggal_selesai->format('Y-m-d H:i:s') : '-',
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}
