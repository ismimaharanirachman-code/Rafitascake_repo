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
        Schema::table('beban_operasionals', function (Blueprint $table) {
          //$table->date('tanggal')->nullable();
          //$table->string('kode_akun')->nullable();
          // $table->string('keterangan')->nullable();
          //$table->decimal('nominal', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('beban_operasionals', function (Blueprint $table) {
        $table->dropColumn([
            'tanggal',
            'kode_akun',
            'keterangan',
            'nominal'
        ]);
    });
}
};