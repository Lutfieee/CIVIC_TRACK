<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Menampilkan daftar notifikasi laporan pengguna (FR-29)
     */
    public function index()
    {
        $notifikasis = auth()->user()->notifikasis()->paginate(15);
        return view('notifikasi.index', compact('notifikasis'));
    }

    /**
     * Menandai notifikasi telah dibaca
     */
    public function markAsRead(Notifikasi $notifikasi)
    {
        if ($notifikasi->user_id !== auth()->id()) {
            abort(403);
        }

        $notifikasi->update(['is_read' => true]);

        return back()->with('success', 'Notifikasi ditandai telah dibaca.');
    }

    /**
     * Menandai seluruh notifikasi telah dibaca
     */
    public function markAllRead()
    {
        auth()->user()->notifikasis()->update(['is_read' => true]);
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
