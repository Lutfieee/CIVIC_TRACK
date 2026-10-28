# SIGAP — Sistem Informasi Geospasial Aman & Peduli (Kota Bandung)

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![Leaflet](https://img.shields.io/badge/Leaflet-1.9.4-199900?style=for-the-badge&logo=leaflet)](https://leafletjs.com)
[![SDG 11](https://img.shields.io/badge/SDG_11-Sustainable_Cities-FD9D24?style=for-the-badge)](https://sdgs.un.org/goals/goal11)

Aplikasi web geospasial partisipatif untuk memetakan risiko perkotaan (Kemacetan Lalu Lintas, Banjir & Genangan Air, serta Kawasan Permukiman Padat/Kumuh) di seluruh 30 kecamatan Kota Bandung secara terpusat, dengan fitur andalan **"Cari Kos Aman"** berbasis formula geospasial Haversine untuk mahasiswa dan warga.

Dibangun dengan **Laravel Native (MVC murni — tanpa package admin panel otomatis seperti Filament/Nova/Backpack)** sesuai standar rekrutmen Asisten Laboratorium EISD.

---

## 🌟 Fitur Utama

### A. Fitur Publik (Tanpa Login)
1. **Peta Interaktif Geospasial**: Menampilkan seluruh laporan berstatus *terverifikasi* dengan marker warna & ikon spesifik per kategori isu.
2. **Filter Peta Real-time**: Filter berdasarkan kategori dan transparansi status (terverifikasi vs belum terverifikasi dengan visual pudar).
3. **Detail Laporan & Lokasi**: Menampilkan foto bukti riil, tingkat keparahan, tanggal kejadian, titik peta, dan riwayat update komentar warga.
4. **Cari Kos Aman (Fitur Andalan)**: Form input koordinat atau klik peta calon kos → menghitung radius (1–5 km) menggunakan formula Haversine dan mendeteksi riwayat banjir/macet terdekat beserta skor keamanan lingkungan.

### B. Fitur Warga / Mahasiswa (Role: `warga`)
5. **Autentikasi & Registrasi**: Role default saat pendaftaran adalah `warga`.
6. **Submit Laporan Partisipatif**: Form pelaporan dengan pin lokasi interaktif Leaflet, dropdown 30 kecamatan Bandung, upload foto bukti, dan tingkat keparahan (rendah/sedang/tinggi).
7. **Riwayat Laporan Saya**: Daftar laporan milik sendiri dengan status badge (`pending`, `diverifikasi`, `ditolak`).
8. **Edit & Hapus Laporan (Strict Policy & Controller Check)**: Hanya diizinkan selama status masih `pending`. Dilengkapi dialog konfirmasi hapus.
9. **Komentar & Update Laporan**: Warga dapat memberikan komentar konfirmasi lapangan (misal: "air sudah surut", "petugas sudah di lokasi").

### C. Fitur Administrator (Role: `admin`)
10. **Dashboard Admin & Statistik Geospasial**: Chart ringkas distribusi laporan per kategori (Doughnut Chart) dan tren laporan 6 bulan (Line Chart via Chart.js).
11. **Antrian Verifikasi Laporan**: Daftar laporan berstatus `pending` yang perlu ditinjau administrator.
12. **Aksi Verifikasi / Tolak**: Approve untuk mempublikasikan laporan ke peta publik; Tolak dengan kewajiban mengisi alasan penolakan.
13. **Riwayat Verifikasi Pribadi**: Riwayat seluruh laporan yang diproses oleh administrator yang sedang login.
14. **Kelola Data Master Lokasi**: CRUD titik referensi/landmark kota (contoh: Gerbang Telkom University, Bojongsoang, Dago) dengan relasi Many-to-Many ke kategori.
15. **Kelola Pengguna (User Management)**: CRUD pengguna, aktivasi/nonaktifkan akun (`is_active`), serta assign role warga/admin.
16. **Kelola Kategori**: CRUD kategori isu perkotaan lengkap dengan nama, emoji ikon, dan kode warna hex.

---

## 🔑 Akun Demo (Seeder)

Aplikasi telah dilengkapi seeder akun dengan password default: `password`

| Role | Email | Password | Keterangan |
|------|-------|----------|------------|
| **Admin** | `admin@sigap.id` | `password` | Administrator Utama SIGAP |
| **Warga** | `warga@sigap.id` | `password` | Ahmad Fauzi (Mahasiswa Telkom U) |
| **Warga** | `siti@sigap.id` | `password` | Siti Nurhaliza (Warga Coblong) |
| **Warga** | `budi@sigap.id` | `password` | Budi Santoso (Warga Sukajadi) |

---

## ⚙️ Persyaratan Sistem

- **PHP**: >= 8.2
- **Composer**: >= 2.x
- **Database Server**: MySQL / MariaDB (XAMPP / Laragon / Standalone)
- **Ekstensi PHP Aktif**: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `curl`

---

## 🚀 Panduan Instalasi & Menjalankan

### 1. Masuk ke Direktori Project
```bash
cd c:/Coding/EISD/sigap
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env` jika belum ada:
```bash
cp .env.example .env
php artisan key:generate
```
Pastikan pengaturan database di `.env` sudah mengarah ke MySQL lokal:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sigap_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seeding Database
Jalankan migrasi database beserta data dummy lengkap:
```bash
php artisan migrate:fresh --seed
```

### 5. Hubungkan Storage Publik (Untuk Upload Foto)
```bash
php artisan storage:link
```

### 6. Jalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui peramban: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 📐 Skema Relasi Database (Eloquent ORM)

- **`User`** hasMany **`Laporan`** (1-to-Many: Pelapor)
- **`User`** hasMany **`Verifikasi`** (1-to-Many: Administrator)
- **`User`** hasMany **`Komentar`** (1-to-Many)
- **`Kategori`** hasMany **`Laporan`** (1-to-Many)
- **`Lokasi`** belongsToMany **`Kategori`** via pivot **`kategori_lokasi`** (Many-to-Many Wajib)
- **`Laporan`** hasMany **`Komentar`** (1-to-Many)
- **`Laporan`** hasOne **`Verifikasi`** (1-to-1)

---

## 🎨 Desain & UI/UX

Mengadopsi referensi *Outcrowd UI*:
- **Palet Warna**: Gradien modern Deep Purple ke Vivid Blue (`#6C3DE6` to `#3B82F6`).
- **Tipografi**: Inter (Google Fonts) dengan *weight* tebal dan hirarki visual tegas.
- **Komponen**: Card `rounded-2xl` dan `rounded-3xl`, glassmorphism halus, micro-interaction hover, serta penanda status berbasis warna dinamis.

## Kelola Pengguna (SCRUM-14)

Fitur ini memungkinkan admin mengelola akun pengguna:
- Menambah pengguna baru
- Mengubah data pengguna
- Menghapus pengguna
- Pembatasan akses berdasarkan role

Cara mengakses: login sebagai admin, lalu buka menu Pengguna.