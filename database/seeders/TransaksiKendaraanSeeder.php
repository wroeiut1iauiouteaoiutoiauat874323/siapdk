<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiKendaraan;

class TransaksiKendaraanSeeder extends Seeder
{
    public function run()
    {
        TransaksiKendaraan::create([
            'idDataKendaraan' => 1,
            'idDataPegawai' => 1,
            'jenisTransaksi' => 'Peminjaman',
            'jumlahPinjam' => 1,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dipinjam',
        ]);

        TransaksiKendaraan::create([
            'idDataKendaraan' => 2,
            'idDataPegawai' => 2,
            'jenisTransaksi' => 'Peminjaman',
            'jumlahPinjam' => 1,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dipinjam',
        ]);
    }
}
