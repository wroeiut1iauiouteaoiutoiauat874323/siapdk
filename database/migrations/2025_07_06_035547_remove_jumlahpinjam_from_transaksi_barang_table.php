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
            // Menghapus kolom jumlahpinjam
            $table->dropColumn('jumlahPinjam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_barang', function (Blueprint $table) {
            // Menambahkan kembali kolom jumlahpinjam
            $table->integer('jumlahPinjam')->nullable()->after('idDataBarang');
        });
    }
};
