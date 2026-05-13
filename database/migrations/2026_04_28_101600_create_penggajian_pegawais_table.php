<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penggajian_pegawais', function (Blueprint $table) {
            $table->id('id_penggajian');
            $table->unsignedBigInteger('id_pegawai');
            $table->date('tanggal_gaji');
            $table->string('periode_gaji');
            $table->decimal('gaji_pokok', 12, 2);
            $table->decimal('tunjangan', 12, 2)->default(0);
            $table->decimal('potongan', 12, 2)->default(0);
            $table->decimal('total_gaji', 12, 2);
            $table->string('metode_pembayaran');
            $table->string('status_pembayaran');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Sesuai dengan tabel 'pegawai' dan kolom 'id_pegawai' kamu
            $table->foreign('id_pegawai')
                  ->references('id_pegawai')
                  ->on('pegawai') 
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penggajian_pegawais');
    }
};