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
        Schema::table('transaksi_barang', function (Blueprint $table) {
            // Remove the 'statustransaksi' column from the 'transaksi_barang' table
            $table->dropColumn('statustransaksi');
        });
        Schema::table('transaksi_kendaraan', function (Blueprint $table) {
            // Remove the 'statustransaksi' column from the 'transaksi_barang' table
            $table->dropColumn('statustransaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_barang', function (Blueprint $table) {
            // Add the 'statustransaksi' column back to the 'transaksi_barang' table
            $table->string('statustransaksi')->nullable();
        });
        Schema::table('transaksi_kendaraan', function (Blueprint $table) {
            // Add the 'statustransaksi' column back to the 'transaksi_kendaraan' table
            $table->string('statustransaksi')->nullable();
        });
    }
};
