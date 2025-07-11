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
            $table->string('nip')->nullable()->after('id');
        });

        Schema::table('transaksi_kendaraan', function (Blueprint $table) {
            $table->string('nip')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_barang', function (Blueprint $table) {
            $table->dropColumn('nip');
        });
        Schema::table('transaksi_kendaraan', function (Blueprint $table) {
            $table->dropColumn('nip');
        });
    }
};
