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

        // Untuk memastikan tidak ada data kendaraan yang double
        $uniqueKendaraan = [];

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

        // Membuat kode unik base36 dengan panjang maksimal 9 digit, diawali huruf 'K'
        $usedKodeBarang = [];
        function generateKodeBarang(&$usedKodeBarang, $prefix = 'K', $maxLength = 9) {
            $targetLength = $maxLength - strlen($prefix); // Length yang tersisa setelah prefix
            do {
                $unique = uniqid('', true) . random_int(1000, 9999);
                $kodeBase36 = strtoupper(base_convert(crc32($unique), 10, 36));

                // Potong atau pad sesuai target length
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

        // Untuk menyimpan status terakhir kendaraan berdasarkan identitas unik
        $kendaraanStatus = [];

        // Generate 100 data kendaraan unik
        $maxKendaraan = 100;
        $i = 0;
        while ($i < $maxKendaraan) {
            $jenis = $jenisList[$i % count($jenisList)];
            $namaKendaraan = fake()->randomElement($namaKendaraanList[$jenis]);
            $nomorPolisi = $jenis === 'Sepeda'
                ? 'Tidak ada (Sepeda), warna ' . fake()->safeColorName()
                : ('B ' . rand(1000, 9999) . ' ' . chr(rand(65, 90)));
            $lokasi = fake()->randomElement($lokasiList);

            // Cek duplikasi berdasarkan kombinasi unik
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

            // Buat transaksi langsung setelah data kendaraan dibuat
            $jumlahTransaksi = rand(2, 4);
            $lastJenisTransaksi = null;

            // Simpan status terakhir transaksi kendaraan ini
            $kendaraanKey = $jenis . '|' . $namaKendaraan . '|' . $nomorPolisi . '|' . $lokasi;
            $kendaraanStatus[$kendaraanKey] = 'Masuk'; // Awal dianggap sudah "Masuk" (tersedia)

            for ($j = 0; $j < $jumlahTransaksi; $j++) {
                // Cek status terakhir
                $lastStatus = $kendaraanStatus[$kendaraanKey];

                // Jika status terakhir Masuk, hanya boleh Keluar. Jika Keluar, hanya boleh Masuk.
                if ($lastStatus === 'Masuk') {
                    $jenisTransaksi = 'Keluar';
                } else {
                    $jenisTransaksi = 'Masuk';
                }

                // Simulasi: jika ingin mencoba "Masuk" dua kali berturut-turut, skip
                if ($j > 0 && $jenisTransaksi === $lastStatus) {
                    continue;
                }

                TransaksiKendaraan::create([
                    'idDataKendaraan' => $dataKendaraan->id,
                    'nama_pegawai' => fake()->name(),
                    'status_pegawai' => collect(['PNS', 'PPPK', 'CPNS', 'CPPPK', 'Honorer'])->random(),
                    'tanggal_transaksi' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
                    'jenisTransaksi' => $jenisTransaksi,
                    'waktu' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
                    'kode' => generateKodeUnik12($usedCodes),
                    'alasan' => fake()->sentence(),
                ]);

                // Update status terakhir
                $kendaraanStatus[$kendaraanKey] = $jenisTransaksi;
                $lastJenisTransaksi = $jenisTransaksi;
            }

            // Update status kendaraan sesuai transaksi terakhir
            // Jika transaksi terakhir 'Keluar' maka status 'Tidak Tersedia', jika 'Masuk' maka 'Tersedia'
            $dataKendaraan->status = $lastJenisTransaksi === 'Keluar' ? 'Tidak Tersedia' : 'Tersedia';
            $dataKendaraan->save();

            $i++;

            // Update status semua data kendaraan berdasarkan transaksi terakhir
            foreach (\App\Models\DataKendaraan::all() as $kendaraan) {
                $lastTransaksi = \App\Models\TransaksiKendaraan::where('idDataKendaraan', $kendaraan->id)
                    ->orderByDesc('tanggal_transaksi')
                    ->first();

                if ($lastTransaksi) {
                    if ($lastTransaksi->jenisTransaksi === 'Masuk') {
                        $kendaraan->status = 'Tersedia';
                    } else {
                        $kendaraan->status = 'Tidak Tersedia';
                    }
                    $kendaraan->save();
                }
            }
        }
    }
}
