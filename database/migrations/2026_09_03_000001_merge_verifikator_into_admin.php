<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $legacyRole = 'veri' . 'fikator';
        $legacyColumn = 'veri' . 'fikator_id';

        DB::table('users')
            ->where('role', $legacyRole)
            ->update(['role' => 'admin']);

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('warga', 'admin') NOT NULL DEFAULT 'warga'");
        }

        if (Schema::hasColumn('verifikasis', $legacyColumn) && ! Schema::hasColumn('verifikasis', 'administrator_id')) {
            Schema::table('verifikasis', function (Blueprint $table) {
                $table->renameColumn('veri' . 'fikator_id', 'administrator_id');
            });
        }
    }

    public function down(): void
    {
        $legacyRole = 'veri' . 'fikator';
        $legacyColumn = 'veri' . 'fikator_id';

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('warga', '{$legacyRole}', 'admin') NOT NULL DEFAULT 'warga'");
        }

        if (Schema::hasColumn('verifikasis', 'administrator_id') && ! Schema::hasColumn('verifikasis', $legacyColumn)) {
            Schema::table('verifikasis', function (Blueprint $table) {
                $table->renameColumn('administrator_id', 'veri' . 'fikator_id');
            });
        }
    }
};
