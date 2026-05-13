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
            Schema::create('beban_operasionals', function (Blueprint $table) {
                    $table->id();
                    $table->date('tanggal')->nullable();
                    $table->string('kode_akun')->nullable();
                    $table->text('keterangan')->nullable();
                    $table->decimal('nominal', 15, 2)->nullable();
                    $table->string('lampiran')->nullable();
                    $table->string('coa_id')->nullable();
                    $table->timestamps();
});
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beban_operasionals', function (Blueprint $table) {

        });
    }
};