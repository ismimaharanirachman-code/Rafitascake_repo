<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('produksi', function (Blueprint $table) {
        $table->string('nama_produk')->after('id');
        $table->integer('qty_produksi')->after('nama_produk');
        $table->date('tanggal_produksi')->after('qty_produksi');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produksi', function (Blueprint $table) {
            //
        });
    }
};
