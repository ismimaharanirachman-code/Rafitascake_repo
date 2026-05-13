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
        Schema::table('produksi', function (Blueprint $table) {

            if (!Schema::hasColumn('produksi', 'nama_produk')) {
                $table->string('nama_produk')->after('id');
            }

            if (!Schema::hasColumn('produksi', 'qty_produksi')) {
                $table->integer('qty_produksi')->after('nama_produk');
            }

            if (!Schema::hasColumn('produksi', 'tanggal_produksi')) {
                $table->date('tanggal_produksi')->after('qty_produksi');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produksi', function (Blueprint $table) {

            $columns = [];

            if (Schema::hasColumn('produksi', 'nama_produk')) {
                $columns[] = 'nama_produk';
            }

            if (Schema::hasColumn('produksi', 'qty_produksi')) {
                $columns[] = 'qty_produksi';
            }

            if (Schema::hasColumn('produksi', 'tanggal_produksi')) {
                $columns[] = 'tanggal_produksi';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }

        });
    }
};