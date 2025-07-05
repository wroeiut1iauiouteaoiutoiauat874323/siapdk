<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiBarang;

class TransaksiBarangSeeder extends Seeder
{
    public function run()
    {
        // Tambahan 20 data dengan tanggal_transaksi random
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
                // Ganti 100 dengan batas maksimal stok jika ada
                $stokMaksimal = 100;
                if ($dataBarang->jumlahTersedia + $jumlahPinjam > $stokMaksimal) {
                    continue;
                }
            }

            TransaksiBarang::create([
                'idDataBarang' => $idDataBarang,
                'nama_pegawai' => fake()->name(),
                'status_pegawai' => collect(['PNS', 'PPPK', 'CPNS', 'CPPPK', 'Honorer'])->random(),
                'jenisTransaksi' => $jenisTransaksi,
                'jumlahPinjam' => $jumlahPinjam,
                'tanggal_transaksi' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
                'statusTransaksi' => $jenisTransaksi === 'Keluar' ? 'Dipinjam' : 'Dikembalikan',
                'waktu' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
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
