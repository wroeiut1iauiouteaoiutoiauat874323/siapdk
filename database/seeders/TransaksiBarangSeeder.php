<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiBarang;

class TransaksiBarangSeeder extends Seeder
{
    public function run()
    {
        // Tambahan 20 data dengan tanggal_transaksi random
        for ($i = 0; $i < 1000; $i++) {
            TransaksiBarang::create([
            'idDataBarang' => rand(1, 20),
            'nama_pegawai' => fake()->name(),
            'status_pegawai' => collect(value: ['PNS', 'PPPK', 'CPNS', 'CPPPK', 'Honorer'])->random(),
            'jenisTransaksi' => 'Keluar',
            'jumlahPinjam' => rand(10, 20),
            'tanggal_transaksi' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
            'statusTransaksi' =>  'Dipinjam',
            ]);
        }
        for ($i = 0; $i < 10; $i++) {
            TransaksiBarang::create([
            'idDataBarang' => rand(1, 20),
            'nama_pegawai' => fake()->name(),
            'status_pegawai' => collect(value: ['PNS', 'PPPK', 'CPNS', 'CPPPK', 'Honorer'])->random(),
            'jenisTransaksi' => 'Masuk',
            'jumlahPinjam' => rand(1, 2),
            'tanggal_transaksi' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
            'statusTransaksi' => 'Dikembalikan',
            ]);
        }
    }
}
