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
            'status' => 'Tersedia',
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Toyota Avanza',
            'nomorPolisi' => 'B 5678 DEF',
            'status' => 'Tidak Tersedia',
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Yamaha NMAX',
            'nomorPolisi' => 'B 2345 GHI',
            'status' => 'Tersedia',
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Daihatsu Xenia',
            'nomorPolisi' => 'B 6789 JKL',
            'status' => 'Tidak Tersedia',
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Suzuki Satria',
            'nomorPolisi' => 'B 3456 MNO',
            'status' => 'Tersedia',
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Honda Jazz',
            'nomorPolisi' => 'B 7890 PQR',
            'status' => 'Tidak Tersedia',
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Vespa Sprint',
            'nomorPolisi' => 'B 4567 STU',
            'status' => 'Tersedia',
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Suzuki Ertiga',
            'nomorPolisi' => 'B 8901 VWX',
            'status' => 'Tidak Tersedia',
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Kawasaki Ninja',
            'nomorPolisi' => 'B 5678 YZA',
            'status' => 'Tersedia',
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Mitsubishi Xpander',
            'nomorPolisi' => 'B 9012 BCD',
            'status' => 'Tidak Tersedia',
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Honda Vario',
            'nomorPolisi' => 'B 6789 EFG',
            'status' => 'Tersedia',
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Honda Brio',
            'nomorPolisi' => 'B 1235 HIJ',
            'status' => 'Tidak Tersedia',
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Yamaha Mio',
            'nomorPolisi' => 'B 7890 KLM',
            'status' => 'Tersedia',
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Toyota Innova',
            'nomorPolisi' => 'B 2346 NOP',
            'status' => 'Tidak Tersedia',
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Suzuki Address',
            'nomorPolisi' => 'B 8901 QRS',
            'status' => 'Tersedia',
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Daihatsu Terios',
            'nomorPolisi' => 'B 3457 TUV',
            'status' => 'Tidak Tersedia',
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Honda Supra',
            'nomorPolisi' => 'B 9012 WXY',
            'status' => 'Tersedia',
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Suzuki APV',
            'nomorPolisi' => 'B 4568 ZAB',
            'status' => 'Tidak Tersedia',
            ],
            [
            'jenisKendaraan' => 'Motor',
            'namaKendaraan' => 'Yamaha Aerox',
            'nomorPolisi' => 'B 1236 CDE',
            'status' => 'Tersedia',
            ],
            [
            'jenisKendaraan' => 'Mobil',
            'namaKendaraan' => 'Honda CRV',
            'nomorPolisi' => 'B 5679 FGH',
            'status' => 'Tidak Tersedia',
            ],
        ];
        // Membuat kode unik base36 dengan panjang 8 digit, diawali huruf 'K', dan memastikan tidak kembar
        $usedCodes = [];
        foreach ($data as $index => $item) {
            do {
            $unique = uniqid('', true) . random_int(1000, 9999);
            $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 8, '0', STR_PAD_LEFT));
            $kode = 'K' . $kodeBase36;
            } while (in_array($kode, $usedCodes));
            $usedCodes[] = $kode;

            $item['kode'] = $kode;
            DataKendaraan::create($item);
        }
    }
}
