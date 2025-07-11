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

        // Daftar barang baru yang akan diinputkan
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

        foreach ($barangBaruList as $barangBaru) {
            $namaBarangBaru = $barangBaru['nama'];
            $jenisBarangPersediaan = $barangBaru['jenis'];
            $lokasinya = collect(['Gedung A', 'Gedung B', 'Gedung C'])->random();

            // Cek apakah sudah ada barang dengan nama & jenis yang sama
            $dataBarangBaru = \App\Models\DataBarang::where('namaBarang', $namaBarangBaru)
                ->where('jenisBarangPersediaan', $jenisBarangPersediaan)
                ->first();

            if (!$dataBarangBaru) {
                // Jika belum ada, buat baru
                $dataBarangBaru = \App\Models\DataBarang::create([
                    'namaBarang' => $namaBarangBaru,
                    'jenisBarangPersediaan' => $jenisBarangPersediaan,
                    'kode' => 'B' . strtoupper(str_pad(base_convert(crc32(uniqid('', true) . random_int(1000, 9999)), 10, 36), 7, '0', STR_PAD_LEFT)),
                    'lokasi' => $lokasinya
                ]);
            }

            // Tanggal masuk dan keluar terstruktur
            $tanggalMasuk = now()->subDays(rand(30, 60));
            $tanggalKeluar = (clone $tanggalMasuk)->addDays(rand(1, 29));

            // Transaksi Masuk
            do {
                $unique = uniqid('', true) . random_int(1000, 9999);
                $kodeTransaksiMasuk = 'TB' . strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 8, '0', STR_PAD_LEFT));
            } while (in_array($kodeTransaksiMasuk, $usedCodes));
            $usedCodes[] = $kodeTransaksiMasuk;

            TransaksiBarang::create([
                'idDataBarang' => $dataBarangBaru->id,
                'kode' => $kodeTransaksiMasuk,
                'nama_pegawai' => fake()->name(),
                'status_pegawai' => collect(['PNS', 'PPPK', 'CPNS', 'CPPPK', 'Honorer'])->random(),
                'jenisTransaksi' => 'Masuk',
                'tanggal_transaksi' => $tanggalMasuk,
                'waktu' => $tanggalMasuk,
                'alasan' => fake()->sentence(),
                'lokasi' => $lokasinya,
                'nip' => '12345678'
            ]);

            // Transaksi Keluar
            do {
                $unique = uniqid('', true) . random_int(1000, 9999);
                $kodeTransaksiKeluar = 'TB' . strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 8, '0', STR_PAD_LEFT));
            } while (in_array($kodeTransaksiKeluar, $usedCodes));
            $usedCodes[] = $kodeTransaksiKeluar;

            TransaksiBarang::create([
                'idDataBarang' => $dataBarangBaru->id,
                'kode' => $kodeTransaksiKeluar,
                'nama_pegawai' => fake()->name(),
                'status_pegawai' => collect(['PNS', 'PPPK', 'CPNS', 'CPPPK', 'Honorer'])->random(),
                'jenisTransaksi' => 'Keluar',
                'tanggal_transaksi' => $tanggalKeluar,
                'waktu' => $tanggalKeluar,
                'alasan' => fake()->sentence(),
                'lokasi' => $lokasinya,
                'nip' => '12345678'
            ]);
        }
    }

}
