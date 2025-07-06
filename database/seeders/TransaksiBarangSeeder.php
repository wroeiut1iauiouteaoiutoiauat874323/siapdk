<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiBarang;

class TransaksiBarangSeeder extends Seeder
{
    public function run()
    {
        // Membuat kode unik base36 dengan panjang 10 digit, diawali huruf 'TB', dan memastikan tidak kembar
        $usedCodes = [];
        function generateBase36Code10($usedCodes, $prefix = 'TB', $length = 10)
        {
            do {
            $unique = uniqid('', true) . random_int(1000, 9999);
            $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), $length - 2, '0', STR_PAD_LEFT));
            $kode = $prefix . $kodeBase36;
            } while (in_array($kode, $usedCodes));
            return $kode;
        }

        // Generate transaksi untuk barang yang sudah ada
        for ($i = 0; $i < 110; $i++) {
            $jenisTransaksi = collect(['Keluar', 'Masuk'])->random();
            $jumlahPinjam = rand(10, 50);
            $idDataBarang = rand(1, 20);

            // Ambil data barang
            $dataBarang = \App\Models\DataBarang::find($idDataBarang);
            if (!$dataBarang) {
            continue;
            }

            if ($jenisTransaksi === 'Keluar') {
            // Jika stok kurang dari jumlahPinjam, skip
            if ($dataBarang->jumlahTotal < $jumlahPinjam) {
                continue;
            }
            // Kurangi stok
            $dataBarang->jumlahTotal -= $jumlahPinjam;
            } else {
            // Tambah stok
            $dataBarang->jumlahTotal += $jumlahPinjam;
            }
            $dataBarang->save();

            // Membuat kode unik transaksi barang, format: TB + 8 digit acak base36, pastikan unik
            do {
            $unique = uniqid('', true) . random_int(1000, 9999);
            $kodeTransaksi = 'TB' . strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 8, '0', STR_PAD_LEFT));
            } while (in_array($kodeTransaksi, $usedCodes));
            $usedCodes[] = $kodeTransaksi;

            TransaksiBarang::create([
            'idDataBarang' => $idDataBarang,
            'kode' => $kodeTransaksi,
            'nama_pegawai' => fake()->name(),
            'status_pegawai' => collect(['PNS', 'PPPK', 'CPNS', 'CPPPK', 'Honorer'])->random(),
            'jenisTransaksi' => $jenisTransaksi,
            'jumlahPinjam' => $jumlahPinjam,
            'tanggal_transaksi' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
            'waktu' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
            'alasan' => fake()->sentence(),
            'lokasi' => fake()->buildingNumber(),
            ]);
        }

        // Tambahkan transaksi barang masuk untuk barang baru (tidak ganda jika nama & jenis sama)
        for ($i = 0; $i < 100; $i++) {
            $barangBaruList = [
            ['nama' => 'Kursi Lipat', 'jenis' => 'Lainnya'],
            ['nama' => 'Meja Kantor', 'jenis' => 'Lainnya'],
            ['nama' => 'Printer Epson', 'jenis' => 'Elektronik'],
            ['nama' => 'Laptop Lenovo', 'jenis' => 'Elektronik'],
            ['nama' => 'Proyektor', 'jenis' => 'Elektronik'],
            ['nama' => 'Papan Tulis', 'jenis' => 'ATK'],
            ['nama' => 'Lemari Arsip', 'jenis' => 'Lainnya'],
            ['nama' => 'Scanner', 'jenis' => 'Elektronik'],
            ['nama' => 'Kipas Angin', 'jenis' => 'Elektronik'],
            ['nama' => 'Dispenser', 'jenis' => 'Elektronik'],
            ['nama' => 'Rak Buku', 'jenis' => 'Lainnya'],
            ['nama' => 'Televisi', 'jenis' => 'Elektronik'],
            ['nama' => 'Komputer PC', 'jenis' => 'Elektronik'],
            ['nama' => 'Mouse Wireless', 'jenis' => 'ATK'],
            ['nama' => 'Keyboard', 'jenis' => 'ATK'],
            ['nama' => 'AC Split', 'jenis' => 'Elektronik'],
            ['nama' => 'Speaker', 'jenis' => 'Elektronik'],
            ['nama' => 'Kabel Roll', 'jenis' => 'Lainnya'],
            ['nama' => 'Stop Kontak', 'jenis' => 'Lainnya'],
            ['nama' => 'Lampu Meja', 'jenis' => 'Lainnya'],
            ];
            $barangBaru = fake()->randomElement($barangBaruList);
            $namaBarangBaru = $barangBaru['nama'];
            $jenisBarangPersediaan = $barangBaru['jenis'];
            $jumlahPinjam = rand(10, 50);

            // Cek apakah sudah ada barang dengan nama & jenis yang sama
            $dataBarangBaru = \App\Models\DataBarang::where('namaBarang', $namaBarangBaru)
            ->where('jenisBarangPersediaan', $jenisBarangPersediaan)
            ->first();

            if ($dataBarangBaru) {
            // Jika sudah ada, tambahkan jumlahTotal
            $dataBarangBaru->jumlahTotal += $jumlahPinjam;
            $dataBarangBaru->save();
            } else {
            // Jika belum ada, buat baru
            $dataBarangBaru = \App\Models\DataBarang::create([
                'namaBarang' => $namaBarangBaru,
                'jenisBarangPersediaan' => $jenisBarangPersediaan,
                'jumlahTotal' => $jumlahPinjam,
                'kode' => 'B' . strtoupper(str_pad(base_convert(crc32(uniqid('', true) . random_int(1000, 9999)), 10, 36), 7, '0', STR_PAD_LEFT)),
                'lokasi' => collect(['Gedung A', 'Gedung B', 'Gedung C'])->random(),
            ]);
            }

            // Membuat kode unik transaksi barang, format: TB + 8 digit acak base36, pastikan unik
            do {
            $unique = uniqid('', true) . random_int(1000, 9999);
            $kodeTransaksi = 'TB' . strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 8, '0', STR_PAD_LEFT));
            } while (in_array($kodeTransaksi, $usedCodes));
            $usedCodes[] = $kodeTransaksi;

            TransaksiBarang::create([
            'idDataBarang' => $dataBarangBaru->id,
            'kode' => $kodeTransaksi,
            'nama_pegawai' => fake()->name(),
            'status_pegawai' => collect(['PNS', 'PPPK', 'CPNS', 'CPPPK', 'Honorer'])->random(),
            'jenisTransaksi' => 'Masuk',
            'jumlahPinjam' => $jumlahPinjam,
            'tanggal_transaksi' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
            'waktu' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
            'alasan' => fake()->sentence(),
            ]);
        }
    }
}

