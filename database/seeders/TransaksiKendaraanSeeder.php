<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiKendaraan;

class TransaksiKendaraanSeeder extends Seeder
{
    public function run()
    {

        // Membuat kode unik base36 dengan panjang 12 digit, diawali huruf 'T', dan memastikan tidak kembar
        $usedCodes = [];
        function generateKodeUnik12($usedCodes, $prefix = 'TK', $length = 12) {
            do {
            $unique = uniqid('', true) . random_int(1000, 9999);
            $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), $length, '0', STR_PAD_LEFT));
            $kode = $prefix . $kodeBase36;
            } while (in_array($kode, $usedCodes));
            return $kode;
        }

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

            $kode = generateKodeUnik12($usedCodes);
            $usedCodes[] = $kode;

            TransaksiKendaraan::create([
            'idDataKendaraan' => $id,
            'nama_pegawai' => fake()->name(),
            'status_pegawai' => collect(['PNS', 'PPPK', 'CPNS', 'CPPPK', 'Honorer'])->random(),
            'tanggal_transaksi' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
            'jenisTransaksi' => $jenisTransaksi,
            'statusTransaksi' => $statusTransaksi,
            'waktu' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
            'kode' => $kode,
            'alasan' => fake()->sentence(),
            ]);
        }

    }
}
