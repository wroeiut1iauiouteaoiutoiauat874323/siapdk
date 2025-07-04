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
        Schema::table('transaksi_kendaraan', function (Blueprint $table) {
            // Drop the foreign key constraint first if it exists, then drop the column
            if (Schema::hasColumn('transaksi_kendaraan', 'idDataPegawai')) {
                $table->dropForeign(['idDataPegawai']);
                $table->dropColumn('idDataPegawai');
            }

            $table->dropColumn('jumlahPinjam');
            // Add the 'nama_pegawai' and 'status_pegawai' columns
            $table->string('nama_pegawai')->after('jenisTransaksi')->nullable();
            $table->string('status_pegawai')->after('nama_pegawai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_kendaraan', function (Blueprint $table) {
            // Drop the 'nama_pegawai' and 'status_pegawai' columns
            $table->dropColumn(['nama_pegawai', 'status_pegawai']);
            // Re-add the 'jumlahPinjam' column
            $table->unsignedInteger('jumlahPinjam')->after('jenisTransaksi')->nullable();

            // Re-add the 'idDataPegawai' column with foreign key constraint
            $table->unsignedBigInteger('idDataPegawai')->after('jenisTransaksi')->nullable();
            $table->foreign('idDataPegawai')->references('id')->on('data_pegawai')->onDelete('cascade');
        });
    }
};
