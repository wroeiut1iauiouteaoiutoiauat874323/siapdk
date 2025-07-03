<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataBarang;

class DataBarangSeeder extends Seeder
{
    public function run()
    {
        DataBarang::create([
            'jenisBarangPersediaan' => 'ATK',
            'namaBarang' => 'Pulpen',
            'jumlahTotal' => 100,
            'jumlahTersedia' => 80,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'Elektronik',
            'namaBarang' => 'Proyektor',
            'jumlahTotal' => 5,
            'jumlahTersedia' => 3,
        ]);
    }
}
