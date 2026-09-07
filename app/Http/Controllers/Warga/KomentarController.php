<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Komentar;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomentarController extends Controller
{
    public function store(Request $request, Laporan $laporan)
    {
        $request->validate([
            'isi' => 'required|string|min:3|max:1000',
        ]);

        Komentar::create([
            'laporan_id' => $laporan->id,
            'user_id' => Auth::id(),
            'isi' => $request->isi,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function destroy(Komentar $komentar)
    {
        if ($komentar->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $komentar->delete();
        return back()->with('success', 'Komentar dihapus.');
    }
}
