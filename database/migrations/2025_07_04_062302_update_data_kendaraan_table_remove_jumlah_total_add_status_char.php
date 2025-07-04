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
        Schema::table('data_kendaraan', function (Blueprint $table) {
            $table->dropColumn('jumlahTotal');
            $table->dropColumn('jumlahTersedia');

            $table->char('status', 20)->after('nomorPolisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_kendaraan', function (Blueprint $table) {
            $table->integer('jumlahTotal')->default(0)->after('nomorPolisi');
            $table->integer('jumlahTersedia')->default(0)->after('jumlahTotal');

            $table->dropColumn('status');
        });
    }
};
