<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LokasiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CariKosController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\Warga\KomentarController;
use App\Http\Controllers\Warga\LaporanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetaKomentarController;

// ──────────────────────────────
// Publik
// ──────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// Peta
Route::get('/peta', [PetaController::class, 'index'])->name('peta.index');
Route::get('/peta/data', [PetaController::class, 'data'])->name('peta.data');
Route::get('/peta/{laporan}', [PetaController::class, 'show'])->name('peta.show');
Route::post('/peta/{laporan}/komentar', [PetaKomentarController::class, 'store'])->name('peta.komentar.store')->middleware('auth');

// Cari Kos
Route::get('/cari-kos', [CariKosController::class, 'index'])->name('cari-kos.index');
Route::get('/cari-kos/search', [CariKosController::class, 'search'])->name('cari-kos.search');

// ──────────────────────────────
// Auth
// ──────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ──────────────────────────────
// Warga
// ──────────────────────────────
Route::middleware(['auth', 'role:warga'])->prefix('warga')->name('warga.')->group(function () {
    Route::resource('laporan', LaporanController::class);
    Route::post('laporan/{laporan}/komentar', [KomentarController::class, 'store'])->name('komentar.store');
    Route::delete('komentar/{komentar}', [KomentarController::class, 'destroy'])->name('komentar.destroy');
    Route::post('laporan/{laporan}/ajukan-hapus', [LaporanController::class, 'ajukanHapus'])->name('laporan.ajukan-hapus');
    Route::post('laporan/{laporan}/batal-hapus', [LaporanController::class, 'batalHapus'])->name('laporan.batal-hapus');
});

// ──────────────────────────────
// Admin
// ──────────────────────────────
// ──────────────────────────────
// Admin
// ──────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/antrian-verifikasi', [AdminDashboard::class, 'antrian'])->name('antrian');
    Route::get('/riwayat-verifikasi', [AdminDashboard::class, 'riwayat'])->name('riwayat');
    Route::get('/laporan/{laporan}', [AdminDashboard::class, 'show'])->name('laporan.show');
    Route::post('/laporan/{laporan}/approve', [VerifikasiController::class, 'approve'])->name('laporan.approve');
    Route::post('/laporan/{laporan}/reject', [VerifikasiController::class, 'reject'])->name('laporan.reject');
    Route::get('/permohonan-hapus', [AdminDashboard::class, 'permohonanHapus'])->name('permohonan-hapus');
    Route::post('/laporan/{laporan}/approve-delete', [VerifikasiController::class, 'approveDelete'])->name('laporan.approve-delete');
    Route::post('/laporan/{laporan}/reject-delete', [VerifikasiController::class, 'rejectDelete'])->name('laporan.reject-delete');
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('kategori', KategoriController::class)->except(['show', 'create', 'edit']);
    Route::resource('lokasi', LokasiController::class)->except(['show']);
});
