<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pegawai', 'ktp_id')) {
            Schema::table('pegawai', function (Blueprint $table) {
                // 1. Buat kolomnya dulu dengan tipe data Big Integer Unsigned (harus sama dengan primary key target)
                $table->unsignedBigInteger('ktp_id')->nullable();

                // 2. Tentukan foreign key secara manual mendeteksi nama tabel target yang benar
                // Cek jika tabelnya bernama 'ktps' (jamak), jika tidak pakai 'ktp' (tunggal)
                $targetTable = Schema::hasTable('ktps') ? 'ktps' : 'ktp';

                $table->foreign('ktp_id')
                      ->references('id') // Asumsi nama primary key di tabel KTP adalah 'id'
                      ->on($targetTable)
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pegawai', 'ktp_id')) {
            Schema::table('pegawai', function (Blueprint $table) {
                $table->dropForeign(['ktp_id']);
                $table->dropColumn('ktp_id');
            });
        }
    }
};