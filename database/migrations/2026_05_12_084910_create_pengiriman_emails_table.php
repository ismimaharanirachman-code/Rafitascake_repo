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
        Schema::create('pengiriman_email', function (Blueprint $table) {
            $table->id();

            // relasi ke tabel penjualan
            $table->foreignId('penjualan_id')
                  ->constrained('penjualans')
                  ->onDelete('cascade');

            // status email
            $table->enum('status', [
                'pending',
                'terkirim',
                'gagal'
            ])->default('pending');

            // waktu pengiriman
            $table->dateTime('tgl_pengiriman_pesan')
                  ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_email');
    }
};