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
            $table->string('status')->default('umum')->after('password');
            // default 'bukan' artinya pegawai biasa, bisa kamu sesuaikan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_pegawai', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
