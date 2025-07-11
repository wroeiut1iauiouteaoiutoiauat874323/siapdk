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
            // Drop the foreign key constraint and the column 'data_barang_id'
            $table->dropForeign(['idDataPegawai']);
            $table->dropColumn('idDataPegawai');

            // Add new columns 'nama_pegawai' and 'status_pegawai'
            $table->string('nama_pegawai')->nullable();
            $table->string('status_pegawai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_barang', function (Blueprint $table) {
            // Drop the new columns 'nama_pegawai' and 'status_pegawai'
            $table->dropColumn(['nama_pegawai', 'status_pegawai']);

            // Re-add the foreign key constraint and the column 'data_barang_id'
            $table->unsignedBigInteger(column: 'idDataPegawai')->nullable();
            $table->foreign('idDataPegawai')->references('id')->on('data_barang')->onDelete('cascade');
        });
    }
};
