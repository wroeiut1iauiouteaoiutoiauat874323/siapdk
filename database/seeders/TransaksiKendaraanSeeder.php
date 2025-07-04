<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiKendaraan;

class TransaksiKendaraanSeeder extends Seeder
{
    public function run()
    {

        // Tambahan 20 data dengan tanggal_transaksi random
        for ($i = 0; $i < 20; $i++) {
            TransaksiKendaraan::create([
            'idDataKendaraan' => rand(1, 5),
            'idDataPegawai' => rand(1, 2),
            'jenisTransaksi' => rand(0, 1) ? 'Masuk' : 'Keluar',
            'jumlahPinjam' => rand(1, 10),
            'tanggal_transaksi' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
            'statusTransaksi' => 'Dipinjam',
            ]);
        }
    }
}
