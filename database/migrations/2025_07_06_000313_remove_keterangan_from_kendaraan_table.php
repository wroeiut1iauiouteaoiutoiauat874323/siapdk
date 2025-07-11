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
        Schema::table('data_kendaraan', function (Blueprint $table) {
            // Remove the 'keterangan' column from the 'kendaraan' table
            $table->dropColumn('keterangan');
            $table->string('lokasi')->nullable();
        });
        Schema::table('data_barang', function (Blueprint $table) {
            $table->string('lokasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_kendaraan', function (Blueprint $table) {
            // Recreate the 'keterangan' column in the 'kendaraan' table
            $table->string('keterangan')->nullable();
            $table->dropColumn('lokasi');
        });
        Schema::table('data_barang', function (Blueprint $table) {
            // Remove the 'lokasi' column from the 'barang' table
            $table->dropColumn('lokasi');
        });
    }
};
