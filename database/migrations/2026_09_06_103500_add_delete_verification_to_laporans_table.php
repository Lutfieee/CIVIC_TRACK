<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->string('status_penghapusan')->nullable()->after('status'); // 'menunggu_verifikasi', 'ditolak_hapus'
            $table->text('alasan_penghapusan')->nullable()->after('status_penghapusan');
            $table->timestamp('tanggal_pengajuan_hapus')->nullable()->after('alasan_penghapusan');
        });
    }

    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn(['status_penghapusan', 'alasan_penghapusan', 'tanggal_pengajuan_hapus']);
        });
    }
};
