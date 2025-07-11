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
            // Make the 'status' column nullable
            $table->string('status')->nullable()->change();

            // If you want to add a default value, you can uncomment the line below
            // $table->string('status')->default('available')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_kendaraan', function (Blueprint $table) {
            // Revert the 'status' column to not nullable
            $table->string('status')->nullable(false)->change();

            // If you had a default value before, you can uncomment the line below
            // $table->string('status')->default('available')->change();
        });
    }
};
