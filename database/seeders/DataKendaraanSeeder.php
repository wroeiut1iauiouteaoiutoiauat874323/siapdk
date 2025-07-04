<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataKendaraan;

class DataKendaraanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Honda Beat',
            'nomorPolisi' => 'B 1234 ABC',
            'jumlahTotal' => 10,
            'jumlahTersedia' => 7,
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Toyota Avanza',
            'nomorPolisi' => 'B 5678 DEF',
            'jumlahTotal' => 3,
            'jumlahTersedia' => 2,
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Yamaha NMAX',
            'nomorPolisi' => 'B 2345 GHI',
            'jumlahTotal' => 5,
            'jumlahTersedia' => 3,
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Daihatsu Xenia',
            'nomorPolisi' => 'B 6789 JKL',
            'jumlahTotal' => 4,
            'jumlahTersedia' => 2,
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Suzuki Satria',
            'nomorPolisi' => 'B 3456 MNO',
            'jumlahTotal' => 6,
            'jumlahTersedia' => 4,
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Honda Jazz',
            'nomorPolisi' => 'B 7890 PQR',
            'jumlahTotal' => 2,
            'jumlahTersedia' => 1,
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Vespa Sprint',
            'nomorPolisi' => 'B 4567 STU',
            'jumlahTotal' => 3,
            'jumlahTersedia' => 2,
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Suzuki Ertiga',
            'nomorPolisi' => 'B 8901 VWX',
            'jumlahTotal' => 5,
            'jumlahTersedia' => 3,
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Kawasaki Ninja',
            'nomorPolisi' => 'B 5678 YZA',
            'jumlahTotal' => 2,
            'jumlahTersedia' => 2,
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Mitsubishi Xpander',
            'nomorPolisi' => 'B 9012 BCD',
            'jumlahTotal' => 3,
            'jumlahTersedia' => 2,
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Honda Vario',
            'nomorPolisi' => 'B 6789 EFG',
            'jumlahTotal' => 7,
            'jumlahTersedia' => 5,
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Honda Brio',
            'nomorPolisi' => 'B 1235 HIJ',
            'jumlahTotal' => 4,
            'jumlahTersedia' => 3,
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Yamaha Mio',
            'nomorPolisi' => 'B 7890 KLM',
            'jumlahTotal' => 8,
            'jumlahTersedia' => 6,
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Toyota Innova',
            'nomorPolisi' => 'B 2346 NOP',
            'jumlahTotal' => 2,
            'jumlahTersedia' => 1,
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Suzuki Address',
            'nomorPolisi' => 'B 8901 QRS',
            'jumlahTotal' => 4,
            'jumlahTersedia' => 3,
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Daihatsu Terios',
            'nomorPolisi' => 'B 3457 TUV',
            'jumlahTotal' => 3,
            'jumlahTersedia' => 2,
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Honda Supra',
            'nomorPolisi' => 'B 9012 WXY',
            'jumlahTotal' => 5,
            'jumlahTersedia' => 4,
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Suzuki APV',
            'nomorPolisi' => 'B 4568 ZAB',
            'jumlahTotal' => 2,
            'jumlahTersedia' => 2,
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Yamaha Aerox',
            'nomorPolisi' => 'B 1236 CDE',
            'jumlahTotal' => 6,
            'jumlahTersedia' => 5,
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Honda CRV',
            'nomorPolisi' => 'B 5679 FGH',
            'jumlahTotal' => 1,
            'jumlahTersedia' => 1,
            ],
        ];

        foreach ($data as $item) {
            DataKendaraan::create($item);
        }
    }
}
