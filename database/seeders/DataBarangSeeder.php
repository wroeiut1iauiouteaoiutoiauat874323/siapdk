<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataBarang;

class DataBarangSeeder extends Seeder
{
    public function run()
    {
        $barangs = [
            ['ATK', 'Pulpen'],
            ['Elektronik', 'Proyektor'],
            ['ATK', 'Pensil'],
            ['ATK', 'Penghapus'],
            ['ATK', 'Spidol'],
            ['Elektronik', 'Laptop'],
            ['Elektronik', 'Printer'],
            ['ATK', 'Kertas A4'],
            ['ATK', 'Map'],
            ['ATK', 'Stabilo'],
            ['Elektronik', 'Scanner'],
            ['Elektronik', 'Monitor'],
            ['ATK', 'Penggaris'],
            ['ATK', 'Binder Clip'],
            ['ATK', 'Sticky Notes'],
            ['Elektronik', 'Speaker'],
            ['Elektronik', 'Kamera'],
            ['ATK', 'Lakban'],
            ['ATK', 'Amplop'],
            ['Elektronik', 'Modem'],
        ];

        // Membuat kode unik base36 dengan panjang 7 digit, diawali huruf 'B', dan memastikan tidak kembar
        $usedCodes = [];
        foreach ($barangs as $index => $barang) {
            do {
            $unique = uniqid('', true) . random_int(1000, 9999);
            $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 7, '0', STR_PAD_LEFT));
            $kode = 'B' . $kodeBase36;
            } while (in_array($kode, $usedCodes));
            $usedCodes[] = $kode;

            DataBarang::create([
            'kode' => $kode,
            'jenisBarangPersediaan' => $barang[0],
            'namaBarang' => $barang[1],
            'jumlahTotal' => 10000,
            'jumlahTersedia' => 10000,
            ]);
        }
    }
}
