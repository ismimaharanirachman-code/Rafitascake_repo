<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    // Membuat tabel KTP
    Schema::create('ktp', function ($table) {
        $table->id();
        $table->string('nik')->unique();
        $table->string('nama');
        $table->string('tempat_lahir')->nullable();
        $table->date('tanggal_lahir')->nullable();
        $table->text('alamat');
        $table->timestamps();
    });

    // Membuat tabel Pegawai
    Schema::create('pegawai', function ($table) {
        $table->string('id_pegawai')->primary();
        $table->string('nama_pegawai');
        $table->text('alamat_pegawai');
        $table->string('no_hp');
        $table->string('id_jabatan');
        $table->foreignId('ktp_id')->constrained('ktp');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ktp_and_pegawai_tables');
    }
};
