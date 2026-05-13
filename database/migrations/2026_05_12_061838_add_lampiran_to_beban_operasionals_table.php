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
        $table->string('lampiran')->nullable();
    });
}
    public function down(): void
    {
        Schema::table('beban_operasionals', function (Blueprint $table) {
            //
        });
    }
};
