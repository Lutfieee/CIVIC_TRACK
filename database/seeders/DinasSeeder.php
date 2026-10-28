<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DinasSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Update mapping kategori ke dinas
        DB::table('kategoris')->where('nama_kategori', 'like', '%Sampah%')
            ->update(['dinas_terkait' => 'Dinas Lingkungan Hidup Kabupaten Bandung']);

        DB::table('kategoris')->where('nama_kategori', 'like', '%Jalan%')
            ->orWhere('nama_kategori', 'like', '%Padat%')
            ->orWhere('nama_kategori', 'like', '%Kumuh%')
            ->update(['dinas_terkait' => 'Dinas Pekerjaan Umum dan Tata Ruang (DPUTR) Kabupaten Bandung']);

        DB::table('kategoris')->where('nama_kategori', 'like', '%Banjir%')
            ->update(['dinas_terkait' => 'Badan Penanggulangan Bencana Daerah (BPBD)']);

        DB::table('kategoris')->where('nama_kategori', 'like', '%Lalu Lintas%')
            ->orWhere('nama_kategori', 'like', '%Lampu%')
            ->orWhere('nama_kategori', 'like', '%Kemacetan%')
            ->update(['dinas_terkait' => 'Dinas Perhubungan Kabupaten Bandung']);

        // Set default bagi yang belum terisi
        DB::table('kategoris')->whereNull('dinas_terkait')
            ->update(['dinas_terkait' => 'Dinas Pekerjaan Umum dan Tata Ruang (DPUTR) Kabupaten Bandung']);

        // 2. Buat akun Aktor Dinas untuk masing-masing instansi
        $dinasAccounts = [
            [
                'name' => 'Petugas DLH Kab. Bandung',
                'email' => 'dlh@bandung.go.id',
                'password' => Hash::make('password'),
                'role' => 'dinas',
                'instansi' => 'Dinas Lingkungan Hidup Kabupaten Bandung',
                'is_active' => true,
            ],
            [
                'name' => 'Petugas DPUTR Kab. Bandung',
                'email' => 'dputr@bandung.go.id',
                'password' => Hash::make('password'),
                'role' => 'dinas',
                'instansi' => 'Dinas Pekerjaan Umum dan Tata Ruang (DPUTR) Kabupaten Bandung',
                'is_active' => true,
            ],
            [
                'name' => 'Petugas BPBD Kab. Bandung',
                'email' => 'bpbd@bandung.go.id',
                'password' => Hash::make('password'),
                'role' => 'dinas',
                'instansi' => 'Badan Penanggulangan Bencana Daerah (BPBD)',
                'is_active' => true,
            ],
            [
                'name' => 'Petugas Dishub Kab. Bandung',
                'email' => 'dishub@bandung.go.id',
                'password' => Hash::make('password'),
                'role' => 'dinas',
                'instansi' => 'Dinas Perhubungan Kabupaten Bandung',
                'is_active' => true,
            ],
        ];

        foreach ($dinasAccounts as $acc) {
            User::updateOrCreate(
                ['email' => $acc['email']],
                $acc
            );
        }
    }
}
