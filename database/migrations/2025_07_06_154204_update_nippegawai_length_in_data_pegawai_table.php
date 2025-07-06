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
        Schema::table('data_pegawai', function (Blueprint $table) {
            $table->string('nipPegawai', 40)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_pegawai', function (Blueprint $table) {
            $table->string('nipPegawai', 11)->change();
        });
    }
};
