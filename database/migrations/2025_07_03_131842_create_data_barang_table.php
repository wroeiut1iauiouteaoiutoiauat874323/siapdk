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
        Schema::create('data_barang', function (Blueprint $table) {
            $table->id();
            $table->string('jenisBarangPersediaan')->nullable();
            $table->string('namaBarang')->nullable();
            $table->integer('jumlahTotal')->nullable();
            $table->integer('jumlahTersedia')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_barang');
    }
};
