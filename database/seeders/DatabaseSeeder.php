<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Komentar;
use App\Models\Laporan;
use App\Models\Lokasi;
use App\Models\Notifikasi;
use App\Models\Transportasi;
use App\Models\User;
use App\Models\Verifikasi;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ──────────────────────────────
        // 1. Akun Pengguna (Multi-Role)
        // ──────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@sigap.id'],
            [
                'name' => 'Administrator SIGAP',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $warga1 = User::firstOrCreate(
            ['email' => 'warga@sigap.id'],
            [
                'name' => 'Ahmad Fauzi (Mahasiswa)',
                'password' => Hash::make('password'),
                'role' => 'warga',
                'is_active' => true,
            ]
        );

        $warga2 = User::firstOrCreate(
            ['email' => 'siti@sigap.id'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password'),
                'role' => 'warga',
                'is_active' => true,
            ]
        );

        $warga3 = User::firstOrCreate(
            ['email' => 'budi@sigap.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'warga',
                'is_active' => true,
            ]
        );

        // ──────────────────────────────
        // 2. Data Master Wilayah (FR-34)
        // ──────────────────────────────
        $wilayahList = [
            ['nama' => 'Coblong', 'tipe' => 'Kecamatan', 'kode_pos' => '40132', 'deskripsi' => 'Wilayah pusat pendidikan, dekat kampus ITB dan Unpad Dipatiukur.'],
            ['nama' => 'Dayeuhkolot', 'tipe' => 'Kecamatan', 'kode_pos' => '40257', 'deskripsi' => 'Wilayah perbatasan selatan, dekat Telkom University, rawan genangan air.'],
            ['nama' => 'Sukajadi', 'tipe' => 'Kecamatan', 'kode_pos' => '40161', 'deskripsi' => 'Kawasan komersial dan pemukiman padat Bandung Utara.'],
            ['nama' => 'Lengkong', 'tipe' => 'Kecamatan', 'kode_pos' => '40261', 'deskripsi' => 'Kawasan pusat kota dengan mobilitas transportasi tinggi.'],
            ['nama' => 'Cidadap', 'tipe' => 'Kecamatan', 'kode_pos' => '40142', 'deskripsi' => 'Kawasan perbukitan dan jalur wisata Ciumbuleuit-Lembang.'],
            ['nama' => 'Kiaracondong', 'tipe' => 'Kecamatan', 'kode_pos' => '40283', 'deskripsi' => 'Kawasan perlintasan kereta api dan pemukiman padat.'],
            ['nama' => 'Antapani', 'tipe' => 'Kecamatan', 'kode_pos' => '40291', 'deskripsi' => 'Kawasan pemukiman terencana di wilayah Bandung Timur.'],
            ['nama' => 'Sumur Bandung', 'tipe' => 'Kecamatan', 'kode_pos' => '40111', 'deskripsi' => 'Jantung kota Bandung (Alun-alun & Asia Afrika).'],
        ];

        foreach ($wilayahList as $w) {
            Wilayah::firstOrCreate(['nama' => $w['nama']], $w);
        }

        // ──────────────────────────────
        // 3. Kategori Laporan (SDG 11 Focus)
        // ──────────────────────────────
        $katMacet = Kategori::firstOrCreate(['nama_kategori' => 'Lalu Lintas / Kemacetan'], [
            'dinas_terkait' => 'Dinas Perhubungan Kabupaten Bandung',
            'ikon' => '🚦',
            'warna' => '#8B5CF6',
        ]);

        $katBanjir = Kategori::firstOrCreate(['nama_kategori' => 'Banjir & Genangan Air'], [
            'dinas_terkait' => 'Badan Penanggulangan Bencana Daerah (BPBD)',
            'ikon' => '🌊',
            'warna' => '#3B82F6',
        ]);

        $katPermukiman = Kategori::firstOrCreate(['nama_kategori' => 'Kawasan Padat / Kumuh'], [
            'dinas_terkait' => 'Dinas Pekerjaan Umum dan Tata Ruang (DPUTR) Kabupaten Bandung',
            'ikon' => '🏘️',
            'warna' => '#10B981',
        ]);

        $katJalan = Kategori::firstOrCreate(['nama_kategori' => 'Jalan Rusak / Berlubang'], [
            'dinas_terkait' => 'Dinas Pekerjaan Umum dan Tata Ruang (DPUTR) Kabupaten Bandung',
            'ikon' => '🕳️',
            'warna' => '#EF4444',
        ]);

        $katSampah = Kategori::firstOrCreate(['nama_kategori' => 'Sampah Liar'], [
            'dinas_terkait' => 'Dinas Lingkungan Hidup Kabupaten Bandung',
            'ikon' => '🗑️',
            'warna' => '#F59E0B',
        ]);

        $katLampu = Kategori::firstOrCreate(['nama_kategori' => 'Lampu PJU Mati'], [
            'dinas_terkait' => 'Dinas Perhubungan Kabupaten Bandung',
            'ikon' => '💡',
            'warna' => '#EAB308',
        ]);

        $this->call(DinasSeeder::class);

        // ──────────────────────────────
        // 4. Data Master Transportasi Umum (FR-21 s/d FR-26)
        // ──────────────────────────────
        $transportasiList = [
            [
                'nama_moda' => 'Trans Metro Bandung (TMB) K-1',
                'tipe' => 'Bus',
                'kode_trayek' => 'TMB Koridor 1',
                'rute_asal' => 'Terminal Cicaheum',
                'rute_tujuan' => 'Terminal Leuwipanjang',
                'titik_pemberhentian' => 'Cicaheum - Ahmad Yani - Kosambi - Alun-Alun Bandung - Kebon Kelapa - Terminal Leuwipanjang',
                'jadwal_operasional' => '05:30 - 20:30 WIB (Setiap 10-15 Menit)',
                'tarif' => 'Rp 4.000',
                'status_operasional' => 'normal',
                'keterangan_operasional' => 'Armada beroperasi penuh (14 bus aktif). Estimasi waktu tempuh lancar.',
                'is_active' => true,
            ],
            [
                'nama_moda' => 'Trans Metro Pasundan (TMP 2D)',
                'tipe' => 'Bus',
                'kode_trayek' => 'TMP Koridor 2',
                'rute_asal' => 'Alun-Alun Bandung',
                'rute_tujuan' => 'Kota Baru Parahyangan (Padalarang)',
                'titik_pemberhentian' => 'Alun-alun Bandung - Sudirman - Rajawali - Cimahi - Padalarang - IKEA Kota Baru Parahyangan',
                'jadwal_operasional' => '05:00 - 21:00 WIB (Setiap 12 Menit)',
                'tarif' => 'Rp 4.900 (Non-Tunai / Tap)',
                'status_operasional' => 'normal',
                'keterangan_operasional' => 'Operasional tepat waktu, pembayaran wajib kartu e-money / QRIS.',
                'is_active' => true,
            ],
            [
                'nama_moda' => 'Feeder KCIC Whoosh Bandung',
                'tipe' => 'Kereta',
                'kode_trayek' => 'Feeder Padalarang-Bandung',
                'rute_asal' => 'Stasiun Hub Padalarang',
                'rute_tujuan' => 'Stasiun Utama Bandung',
                'titik_pemberhentian' => 'Stasiun Padalarang - Stasiun Cimahi - Stasiun Bandung Kota',
                'jadwal_operasional' => '06:15 - 22:30 WIB (Terkoneksi Kedatangan Whoosh)',
                'tarif' => 'Gratis (Termasuk Tiket Whoosh)',
                'status_operasional' => 'normal',
                'keterangan_operasional' => 'Beroperasi mulus, waktu tempuh 18 menit tanpa macet.',
                'is_active' => true,
            ],
            [
                'nama_moda' => 'Angkot Trayek 08 Cicaheum - Leuwipanjang',
                'tipe' => 'Angkot',
                'kode_trayek' => 'AK-08',
                'rute_asal' => 'Terminal Cicaheum',
                'rute_tujuan' => 'Terminal Leuwipanjang',
                'titik_pemberhentian' => 'Cicaheum - Kiara Condong - Buah Batu - Moh. Toha - Leuwipanjang',
                'jadwal_operasional' => '05:00 - 22:00 WIB (Fleksibel)',
                'tarif' => 'Rp 5.000 - Rp 8.000',
                'status_operasional' => 'terkendala',
                'keterangan_operasional' => 'Kepadatan arus lalu lintas tinggi di persimpangan Kiaracondong - Soekarno Hatta.',
                'is_active' => true,
            ],
            [
                'nama_moda' => 'Trans Metro Pasundan (TMP 3D)',
                'tipe' => 'Bus',
                'kode_trayek' => 'TMP Koridor 3',
                'rute_asal' => 'Baleendah',
                'rute_tujuan' => 'BEC (Bandung Electronic Center)',
                'titik_pemberhentian' => 'Baleendah - Dayeuhkolot - Telkom University - Moh Toha - ITC Kebon Kelapa - Purnawarman BEC',
                'jadwal_operasional' => '05:00 - 20:00 WIB (Setiap 15 Menit)',
                'tarif' => 'Rp 4.900',
                'status_operasional' => 'perbaikan',
                'keterangan_operasional' => 'Sebagian armada dialihkan rute alternatif karena genangan air di Dayeuhkolot.',
                'is_active' => true,
            ],
        ];

        foreach ($transportasiList as $t) {
            Transportasi::firstOrCreate(['nama_moda' => $t['nama_moda']], $t);
        }

        // ──────────────────────────────
        // 5. Data Lokasi Kos & Landmark (Existing)
        // ──────────────────────────────
        $lokasi1 = Lokasi::firstOrCreate(['nama' => 'Titik Gerbang 1 Telkom University'], [
            'alamat' => 'Jl. Telekomunikasi No. 1, Sukapura, Dayeuhkolot',
            'wilayah' => 'Dayeuhkolot',
            'latitude' => -6.974026,
            'longitude' => 107.630328,
            'deskripsi' => 'Landmark pintu gerbang utama Telkom University, titik acuan mahasiswa mencari hunian kos di sekitar Sukapura & Bojongsoang.',
            'harga_per_bulan' => null,
            'kontak' => '(022) 7564108',
            'is_active' => true,
        ]);
        $lokasi1->kategoris()->syncWithoutDetaching([$katMacet->id, $katBanjir->id]);

        $lokasi2 = Lokasi::firstOrCreate(['nama' => 'Kos Graha Dago Asri'], [
            'alamat' => 'Jl. Tubagus Ismail VII No. 14, Coblong',
            'wilayah' => 'Coblong',
            'latitude' => -6.885120,
            'longitude' => 107.619840,
            'deskripsi' => 'Kos strategis dekat kampus ITB dan Unpad Dipatiukur, kamar mandi dalam, WiFi fiber optic, bebas banjir.',
            'harga_per_bulan' => '1750000',
            'kontak' => '081223344556',
            'is_active' => true,
        ]);
        $lokasi2->kategoris()->syncWithoutDetaching([$katPermukiman->id]);

        // ──────────────────────────────
        // 6. Data Laporan Masalah Sesuai Kebutuhan Proposal
        // ──────────────────────────────
        // Laporan 1: Selesai Ditangani
        $lap1 = Laporan::updateOrCreate(
            ['latitude' => -6.976500, 'longitude' => 107.631800],
            [
                'user_id' => $warga1->id,
                'kategori_id' => $katBanjir->id,
                'judul' => 'Genangan Air dan Banjir Rendam Jalan Akses Bojongsoang',
                'wilayah' => 'Dayeuhkolot',
                'deskripsi' => 'Genangan air setinggi 40-60 cm merendam badan jalan raya setelah hujan deras selama 2 jam. Kendaraan roda dua mogok dan akses menuju gerbang kampus terhambat.',
                'tingkat_keparahan' => 'tinggi',
                'status' => 'selesai',
                'tindak_lanjut' => 'Dinas SDA dan Bina Marga telah mengoperasikan 2 unit pompa air berkapasitas besar dan normalisasi gorong-gorong. Genangan air telah surut total dan arus lalu lintas kembali lancar.',
                'tanggal_selesai' => now()->subDay(),
                'created_at' => now()->subDays(3),
            ]
        );
        Verifikasi::updateOrCreate(
            ['laporan_id' => $lap1->id],
            [
                'administrator_id' => $admin->id,
                'status' => 'diverifikasi',
                'tanggal_verifikasi' => now()->subDays(3)->addHours(2),
            ]
        );
        Notifikasi::firstOrCreate(
            ['user_id' => $warga1->id, 'laporan_id' => $lap1->id],
            [
                'judul' => 'Laporan Selesai Ditangani',
                'pesan' => 'Laporan Anda mengenai banjir Bojongsoang telah selesai ditindaklanjuti oleh dinas terkait.',
                'is_read' => false,
            ]
        );

        // Laporan 2: Sedang Diproses / Ditindaklanjuti
        $lap2 = Laporan::updateOrCreate(
            ['latitude' => -6.918200, 'longitude' => 107.649200],
            [
                'user_id' => $warga1->id,
                'kategori_id' => $katJalan->id,
                'judul' => 'Aspal Amblas dan Berlubang Bahayakan Pengendara Motor',
                'wilayah' => 'Kiaracondong',
                'deskripsi' => 'Lubang aspal berdiameter 1 meter dengan kedalaman 15 cm sangat membahayakan pengendara motor saat malam hari minim penerangan.',
                'tingkat_keparahan' => 'tinggi',
                'status' => 'diproses',
                'tindak_lanjut' => 'Tim Unit Reaksi Cepat PU Kota Bandung sedang melakukan penambalan aspal sementara dan pemasangan rambu peringatan keselamatan kerja.',
                'created_at' => now()->subDays(2),
            ]
        );
        Verifikasi::updateOrCreate(
            ['laporan_id' => $lap2->id],
            [
                'administrator_id' => $admin->id,
                'status' => 'diverifikasi',
                'tanggal_verifikasi' => now()->subDays(2)->addHours(1),
            ]
        );
        Notifikasi::firstOrCreate(
            ['user_id' => $warga1->id, 'laporan_id' => $lap2->id],
            [
                'judul' => 'Laporan Sedang Diproses',
                'pesan' => 'Laporan perbaikan aspal di Kiaracondong sedang dalam proses penanganan oleh petugas lapangan.',
                'is_read' => false,
            ]
        );

        // Laporan 3: Terverifikasi (Diverifikasi)
        $lap3 = Laporan::updateOrCreate(
            ['latitude' => -6.887900, 'longitude' => 107.614500],
            [
                'user_id' => $warga2->id,
                'kategori_id' => $katMacet->id,
                'judul' => 'Kemacetan Kronis Simpang Cikapayang Menuju Dago Bawah',
                'wilayah' => 'Coblong',
                'deskripsi' => 'Kemacetan kronis dari arah Cikapayang menuju Dago Bawah akibat penyempitan jalan dan angkot ngetem sembarangan.',
                'tingkat_keparahan' => 'sedang',
                'status' => 'diverifikasi',
                'tindak_lanjut' => 'Laporan telah diverifikasi oleh administrator dan diteruskan ke Dinas Perhubungan untuk ploting petugas pengatur arus lalu lintas.',
                'created_at' => now()->subDay(),
            ]
        );
        Verifikasi::updateOrCreate(
            ['laporan_id' => $lap3->id],
            [
                'administrator_id' => $admin->id,
                'status' => 'diverifikasi',
                'tanggal_verifikasi' => now()->subDay()->addHours(3),
            ]
        );

        // Laporan 4: Menunggu Verifikasi (Pending)
        Laporan::updateOrCreate(
            ['latitude' => -6.915000, 'longitude' => 107.658000],
            [
                'user_id' => $warga2->id,
                'kategori_id' => $katSampah->id,
                'judul' => 'Penumpukan Sampah Liar Sepanjang Bahu Jalan Raya',
                'wilayah' => 'Antapani',
                'deskripsi' => 'Tumpukan sampah liar menumpuk di bahu jalan raya sepanjang 20 meter menimbulkan aroma tak sedap dan mengundang lalat.',
                'tingkat_keparahan' => 'sedang',
                'status' => 'pending',
                'created_at' => now()->subHours(3),
            ]
        );

        // Laporan 5: Ditolak
        $lapTolak = Laporan::updateOrCreate(
            ['latitude' => -6.917500, 'longitude' => 107.619100],
            [
                'user_id' => $warga3->id,
                'kategori_id' => $katBanjir->id,
                'judul' => 'Kabar Hoax Banjir Bandang di Alun-Alun',
                'wilayah' => 'Sumur Bandung',
                'deskripsi' => 'Ada banjir bandang menenggelamkan gedung tinggi di alun-alun Bandung.',
                'tingkat_keparahan' => 'tinggi',
                'status' => 'ditolak',
                'created_at' => now()->subDays(4),
            ]
        );
        Verifikasi::updateOrCreate(
            ['laporan_id' => $lapTolak->id],
            [
                'administrator_id' => $admin->id,
                'status' => 'ditolak',
                'alasan_penolakan' => 'Hasil verifikasi faktual tim patroli lapangan menunjukkan informasi tidak akurat / hoax.',
                'tanggal_verifikasi' => now()->subDays(4)->addHours(1),
            ]
        );
    }
}
