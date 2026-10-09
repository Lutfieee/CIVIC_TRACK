<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Laporan;
use App\Models\Transportasi;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProposalFunctionalRequirementsTest extends TestCase
{
    use DatabaseMigrations;

    protected User $admin;
    protected User $warga;
    protected Kategori $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin_test@sigap.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->warga = User::create([
            'name' => 'Warga Test',
            'email' => 'warga_test@sigap.id',
            'password' => bcrypt('password'),
            'role' => 'warga',
            'is_active' => true,
        ]);

        $this->kategori = Kategori::create([
            'nama_kategori' => 'Uji Coba',
            'ikon' => '🧪',
            'warna' => '#10B981',
            'slug' => 'uji-coba',
        ]);
    }

    /** FR-5: Melihat Dashboard Utama (Publik) */
    public function test_pengunjung_bisa_melihat_dashboard_utama(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Dasbor Pelaporan Masalah Wilayah');
    }

    /** FR-21, FR-22, FR-26: Informasi & Pencarian Transportasi (Publik) */
    public function test_pengunjung_bisa_melihat_dan_mencari_transportasi(): void
    {
        $transport = Transportasi::create([
            'nama_moda' => 'Bus Uji Coba K-1',
            'tipe' => 'Bus',
            'kode_trayek' => 'BUS-01',
            'rute_asal' => 'Terminal A',
            'rute_tujuan' => 'Terminal B',
            'titik_pemberhentian' => 'Halte 1 - Halte 2 - Halte 3',
            'jadwal_operasional' => '06:00 - 20:00 WIB',
            'tarif' => 'Rp 4.000',
            'status_operasional' => 'normal',
            'is_active' => true,
        ]);

        $response = $this->get(route('transportasi.index'));
        $response->assertStatus(200);
        $response->assertSee('Bus Uji Coba K-1');

        // Pencarian rute (FR-22)
        $searchResponse = $this->get(route('transportasi.index', ['q' => 'Terminal A']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Terminal A');

        // Detail rute (FR-23)
        $detailResponse = $this->get(route('transportasi.show', $transport->id));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Halte 1');
    }

    /** FR-32: Menampilkan Informasi Layanan (Publik) */
    public function test_pengunjung_bisa_melihat_informasi_layanan(): void
    {
        $response = $this->get(route('informasi-layanan.index'));
        $response->assertStatus(200);
        $response->assertSee('Panduan Penggunaan Layanan SIGAP');
    }

    /** FR-4: Manajemen Profil (Warga & Admin) */
    public function test_warga_bisa_mengakses_dan_mengubah_profil(): void
    {
        $response = $this->actingAs($this->warga)->get(route('profil.edit'));
        $response->assertStatus(200);

        $updateResponse = $this->actingAs($this->warga)->put(route('profil.update'), [
            'name' => 'Warga Test Updated',
            'email' => $this->warga->email,
        ]);
        $updateResponse->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['id' => $this->warga->id, 'name' => 'Warga Test Updated']);
    }

    /** FR-6, FR-7, FR-8: Membuat Laporan Masalah (Warga) */
    public function test_warga_bisa_membuat_laporan_masalah(): void
    {
        $response = $this->actingAs($this->warga)->post(route('warga.laporan.store'), [
            'judul' => 'Jalan Rusak Berlubang di Depan Balai Desa',
            'kategori_id' => $this->kategori->id,
            'latitude' => -6.912345,
            'longitude' => 107.612345,
            'wilayah' => 'Coblong',
            'deskripsi' => 'Terdapat lubang besar sedalam 15 cm yang sangat membahayakan pengendara motor saat malam hari.',
            'tingkat_keparahan' => 'tinggi',
        ]);

        $response->assertRedirect(route('warga.laporan.index'));
        $this->assertDatabaseHas('laporans', [
            'user_id' => $this->warga->id,
            'judul' => 'Jalan Rusak Berlubang di Depan Balai Desa',
            'status' => 'pending',
        ]);
    }

    /** FR-14, FR-15, FR-29: Admin Memperbarui Status, Mencatat Tindak Lanjut, dan Notifikasi Otomatis */
    public function test_admin_bisa_update_status_tindak_lanjut_dan_mengirim_notifikasi(): void
    {
        $laporan = Laporan::create([
            'user_id' => $this->warga->id,
            'kategori_id' => $this->kategori->id,
            'judul' => 'Pipa Saluran Air Pecah',
            'latitude' => -6.9123,
            'longitude' => 107.6123,
            'wilayah' => 'Coblong',
            'deskripsi' => 'Air meluap ke badan jalan dari pipa yang bocor.',
            'tingkat_keparahan' => 'sedang',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.laporan.update-status', $laporan->id), [
            'status' => 'diproses',
            'tindak_lanjut' => 'Petugas PDAM sedang melakukan perbaikan pipa di lokasi.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('laporans', [
            'id' => $laporan->id,
            'status' => 'diproses',
            'tindak_lanjut' => 'Petugas PDAM sedang melakukan perbaikan pipa di lokasi.',
        ]);

        // Cek notifikasi terkirim ke warga (FR-29)
        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $this->warga->id,
            'laporan_id' => $laporan->id,
        ]);
    }

    /** FR-31: Ekspor Data Laporan (Admin) */
    public function test_admin_bisa_mengekspor_data_laporan_ke_csv(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.laporan.export'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    /** FR-28: Pengunjung / Warga Tidak Boleh Mengakses Rute Admin */
    public function test_pembatasan_hak_akses_admin(): void
    {
        // Pengunjung (guest) dialihkan ke login
        $guestResponse = $this->get(route('admin.dashboard'));
        $guestResponse->assertRedirect(route('login'));

        // Warga dilarang akses rute admin (403 Forbidden)
        $wargaResponse = $this->actingAs($this->warga)->get(route('admin.dashboard'));
        $wargaResponse->assertStatus(403);
    }
}
