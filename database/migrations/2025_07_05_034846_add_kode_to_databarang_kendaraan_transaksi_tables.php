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
        Schema::table('data_barang', function (Blueprint $table) {
            $table->string('kode')->nullable();
        });

        Schema::table('data_kendaraan', function (Blueprint $table) {
            $table->string('kode')->nullable();
        });

        Schema::table('transaksi_barang', function (Blueprint $table) {
            $table->string('kode')->nullable();
        });
        Schema::table('transaksi_kendaraan', function (Blueprint $table) {
            $table->string('kode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_barang', function (Blueprint $table) {
            $table->dropColumn('kode');
        });

        Schema::table('data_kendaraan', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
        Schema::table('transaksi_barang', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
        Schema::table('transaksi_kendaraan', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
    }
};
