<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataPegawai;
use Illuminate\Support\Facades\Hash;

class DataPegawaiSeeder extends Seeder
{
    public function run()
    {
        DataPegawai::create([
            'namaPegawai' => 'Randi Afif',
            'nipPegawai' => 12345678,
            'password' => Hash::make('password123'), // Password default
            'status' => 'umum', // Status default
        ]);

        DataPegawai::create([
            'namaPegawai' => 'Admin User',
            'nipPegawai' => 87654321,
            'password' => Hash::make('adminpass'),
            'status' => 'admin', // Status admin
        ]);
    }
}
