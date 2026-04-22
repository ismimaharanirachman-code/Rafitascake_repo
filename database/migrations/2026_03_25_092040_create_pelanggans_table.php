<?php
//
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
        Schema::create('pegawais', function (Blueprint $table) {
    $table->string('kode_pegawai', 10)->primary(); // ganti di sini
    
    $table->string('nama_pegawai', 50);
    $table->string('jabatan', 50);
    $table->string('alamat_pegawai', 100);
    $table->string('no_hp', 20);
    
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
