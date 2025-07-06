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
            // Remove the 'jumlah_tersedia' column from the 'dataBarang' table
            $table->dropColumn('jumlahTersedia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_barang', function (Blueprint $table) {
            // Recreate the 'jumlah_tersedia' column in the 'dataBarang' table
            $table->integer('jumlahTersedia')->nullable();
        });
    }
};
