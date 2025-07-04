<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiKendaraan;

class TransaksiKendaraanSeeder extends Seeder
{
    public function run()
    {

        // Tambahan 30 data dengan tanggal_transaksi random dan jenisTransaksi serta statusTransaksi yang konsisten
        for ($i = 0; $i < 30; $i++) {
            $id = rand(1, 10);

            // Hitung statusTransaksi terakhir untuk kendaraan ini
            $lastTransaksi = TransaksiKendaraan::where('idDataKendaraan', $id)
            ->orderByDesc('tanggal_transaksi')
            ->first();

            // Jika terakhir Dipinjam, maka berikutnya Dikembalikan, dan sebaliknya
            if ($lastTransaksi && $lastTransaksi->statusTransaksi === 'Dipinjam') {
            $statusTransaksi = 'Dikembalikan';
            $jenisTransaksi = 'Masuk';
            } else {
            $statusTransaksi = 'Dipinjam';
            $jenisTransaksi = 'Keluar';
            }

            TransaksiKendaraan::create([
            'idDataKendaraan' => $id,
            'nama_pegawai' => fake()->name(),
            'status_pegawai' => collect(['PNS', 'PPPK', 'CPNS', 'CPPPK', 'Honorer'])->random(),
            'tanggal_transaksi' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
            'jenisTransaksi' => $jenisTransaksi,
            'statusTransaksi' => $statusTransaksi,
            ]);
        }

    }
}
