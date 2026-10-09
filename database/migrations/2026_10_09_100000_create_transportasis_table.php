<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transportasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_moda'); // contoh: Trans Metro Bandung, Angkot 08, TMP Koridor 2
            $table->enum('tipe', ['Bus', 'Angkot', 'Kereta', 'Feeder'])->default('Bus');
            $table->string('kode_trayek'); // contoh: TMB-01, TMP-2D, AK-08
            $table->string('rute_asal');
            $table->string('rute_tujuan');
            $table->text('titik_pemberhentian'); // daftar halte/titik singgah
            $table->string('jadwal_operasional'); // contoh: 05:30 - 21:00 WIB
            $table->string('tarif')->default('Rp 4.000');
            $table->enum('status_operasional', ['normal', 'terkendala', 'perbaikan', 'tidak_beroperasi'])->default('normal');
            $table->text('keterangan_operasional')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transportasis');
    }
};
