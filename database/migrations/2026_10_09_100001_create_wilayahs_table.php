<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // contoh: Coblong, Sukajadi, Dayeuhkolot
            $table->enum('tipe', ['Kecamatan', 'Kelurahan', 'Desa', 'Kota/Kabupaten'])->default('Kecamatan');
            $table->string('kode_pos', 10)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayahs');
    }
};
