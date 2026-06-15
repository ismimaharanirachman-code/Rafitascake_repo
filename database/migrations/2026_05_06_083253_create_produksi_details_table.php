<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produksi_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('produksi_id')
                ->constrained('produksi')
                ->cascadeOnDelete();

            $table->foreignId('bahan_baku_id')
                ->constrained('bahan_bakus')
                ->cascadeOnDelete();

            $table->double('jumlah_pakai');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produksi_details');
    }
};