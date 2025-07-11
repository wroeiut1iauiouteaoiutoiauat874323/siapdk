<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiKendaraan;

class TransaksiKendaraanSeeder extends Seeder
{
    public function run()
    {
        // Data referensi
        $jenisList = ['Mobil', 'Motor', 'Sepeda'];
        $namaKendaraanList = [
            'Mobil' => ['Toyota Avanza', 'Honda Brio', 'Suzuki Ertiga', 'Daihatsu Xenia', 'Mitsubishi Xpander', 'Toyota Innova'],
            'Motor' => ['Honda Beat', 'Yamaha Nmax', 'Suzuki Satria', 'Kawasaki Ninja', 'Vespa LX'],
            'Sepeda' => ['Polygon Sepeda', 'United Sepeda', 'Wimcycle Sepeda']
        ];
        $lokasiList = ['Gedung A', 'Gedung B', 'Gedung C'];

        // Membuat kode unik base36 dengan panjang maksimal 9 digit, diawali huruf 'K'
        $usedKodeBarang = [];
        function generateKodeBarang(&$usedKodeBarang, $prefix = 'K', $maxLength = 9) {
            $targetLength = $maxLength - strlen($prefix);
            do {
                $unique = uniqid('', true) . random_int(1000, 9999);
                $kodeBase36 = strtoupper(base_convert(crc32($unique), 10, 36));
                if (strlen($kodeBase36) > $targetLength) {
                    $kodeBase36 = substr($kodeBase36, 0, $targetLength);
                } else {
                    $kodeBase36 = str_pad($kodeBase36, $targetLength, '0', STR_PAD_LEFT);
                }
                $kode = $prefix . $kodeBase36;
            } while (in_array($kode, $usedKodeBarang));
            $usedKodeBarang[] = $kode;
            return $kode;
        }

        // Membuat kode unik base36 dengan panjang 12 digit, diawali huruf 'TK'
        $usedCodes = [];
        function generateKodeUnik12(&$usedCodes, $prefix = 'TK', $length = 12) {
            do {
                $unique = uniqid('', true) . random_int(1000, 9999);
                $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), $length, '0', STR_PAD_LEFT));
                $kode = $prefix . $kodeBase36;
            } while (in_array($kode, $usedCodes));
            $usedCodes[] = $kode;
            return $kode;
        }

        // Untuk memastikan tidak ada data kendaraan yang double
        $uniqueKendaraan = [];

        $maxKendaraan = 100;
        $i = 0;
        while ($i < $maxKendaraan) {
            $jenis = $jenisList[$i % count($jenisList)];
            $namaKendaraan = fake()->randomElement($namaKendaraanList[$jenis]);
            $nomorPolisi = $jenis === 'Sepeda'
                ? 'Warna ' . fake()->safeColorName()
                : ('B ' . rand(1000, 9999) . ' ' . chr(rand(65, 90)));
            $lokasi = fake()->randomElement($lokasiList);

            $key = $jenis . '|' . $namaKendaraan . '|' . $nomorPolisi . '|' . $lokasi;
            if (isset($uniqueKendaraan[$key])) {
                continue;
            }
            $uniqueKendaraan[$key] = true;

            // Status awal kendaraan: Tersedia
            $status = 'Tersedia';

            $dataKendaraan = \App\Models\DataKendaraan::create([
                'jenisKendaraan' => $jenis,
                'nomorPolisi' => $nomorPolisi,
                'namaKendaraan' => $namaKendaraan,
                'lokasi' => $lokasi,
                'status' => $status,
                'kode' => generateKodeBarang($usedKodeBarang),
            ]);

            // Tanggal dasar
            $tanggalMasuk = now()->subDays(rand(10, 365));
            $tanggalKeluar = (clone $tanggalMasuk)->addHours(rand(1, 72));

            // Transaksi Masuk
            TransaksiKendaraan::create([
                'idDataKendaraan' => $dataKendaraan->id,
                'nama_pegawai' => fake()->name(),
                'status_pegawai' => collect(['PNS', 'PPPK', 'CPNS', 'CPPPK', 'Honorer'])->random(),
                'tanggal_transaksi' => $tanggalMasuk,
                'jenisTransaksi' => 'Masuk',
                'waktu' => $tanggalMasuk,
                'kode' => generateKodeUnik12($usedCodes),
                'alasan' => fake()->sentence(),
                'lokasi' => $lokasi,
                'nip' => '12345678'
            ]);

            // Transaksi Keluar
            TransaksiKendaraan::create([
                'idDataKendaraan' => $dataKendaraan->id,
                'nama_pegawai' => fake()->name(),
                'status_pegawai' => collect(['PNS', 'PPPK', 'CPNS', 'CPPPK', 'Honorer'])->random(),
                'tanggal_transaksi' => $tanggalKeluar,
                'jenisTransaksi' => 'Keluar',
                'waktu' => $tanggalKeluar,
                'kode' => generateKodeUnik12($usedCodes),
                'alasan' => fake()->sentence(),
                'lokasi' => $lokasi,
                'nip' => '12345678'
            ]);

            // Update status kendaraan jadi Tidak Tersedia
            $dataKendaraan->status = 'Tidak Tersedia';
            $dataKendaraan->save();

            $i++;
        }
    }
}
