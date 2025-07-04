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

        DataBarang::create([
            'jenisBarangPersediaan' => 'ATK',
            'namaBarang' => 'Pensil',
            'jumlahTotal' => 150,
            'jumlahTersedia' => 120,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'ATK',
            'namaBarang' => 'Penghapus',
            'jumlahTotal' => 80,
            'jumlahTersedia' => 60,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'ATK',
            'namaBarang' => 'Spidol',
            'jumlahTotal' => 60,
            'jumlahTersedia' => 40,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'Elektronik',
            'namaBarang' => 'Laptop',
            'jumlahTotal' => 10,
            'jumlahTersedia' => 7,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'Elektronik',
            'namaBarang' => 'Printer',
            'jumlahTotal' => 4,
            'jumlahTersedia' => 2,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'ATK',
            'namaBarang' => 'Kertas A4',
            'jumlahTotal' => 500,
            'jumlahTersedia' => 350,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'ATK',
            'namaBarang' => 'Map',
            'jumlahTotal' => 200,
            'jumlahTersedia' => 150,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'ATK',
            'namaBarang' => 'Stabilo',
            'jumlahTotal' => 70,
            'jumlahTersedia' => 50,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'Elektronik',
            'namaBarang' => 'Scanner',
            'jumlahTotal' => 3,
            'jumlahTersedia' => 2,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'Elektronik',
            'namaBarang' => 'Monitor',
            'jumlahTotal' => 8,
            'jumlahTersedia' => 5,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'ATK',
            'namaBarang' => 'Penggaris',
            'jumlahTotal' => 90,
            'jumlahTersedia' => 70,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'ATK',
            'namaBarang' => 'Binder Clip',
            'jumlahTotal' => 120,
            'jumlahTersedia' => 100,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'ATK',
            'namaBarang' => 'Sticky Notes',
            'jumlahTotal' => 60,
            'jumlahTersedia' => 45,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'Elektronik',
            'namaBarang' => 'Speaker',
            'jumlahTotal' => 6,
            'jumlahTersedia' => 4,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'Elektronik',
            'namaBarang' => 'Kamera',
            'jumlahTotal' => 2,
            'jumlahTersedia' => 1,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'ATK',
            'namaBarang' => 'Lakban',
            'jumlahTotal' => 40,
            'jumlahTersedia' => 30,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'ATK',
            'namaBarang' => 'Amplop',
            'jumlahTotal' => 300,
            'jumlahTersedia' => 250,
        ]);

        DataBarang::create([
            'jenisBarangPersediaan' => 'Elektronik',
            'namaBarang' => 'Modem',
            'jumlahTotal' => 5,
            'jumlahTersedia' => 3,
        ]);
    }
}
