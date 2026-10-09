<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ExportLaporanController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\TransportasiController as AdminTransportasi;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Admin\WilayahController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InformasiLayananController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\PetaKomentarController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\TransportasiController;
use App\Http\Controllers\Warga\KomentarController;
use App\Http\Controllers\Warga\LaporanController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────
// 1. Publik / Pengunjung (Tanpa Login / Guest)
// ─────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// Peta Persebaran Masalah Lingkungan & Fasilitas
Route::get('/peta', [PetaController::class, 'index'])->name('peta.index');
Route::get('/peta/data', [PetaController::class, 'data'])->name('peta.data');
Route::get('/peta/{laporan}', [PetaController::class, 'show'])->name('peta.show');
Route::post('/peta/{laporan}/komentar', [PetaKomentarController::class, 'store'])->name('peta.komentar.store')->middleware('auth');

// Pemantauan Transportasi Umum Terpadu
Route::get('/transportasi', [TransportasiController::class, 'index'])->name('transportasi.index');
Route::get('/transportasi/{transportasi}', [TransportasiController::class, 'show'])->name('transportasi.show');

// Panduan & Informasi Layanan
Route::get('/informasi-layanan', [InformasiLayananController::class, 'index'])->name('informasi-layanan.index');

// ─────────────────────────────────────────────────────────────
// 2. Autentikasi Pengguna
// ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─────────────────────────────────────────────────────────────
// 3. Modul Pengguna Terautentikasi (Warga & Admin)
// ─────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    // Manajemen Profil Pengguna
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

    // Notifikasi Laporan
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{notifikasi}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/read-all', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.read-all');

    // Hapus Komentar (Warga untuk komentar miliknya, Admin untuk moderasi)
    Route::delete('/komentar/{komentar}', [KomentarController::class, 'destroy'])->name('komentar.destroy');
});

// ─────────────────────────────────────────────────────────────
// 4. Role: Warga
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:warga'])->prefix('warga')->name('warga.')->group(function () {
    Route::resource('laporan', LaporanController::class);
    Route::post('laporan/{laporan}/komentar', [KomentarController::class, 'store'])->name('komentar.store');
    Route::post('laporan/{laporan}/ajukan-hapus', [LaporanController::class, 'ajukanHapus'])->name('laporan.ajukan-hapus');
    Route::post('laporan/{laporan}/batal-hapus', [LaporanController::class, 'batalHapus'])->name('laporan.batal-hapus');
});

// ─────────────────────────────────────────────────────────────
// 5. Role: Admin
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Utama Analitik
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    // Pengelolaan Laporan Masuk & Seluruh Laporan
    Route::get('/semua-laporan', [AdminDashboard::class, 'kelolaLaporan'])->name('laporan.index');
    Route::get('/laporan/export', [ExportLaporanController::class, 'export'])->name('laporan.export');
    Route::get('/laporan/{laporan}', [AdminDashboard::class, 'show'])->name('laporan.show');
    Route::put('/laporan/{laporan}/update-status', [VerifikasiController::class, 'updateStatus'])->name('laporan.update-status');

    // Verifikasi & Tindak Lanjut
    Route::get('/antrian-verifikasi', [AdminDashboard::class, 'antrian'])->name('antrian');
    Route::get('/riwayat-verifikasi', [AdminDashboard::class, 'riwayat'])->name('riwayat');
    Route::post('/laporan/{laporan}/approve', [VerifikasiController::class, 'approve'])->name('laporan.approve');
    Route::post('/laporan/{laporan}/reject', [VerifikasiController::class, 'reject'])->name('laporan.reject');
    Route::get('/permohonan-hapus', [AdminDashboard::class, 'permohonanHapus'])->name('permohonan-hapus');
    Route::post('/laporan/{laporan}/approve-delete', [VerifikasiController::class, 'approveDelete'])->name('laporan.approve-delete');
    Route::post('/laporan/{laporan}/reject-delete', [VerifikasiController::class, 'rejectDelete'])->name('laporan.reject-delete');

    // Pengelolaan Data Pengguna & Hak Akses
    Route::resource('users', UserController::class)->except(['show']);

    // Pengelolaan Kategori Masalah
    Route::resource('kategori', KategoriController::class)->except(['show', 'create', 'edit']);

    // Pengelolaan Pemantauan Transportasi Umum & Jadwal
    Route::resource('transportasi', AdminTransportasi::class)->except(['show']);

    // Pengelolaan Data Master Wilayah
    Route::resource('wilayah', WilayahController::class)->except(['show']);
});
