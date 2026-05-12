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
        Schema::create('detail_pembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_bahan_baku_id')
                  ->constrained('pembelian_bahan_baku')
                  ->onDelete('cascade');
            $table->foreignId('bahan_baku_id')
                  ->constrained('bahan_bakus');
            $table->integer('qty');
            $table->bigInteger('harga');
            $table->bigInteger('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian');
    }
};