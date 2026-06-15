<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pegawai', 'gaji')) {

            Schema::table('pegawai', function (Blueprint $table) {
                $table->integer('gaji')->default(0);
            });

        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pegawai', 'gaji')) {

            Schema::table('pegawai', function (Blueprint $table) {
                $table->dropColumn('gaji');
            });

        }
    }
};