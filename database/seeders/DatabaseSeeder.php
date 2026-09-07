<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Komentar;
use App\Models\Laporan;
use App\Models\Lokasi;
use App\Models\User;
use App\Models\Verifikasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ──────────────────────────────
        // 1. Akun Pengguna (Multi-Role)
        // ──────────────────────────────
        $admin = User::create([
            'name' => 'Administrator SIGAP',
            'email' => 'admin@sigap.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $warga1 = User::create([
            'name' => 'Ahmad Fauzi (Mahasiswa)',
            'email' => 'warga@sigap.id',
            'password' => Hash::make('password'),
            'role' => 'warga',
            'is_active' => true,
        ]);

        $warga2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@sigap.id',
            'password' => Hash::make('password'),
            'role' => 'warga',
            'is_active' => true,
        ]);

        $warga3 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@sigap.id',
            'password' => Hash::make('password'),
            'role' => 'warga',
            'is_active' => true,
        ]);

        // ──────────────────────────────
        // 2. Kategori Laporan (SDG 11 Focus)
        // ──────────────────────────────
        $katMacet = Kategori::create([
            'nama_kategori' => 'Lalu Lintas / Kemacetan',
            'ikon' => '🚦',
            'warna' => '#8B5CF6', // Purple
        ]);

        $katBanjir = Kategori::create([
            'nama_kategori' => 'Banjir & Genangan Air',
            'ikon' => '🌊',
            'warna' => '#3B82F6', // Blue
        ]);

        $katPermukiman = Kategori::create([
            'nama_kategori' => 'Kawasan Padat / Kumuh',
            'ikon' => '🏘️',
            'warna' => '#10B981', // Emerald / Teal
        ]);

        $katJalan = Kategori::create([
            'nama_kategori' => 'Jalan Rusak / Berlubang',
            'ikon' => '🕳️',
            'warna' => '#EF4444', // Red
        ]);

        $katSampah = Kategori::create([
            'nama_kategori' => 'Sampah Liar',
            'ikon' => '🗑️',
            'warna' => '#F59E0B', // Amber
        ]);

        // ──────────────────────────────
        // 3. Lokasi Master / Kos & Landmark
        // ──────────────────────────────
        $lokasi1 = Lokasi::create([
            'nama' => 'Titik Gerbang 1 Telkom University',
            'alamat' => 'Jl. Telekomunikasi No. 1, Sukapura, Dayeuhkolot',
            'wilayah' => 'Bandung Kidul',
            'latitude' => -6.974026,
            'longitude' => 107.630328,
            'deskripsi' => 'Landmark pintu gerbang utama Telkom University, titik acuan mahasiswa mencari hunian kos di sekitar Sukapura & Bojongsoang.',
            'harga_per_bulan' => null,
            'kontak' => '(022) 7564108',
            'is_active' => true,
        ]);
        $lokasi1->kategoris()->attach([$katMacet->id, $katBanjir->id]);

        $lokasi2 = Lokasi::create([
            'nama' => 'Kos Graha Dago Asri',
            'alamat' => 'Jl. Tubagus Ismail VII No. 14, Coblong',
            'wilayah' => 'Coblong',
            'latitude' => -6.885120,
            'longitude' => 107.619840,
            'deskripsi' => 'Kos strategis dekat kampus ITB dan Unpad Dipatiukur, kamar mandi dalam, WiFi fiber optic, bebas banjir.',
            'harga_per_bulan' => '1750000',
            'kontak' => '081223344556',
            'is_active' => true,
        ]);
        $lokasi2->kategoris()->attach([$katPermukiman->id]);

        $lokasi3 = Lokasi::create([
            'nama' => 'Pondok Mahasiswa Sukapura Indah',
            'alamat' => 'Jl. Radio Palasari No. 22, Dayeuhkolot',
            'wilayah' => 'Bandung Kidul',
            'latitude' => -6.978500,
            'longitude' => 107.633500,
            'deskripsi' => 'Kos mahasiswa terjangkau 5 menit jalan kaki dari kampus, akses motor luas, keamanan 24 jam.',
            'harga_per_bulan' => '850000',
            'kontak' => '085712349876',
            'is_active' => true,
        ]);
        $lokasi3->kategoris()->attach([$katBanjir->id, $katMacet->id]);

        $lokasi4 = Lokasi::create([
            'nama' => 'Wisma Dipatiukur Harmoni',
            'alamat' => 'Jl. Dipatiukur No. 88, Lebakgede',
            'wilayah' => 'Coblong',
            'latitude' => -6.892540,
            'longitude' => 107.616620,
            'deskripsi' => 'Kos eksklusif dekat halte bus Trans Metro Bandung dan area kuliner Dipatiukur.',
            'harga_per_bulan' => '2200000',
            'kontak' => '087899887766',
            'is_active' => true,
        ]);
        $lokasi4->kategoris()->attach([$katMacet->id]);

        $lokasi5 = Lokasi::create([
            'nama' => 'Kost Putri Ciumbuleuit Panorama',
            'alamat' => 'Jl. Ciumbuleuit No. 105, Hegarmanah',
            'wilayah' => 'Cidadap',
            'latitude' => -6.877800,
            'longitude' => 107.604500,
            'deskripsi' => 'Kos khusus mahasiswi dekat Universitas Katolik Parahyangan, udara sejuk, lingkungan tenang dan aman.',
            'harga_per_bulan' => '1900000',
            'kontak' => '081345678901',
            'is_active' => true,
        ]);
        $lokasi5->kategoris()->attach([$katPermukiman->id]);

        $lokasi6 = Lokasi::create([
            'nama' => 'Griya Mahasiswa Buahbatu Asri',
            'alamat' => 'Jl. Buahbatu No. 176, Turangga',
            'wilayah' => 'Lengkong',
            'latitude' => -6.932100,
            'longitude' => 107.625500,
            'deskripsi' => 'Akses angkutan umum mudah, lingkungan nyaman dengan fasilitas dapur bersama dan parkir mobil.',
            'harga_per_bulan' => '1300000',
            'kontak' => '089612345678',
            'is_active' => true,
        ]);
        $lokasi6->kategoris()->attach([$katMacet->id, $katJalan->id]);

        // ──────────────────────────────
        // 4. Laporan Warga & Verifikasi
        // ──────────────────────────────
        // Laporan 1: Terverifikasi (Banjir Bojongsoang dekat Telkom University)
        $lap1 = Laporan::create([
            'user_id' => $warga1->id,
            'kategori_id' => $katBanjir->id,
            'latitude' => -6.976500,
            'longitude' => 107.631800,
            'wilayah' => 'Bandung Kidul',
            'deskripsi' => 'Genangan air setinggi 40-60 cm merendam badan jalan raya setelah hujan deras selama 2 jam. Kendaraan roda dua mogok dan akses menuju gerbang kampus terhambat.',
            'tingkat_keparahan' => 'tinggi',
            'status' => 'diverifikasi',
            'created_at' => now()->subDays(2),
        ]);
        Verifikasi::create([
            'laporan_id' => $lap1->id,
            'administrator_id' => $admin->id,
            'status' => 'diverifikasi',
            'tanggal_verifikasi' => now()->subDays(2)->addHours(3),
        ]);
        Komentar::create([
            'laporan_id' => $lap1->id,
            'user_id' => $warga2->id,
            'isi' => 'Konfirmasi, per jam 16.00 air sudah mulai surut menjadi 20 cm, roda empat sudah bisa melintas perlahan.',
        ]);
        Komentar::create([
            'laporan_id' => $lap1->id,
            'user_id' => $warga1->id,
            'isi' => 'Terima kasih informasinya, mahasiswa yang ingin pulang harap berhati-hati.',
        ]);

        // Laporan 2: Terverifikasi (Kemacetan Simpang Dago - Coblong)
        $lap2 = Laporan::create([
            'user_id' => $warga2->id,
            'kategori_id' => $katMacet->id,
            'latitude' => -6.887900,
            'longitude' => 107.614500,
            'wilayah' => 'Coblong',
            'deskripsi' => 'Kemacetan kronis dari arah Cikapayang menuju Dago Bawah akibat penyempitan jalan dan angkot ngetem sembarangan.',
            'tingkat_keparahan' => 'sedang',
            'status' => 'diverifikasi',
            'created_at' => now()->subDays(3),
        ]);
        Verifikasi::create([
            'laporan_id' => $lap2->id,
            'administrator_id' => $admin->id,
            'status' => 'diverifikasi',
            'tanggal_verifikasi' => now()->subDays(3)->addHours(1),
        ]);
        Komentar::create([
            'laporan_id' => $lap2->id,
            'user_id' => $warga3->id,
            'isi' => 'Sudah ada petugas Dishub yang mengatur di perempatan.',
        ]);

        // Laporan 3: Terverifikasi (Kawasan Kumuh / Padat Sukajadi)
        $lap3 = Laporan::create([
            'user_id' => $warga3->id,
            'kategori_id' => $katPermukiman->id,
            'latitude' => -6.893200,
            'longitude' => 107.595400,
            'wilayah' => 'Sukajadi',
            'deskripsi' => 'Gang pemukiman sangat padat dengan saluran drainase yang tersumbat, minim ventilasi dan akses mobil pemadam kebakaran sulit menjangkau.',
            'tingkat_keparahan' => 'sedang',
            'status' => 'diverifikasi',
            'created_at' => now()->subDays(5),
        ]);
        Verifikasi::create([
            'laporan_id' => $lap3->id,
            'administrator_id' => $admin->id,
            'status' => 'diverifikasi',
            'tanggal_verifikasi' => now()->subDays(4),
        ]);

        // Laporan 4: Terverifikasi (Jalan Rusak Kiaracondong)
        $lap4 = Laporan::create([
            'user_id' => $warga1->id,
            'kategori_id' => $katJalan->id,
            'latitude' => -6.918200,
            'longitude' => 107.649200,
            'wilayah' => 'Kiaracondong',
            'deskripsi' => 'Lubang aspal berdiameter 1 meter dengan kedalaman 15 cm sangat membahayakan pengendara motor saat malam hari minim penerangan.',
            'tingkat_keparahan' => 'tinggi',
            'status' => 'diverifikasi',
            'created_at' => now()->subDays(1),
        ]);
        Verifikasi::create([
            'laporan_id' => $lap4->id,
            'administrator_id' => $admin->id,
            'status' => 'diverifikasi',
            'tanggal_verifikasi' => now()->subHours(8),
        ]);

        // Laporan 5: Pending (Menunggu Verifikasi di Antapani)
        Laporan::create([
            'user_id' => $warga2->id,
            'kategori_id' => $katSampah->id,
            'latitude' => -6.915000,
            'longitude' => 107.658000,
            'wilayah' => 'Antapani',
            'deskripsi' => 'Tumpukan sampah liar menumpuk di bahu jalan raya sepanjang 20 meter menimbulkan aroma tak sedap dan mengundang lalat.',
            'tingkat_keparahan' => 'sedang',
            'status' => 'pending',
            'created_at' => now()->subHours(2),
        ]);

        // Laporan 6: Pending (Menunggu Verifikasi di Lengkong)
        Laporan::create([
            'user_id' => $warga3->id,
            'kategori_id' => $katBanjir->id,
            'latitude' => -6.929000,
            'longitude' => 107.621000,
            'wilayah' => 'Lengkong',
            'deskripsi' => 'Saluran gorong-gorong meluap hingga merendam trotoar dan jalan setinggi 25 cm setiap kali hujan lebih dari 30 menit.',
            'tingkat_keparahan' => 'rendah',
            'status' => 'pending',
            'created_at' => now()->subMinutes(45),
        ]);

        // Laporan 7: Ditolak (Laporan tidak valid / hoax)
        $lapTolak = Laporan::create([
            'user_id' => $warga1->id,
            'kategori_id' => $katBanjir->id,
            'latitude' => -6.917500,
            'longitude' => 107.619100,
            'wilayah' => 'Sumur Bandung',
            'deskripsi' => 'Ada banjir bandang menenggelamkan gedung tinggi di alun-alun Bandung.',
            'tingkat_keparahan' => 'tinggi',
            'status' => 'ditolak',
            'created_at' => now()->subDays(6),
        ]);
        Verifikasi::create([
            'laporan_id' => $lapTolak->id,
            'administrator_id' => $admin->id,
            'status' => 'ditolak',
            'alasan_penolakan' => 'Laporan tidak sesuai dengan kondisi riil di lapangan setelah pengecekan tim patroli. Informasi terindikasi tidak valid.',
            'tanggal_verifikasi' => now()->subDays(6)->addHours(2),
        ]);
    }
}
