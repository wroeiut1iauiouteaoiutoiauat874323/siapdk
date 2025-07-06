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
            'jabatan' => 'CPNS', // Jabatan default
        ]);

        DataPegawai::create(attributes: [
            'namaPegawai' => 'Pegawai',
            'nipPegawai' => 87654321,
            'password' => Hash::make('password4321'), // Password default
            'status' => 'bukanumum', // Status admin
            'jabatan' => 'PNS', // Jabatan default
        ]);
    }
}
