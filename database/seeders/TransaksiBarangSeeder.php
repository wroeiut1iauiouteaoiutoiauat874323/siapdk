<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiBarang;

class TransaksiBarangSeeder extends Seeder
{
    public function run()
    {
        // Tambahan 20 data dengan tanggal_transaksi random
        for ($i = 0; $i < 100; $i++) {
            TransaksiBarang::create([
            'idDataBarang' => rand(1, 5),
            'nama_pegawai' => fake()->name(),
            'status_pegawai' => collect(value: ['PNS', 'PPPK', 'CPNS', 'CPPPK', 'honorer'])->random(),
            'jenisTransaksi' => rand(0, 1) ? 'Masuk' : 'Keluar',
            'jumlahPinjam' => rand(1, 10),
            'tanggal_transaksi' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
            'statusTransaksi' => 'Dipinjam',
            ]);
        }
    }
}
