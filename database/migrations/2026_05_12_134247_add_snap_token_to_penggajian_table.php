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
    Schema::table('penggajian_pegawais', function (Blueprint $table) {
        // Hapus baris status_pembayaran karena sudah ada di database
        $table->string('snap_token')->nullable()->after('total_gaji');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penggajian_pegawais', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'status_pembayaran']);
        });
    }
};