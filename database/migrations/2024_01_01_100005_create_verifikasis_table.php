<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporans')->onDelete('cascade');
            $table->foreignId('administrator_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['diverifikasi', 'ditolak']);
            $table->text('alasan_penolakan')->nullable();
            $table->timestamp('tanggal_verifikasi')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasis');
    }
};
