<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            if (!Schema::hasColumn('laporans', 'judul')) {
                $table->string('judul')->nullable()->after('kategori_id');
            }
            if (!Schema::hasColumn('laporans', 'tindak_lanjut')) {
                $table->text('tindak_lanjut')->nullable()->after('status');
            }
            if (!Schema::hasColumn('laporans', 'tanggal_selesai')) {
                $table->timestamp('tanggal_selesai')->nullable()->after('tindak_lanjut');
            }
        });

        // Perluas opsi status penanganan laporan sesuai tahapan di proposal
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE laporans MODIFY status ENUM('pending', 'diverifikasi', 'diproses', 'selesai', 'ditolak') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn(['judul', 'tindak_lanjut', 'tanggal_selesai']);
        });

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE laporans MODIFY status ENUM('pending', 'diverifikasi', 'ditolak') NOT NULL DEFAULT 'pending'");
        }
    }
};
