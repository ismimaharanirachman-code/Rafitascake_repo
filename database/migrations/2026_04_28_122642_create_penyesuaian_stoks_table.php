<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('produk', 'stok')) {
            Schema::table('produk', function (Blueprint $table) {
                $table->integer('stok')->default(0)->after('harga_jual');
            });
        }

        if (!Schema::hasTable('penyesuaian_stok')) {
            Schema::create('penyesuaian_stok', function (Blueprint $table) {
                $table->id();
                $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
                $table->enum('tipe', ['koreksi']);
                $table->integer('jumlah');
                $table->integer('stok_sebelum');
                $table->integer('stok_sesudah');
                $table->string('keterangan')->nullable();
                $table->string('nomor_referensi')->unique()->nullable();
                $table->foreignId('dibuat_oleh')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('penyesuaian_stok');

        if (Schema::hasColumn('produk', 'stok')) {
            Schema::table('produk', function (Blueprint $table) {
                $table->dropColumn('stok');
            });
        }
    }
};