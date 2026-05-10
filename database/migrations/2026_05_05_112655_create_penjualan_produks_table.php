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
        Schema::create('penjualan_produk', function (Blueprint $table) {
            $table->id();

            $table->foreignId('penjualan_id')->constrained('penjualan')->cascadeOnDelete();
            $table->unsignedBigInteger('produk_id');

            $table->foreign('produk_id')
                ->references('id')
                ->on('produk')
                ->cascadeOnDelete();

            $table->integer('qty');
            $table->integer('harga');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_produks');
    }
};
