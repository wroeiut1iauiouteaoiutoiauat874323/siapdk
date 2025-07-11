<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transaksi_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idDataKendaraan')->constrained('data_kendaraan')->onDelete('cascade');
            $table->foreignId('idDataPegawai')->constrained('data_pegawai')->onDelete('cascade');
            $table->string('jenisTransaksi');
            $table->integer('jumlahPinjam');
            $table->date('tanggalPinjam');
            $table->date('tanggalDikembalikan')->nullable();
            $table->string('statusTransaksi');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_kendaraan');
    }
};
