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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('no_nota')->unique();
            $table->date('tanggal');

            $table->unsignedBigInteger('pelanggan_id');

            $table->foreign('pelanggan_id')
            ->references('id_pelanggan')
            ->on('pelanggan')
            ->cascadeOnDelete();

            $table->enum('metode_pembayaran', ['cash', 'qris'])->default('cash');
            $table->enum('status', ['diproses', 'selesai'])->default('diproses');

            // untuk custom order
            $table->text('request_custom')->nullable();
            $table->dateTime('estimasi_selesai')->nullable();

            $table->integer('total_harga')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
