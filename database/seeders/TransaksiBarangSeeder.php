<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiBarang;

class TransaksiBarangSeeder extends Seeder
{
    public function run()
    {
        // Membuat kode unik base36 dengan panjang 10 digit, diawali huruf 'T', dan memastikan tidak kembar
        $usedCodes = [];
        function generateBase36Code10($usedCodes, $prefix = 'TB', $length = 10)
        {
            do {
            $unique = uniqid('', true) . random_int(1000, 9999);
            $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), $length - 1, '0', STR_PAD_LEFT));
            $kode = $prefix . $kodeBase36;
            } while (in_array($kode, $usedCodes));
            return $kode;
        }

        for ($i = 0; $i < 110; $i++) {
            $jenisTransaksi = collect(['Keluar', 'Masuk'])->random();
            $jumlahPinjam = rand(10, 50);
            $idDataBarang = rand(1, 20);

            // Ambil stok saat ini
            $dataBarang = \App\Models\DataBarang::find($idDataBarang);
            if (!$dataBarang) {
            continue;
            }

            if ($jenisTransaksi === 'Keluar') {
            // Jika stok kurang dari jumlahPinjam, skip
            if ($dataBarang->jumlahTersedia < $jumlahPinjam) {
                continue;
            }
            } else {
            // Jika stok lebih dari atau sama dengan stok maksimal (misal 100), skip
            $stokMaksimal = 100;
            if ($dataBarang->jumlahTersedia + $jumlahPinjam > $stokMaksimal) {
                continue;
            }
            }

            $kode = generateBase36Code10($usedCodes);
            $usedCodes[] = $kode;

            TransaksiBarang::create([
            'idDataBarang' => $idDataBarang,
            'kode' => $kode,
            'nama_pegawai' => fake()->name(),
            'status_pegawai' => collect(['PNS', 'PPPK', 'CPNS', 'CPPPK', 'Honorer'])->random(),
            'jenisTransaksi' => $jenisTransaksi,
            'jumlahPinjam' => $jumlahPinjam,
            'tanggal_transaksi' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
            'waktu' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
            'alasan' => fake()->sentence(),
            ]);

            // Update DataBarang: tambah atau kurangi stok sesuai jenisTransaksi
            if ($jenisTransaksi === 'Keluar') {
            $dataBarang->decrement('jumlahTersedia', $jumlahPinjam);
            } else {
            $dataBarang->increment('jumlahTersedia', $jumlahPinjam);
            }
        }
    }
}
