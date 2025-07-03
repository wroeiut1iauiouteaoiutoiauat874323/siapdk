<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataKendaraan;

class DataKendaraanSeeder extends Seeder
{
    public function run()
    {
        DataKendaraan::create([
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Honda Beat',
            'jumlahTotal' => 10,
            'jumlahTersedia' => 7,
        ]);

        DataKendaraan::create([
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Toyota Avanza',
            'jumlahTotal' => 3,
            'jumlahTersedia' => 2,
        ]);
    }
}
