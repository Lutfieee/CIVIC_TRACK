<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom dinas_terkait pada tabel kategoris
        Schema::table('kategoris', function (Blueprint $table) {
            if (!Schema::hasColumn('kategoris', 'dinas_terkait')) {
                $table->string('dinas_terkait')->nullable()->after('nama_kategori');
            }
        });

        // 2. Modifikasi kolom role dan tambah instansi pada tabel users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'instansi')) {
                $table->string('instansi')->nullable()->after('role');
            }
        });

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('warga', 'admin', 'dinas') NOT NULL DEFAULT 'warga'");
        }
    }

    public function down(): void
    {
        Schema::table('kategoris', function (Blueprint $table) {
            if (Schema::hasColumn('kategoris', 'dinas_terkait')) {
                $table->dropColumn('dinas_terkait');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'instansi')) {
                $table->dropColumn('instansi');
            }
        });

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('warga', 'admin') NOT NULL DEFAULT 'warga'");
        }
    }
};
