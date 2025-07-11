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
        Schema::create('data_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('jenisKendaraan');
            $table->string('namaKendaraan');
            $table->integer('jumlahTotal');
            $table->integer('jumlahTersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_kendaraan');
    }
};
