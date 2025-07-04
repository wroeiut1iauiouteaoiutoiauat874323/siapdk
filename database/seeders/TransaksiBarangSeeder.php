<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiBarang;

class TransaksiBarangSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
            'idDataBarang' => 1,
            'idDataPegawai' => 1,
            'jenisTransaksi' => 'Masuk',
            'jumlahPinjam' => 5,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dikembalikan',
            ],
            [
            'idDataBarang' => 2,
            'idDataPegawai' => 2,
            'jenisTransaksi' => 'Keluar',
            'jumlahPinjam' => 1,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dipinjam',
            ],
            [
            'idDataBarang' => 3,
            'idDataPegawai' => 3,
            'jenisTransaksi' => 'Masuk',
            'jumlahPinjam' => 3,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => now()->addDays(2),
            'statusTransaksi' => 'Dikembalikan',
            ],
            [
            'idDataBarang' => 4,
            'idDataPegawai' => 4,
            'jenisTransaksi' => 'Keluar',
            'jumlahPinjam' => 2,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dipinjam',
            ],
            [
            'idDataBarang' => 5,
            'idDataPegawai' => 5,
            'jenisTransaksi' => 'Masuk',
            'jumlahPinjam' => 4,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => now()->addDays(1),
            'statusTransaksi' => 'Dikembalikan',
            ],
            [
            'idDataBarang' => 6,
            'idDataPegawai' => 6,
            'jenisTransaksi' => 'Keluar',
            'jumlahPinjam' => 2,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dipinjam',
            ],
            [
            'idDataBarang' => 7,
            'idDataPegawai' => 7,
            'jenisTransaksi' => 'Masuk',
            'jumlahPinjam' => 6,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => now()->addDays(3),
            'statusTransaksi' => 'Dikembalikan',
            ],
            [
            'idDataBarang' => 8,
            'idDataPegawai' => 8,
            'jenisTransaksi' => 'Keluar',
            'jumlahPinjam' => 1,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dipinjam',
            ],
            [
            'idDataBarang' => 9,
            'idDataPegawai' => 9,
            'jenisTransaksi' => 'Masuk',
            'jumlahPinjam' => 2,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => now()->addDays(2),
            'statusTransaksi' => 'Dikembalikan',
            ],
            [
            'idDataBarang' => 10,
            'idDataPegawai' => 10,
            'jenisTransaksi' => 'Keluar',
            'jumlahPinjam' => 3,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dipinjam',
            ],
            [
            'idDataBarang' => 1,
            'idDataPegawai' => 2,
            'jenisTransaksi' => 'Masuk',
            'jumlahPinjam' => 7,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => now()->addDays(4),
            'statusTransaksi' => 'Dikembalikan',
            ],
            [
            'idDataBarang' => 2,
            'idDataPegawai' => 3,
            'jenisTransaksi' => 'Keluar',
            'jumlahPinjam' => 2,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dipinjam',
            ],
            [
            'idDataBarang' => 3,
            'idDataPegawai' => 4,
            'jenisTransaksi' => 'Masuk',
            'jumlahPinjam' => 5,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => now()->addDays(2),
            'statusTransaksi' => 'Dikembalikan',
            ],
            [
            'idDataBarang' => 4,
            'idDataPegawai' => 5,
            'jenisTransaksi' => 'Keluar',
            'jumlahPinjam' => 1,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dipinjam',
            ],
            [
            'idDataBarang' => 5,
            'idDataPegawai' => 6,
            'jenisTransaksi' => 'Masuk',
            'jumlahPinjam' => 3,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => now()->addDays(1),
            'statusTransaksi' => 'Dikembalikan',
            ],
            [
            'idDataBarang' => 6,
            'idDataPegawai' => 7,
            'jenisTransaksi' => 'Keluar',
            'jumlahPinjam' => 4,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dipinjam',
            ],
            [
            'idDataBarang' => 7,
            'idDataPegawai' => 8,
            'jenisTransaksi' => 'Masuk',
            'jumlahPinjam' => 2,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => now()->addDays(3),
            'statusTransaksi' => 'Dikembalikan',
            ],
            [
            'idDataBarang' => 8,
            'idDataPegawai' => 9,
            'jenisTransaksi' => 'Keluar',
            'jumlahPinjam' => 5,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dipinjam',
            ],
            [
            'idDataBarang' => 9,
            'idDataPegawai' => 10,
            'jenisTransaksi' => 'Masuk',
            'jumlahPinjam' => 1,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => now()->addDays(2),
            'statusTransaksi' => 'Dikembalikan',
            ],
            [
            'idDataBarang' => 10,
            'idDataPegawai' => 1,
            'jenisTransaksi' => 'Keluar',
            'jumlahPinjam' => 2,
            'tanggalPinjam' => now(),
            'tanggalDikembalikan' => null,
            'statusTransaksi' => 'Dipinjam',
            ],
        ];

        foreach ($data as $item) {
            TransaksiBarang::create($item);
        }
    }
}
