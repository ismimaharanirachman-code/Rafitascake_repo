<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualan_produk', function (Blueprint $table) {
            $table->integer('subtotal')->after('harga');
        });
    }

    public function down(): void
    {
        Schema::table('penjualan_produk', function (Blueprint $table) {
            $table->dropColumn('subtotal');
        });
    }
};