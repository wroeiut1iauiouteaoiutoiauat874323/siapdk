<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiBarang;

class TransaksiBarangSeeder extends Seeder
{
    public function run()
    {
        TransaksiBarang::create([
            'idDataBarang' => 1,
            'idDataPegawai' => 1,
            'jenisTransaksi' => 'Peminjaman',
            'jumlahPinjam' => 5,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dipinjam',
        ]);

        TransaksiBarang::create([
            'idDataBarang' => 2,
            'idDataPegawai' => 2,
            'jenisTransaksi' => 'Peminjaman',
            'jumlahPinjam' => 1,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dipinjam',
        ]);
    }
}
