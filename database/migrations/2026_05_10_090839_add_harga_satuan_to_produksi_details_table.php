<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produksi_details', function (Blueprint $table) {

            $table->integer('harga_satuan')->default(0);

            $table->integer('subtotal')->default(0);

        });
    }

    public function down(): void
    {
        Schema::table('produksi_details', function (Blueprint $table) {

            $table->dropColumn([
                'harga_satuan',
                'subtotal'
            ]);

        });
    }
};