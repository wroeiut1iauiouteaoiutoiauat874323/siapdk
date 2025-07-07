<?php

namespace App\Http\Controllers;

use App\Exports\DataBarangNowExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\DataPegawai;
use App\Models\DataKendaraan;
use App\Models\DataBarang;
use App\Models\TransaksiBarang;
use App\Models\TransaksiKendaraan;
use App\Helpers\jurnalhelper;

use Carbon\Carbon;

class prosesController extends Controller
{

    // Start Proses Akun
    public function login(Request $request)
    {
        $input = $request->only('nipPegawai', 'password');

        $validator = Validator::make($input, [
            'nipPegawai' => 'required|numeric',
            'password' => 'required'
        ], [
            'nipPegawai.required' => 'NIP Pegawai diperlukan.',
            'nipPegawai.numeric' => 'NIP Pegawai harus berupa angka.',
            'password.required' => 'Kata sandi diperlukan.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pegawai = DataPegawai::where('nipPegawai', $request->nipPegawai)->first();

        if (!$pegawai || !Hash::check($request->password, $pegawai->password)) {
            return back()->withErrors(['password' => 'NIP atau kata sandi salah.'])->withInput();
        }

        // Simpan data pegawai ke session Laravel
        session(['pegawai_login' => $pegawai->id]);

        $usernya = DataPegawai::where('nipPegawai', $request->nipPegawai)->first();
        jurnalhelper::buatkukis($usernya);


        return redirect()->route('dashboard', ['menu' => 'barang']);

    }

    public function transaksi_barang_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenisTransaksi' => 'required|in:Masuk,Keluar',
            'tanggal_transaksi' => 'required|date',
            'waktu_transaksi' => 'nullable|date_format:H:i:s',
            'alasan' => 'nullable|string|max:255',
            'lokasiBarang' => 'required|string|max:100',
            'kode_barang' => 'nullable|string|max:20',
        ], [
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi.',
            'tanggal_transaksi.date' => 'Tanggal transaksi tidak valid.',
            'waktu_transaksi.date_format' => 'Format waktu tidak valid (H:i:s).',
            'lokasiBarang.required' => 'Lokasi barang wajib diisi.',
        ]);
        $barangnya = DataBarang::where('kode', $request->gabungan_barang)->first();

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        // Cari barang berdasarkan kode_barang jika ada, jika tidak berdasarkan nama, jenis, lokasi
        if ($request->filled('kode_barang')) {
            $barang = DataBarang::where('kode', $request->kode_barang)->first();
        } else {
            $barang = DataBarang::where('namaBarang', $request->nama_barang)
            ->where('jenisBarangPersediaan', $request->jenisBarangPersediaan)
            ->where('lokasi', $request->lokasiBarang)
            ->first();
        }

        // Jika jenisBarangPersediaan bukan 'Umum', cari barang berdasarkan gabungan_barang
        if (strtolower($_COOKIE['status']) !== 'umum') {
            $barang = DataBarang::where('kode', $request->gabungan_barang)->first();
        }

        // Jika transaksi Masuk dan barang tidak ada, buat baru
        if (!$barang && strtolower($request->jenisTransaksi) === 'masuk') {
            $barang = new DataBarang();
            $barang->namaBarang = $request->nama_barang;
            $barang->jenisBarangPersediaan = $request->jenisBarangPersediaan;
            $barang->lokasi = $request->lokasiBarang;
            // Membuat kode unik base36 dengan panjang 7 digit, diawali huruf 'B', dan memastikan tidak kembar
            do {
                $unique = uniqid('', true) . random_int(1000, 9999);
                $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 7, '0', STR_PAD_LEFT));
                $kode = 'B' . $kodeBase36;
            } while (DataBarang::where('kode', $kode)->exists());
            $barang->kode = $kode;
            $barang->save();
        } elseif (!$barang) {
            // Jika transaksi keluar dan barang tidak ditemukan
            return back()->withErrors(['kode_barang' => 'Barang tidak ditemukan untuk transaksi keluar.'])->withInput();
        }

        // Membuat kode unik transaksi barang, format: TB + 8 digit acak base36, pastikan unik
        do {
            $unique = uniqid('', true) . random_int(1000, 9999);
            $kodeTransaksi = 'TB' . strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 8, '0', STR_PAD_LEFT));
        } while (TransaksiBarang::where('kode', $kodeTransaksi)->exists());


        $barang->lokasi = $request->lokasiBarang;
        $barang->save();

        $transaksi = new TransaksiBarang();
        $transaksi->nama_pegawai = $_COOKIE['nama'];
        $transaksi->status_pegawai = $_COOKIE['jabatan'];
        $transaksi->idDataBarang = $barang->id;
        $transaksi->jenisTransaksi = $request->jenisTransaksi;
        $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
        $transaksi->waktu = $request->filled('waktu_transaksi') ? $request->waktu_transaksi : now()->format('H:i:s');
        $transaksi->lokasi = $request->lokasiBarang;
        $transaksi->nip = $_COOKIE['nip'];
        $transaksi->kode = $kodeTransaksi;
        if ($request->filled('alasan')) {
            $transaksi->alasan = $request->alasan;
        }
        $transaksi->save();

        return redirect()->route('dashboard', ['menu' => 'tbarang'])
            ->with('success', 'Transaksi barang berhasil disimpan.');
    }

    // public function transaksi_barang_edit(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'jenisTransaksi' => 'required|in:Masuk,Keluar',
    //         'tanggal_transaksi' => 'required|date',
    //         'waktu_transaksi' => 'nullable|date_format:H:i:s',
    //         'alasan' => 'nullable|string|max:255',
    //         'lokasi' => 'required|string|max:100',
    //         'kode_barang' => 'nullable|string|max:20',
    //     ]);

    //     if ($validator->fails()) {
    //         return back()->withErrors($validator)->withInput();
    //     }

    //     // Cari transaksi yang akan diedit
    //     $transaksi = TransaksiBarang::findOrFail($id);
    //     $barang = DataBarang::findOrFail($transaksi->idDataBarang);

    //     // Update data barang
    //     // Update lokasi barang berdasarkan transaksi terakhir


    //     // Update data transaksi
    //     $transaksi->jenisTransaksi = $request->jenisTransaksi;
    //     $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
    //     $transaksi->waktu = $request->filled('waktu_transaksi') ? $request->waktu_transaksi : $transaksi->waktu;
    //     $transaksi->lokasi = $request->lokasi;
    //     $transaksi->alasan = $request->alasan ?? null;
    //     $transaksi->save();

    //     $lastTransaksi = TransaksiBarang::where('idDataBarang', $barang->id)
    //         ->orderByDesc('tanggal_transaksi')
    //         ->orderByDesc('waktu')
    //         ->first();


    //     $barang->lokasi = $lastTransaksi->lokasi;
    //     $barang->save();

    //     return redirect()->route('dashboard', ['menu' => 'tbarang'])
    //         ->with('success', 'Transaksi barang berhasil diperbarui.');
    // }


    public function transaksi_barang_destroy($id)
    {
        $transaksi = TransaksiBarang::findOrFail($id);

        // Hitung jumlah transaksi terkait barang ini
        $barang = DataBarang::find($transaksi->idDataBarang);
        $jumlahTransaksi = 0;
        if ($barang) {
            $jumlahTransaksi = TransaksiBarang::where('idDataBarang', $barang->id)->count();
        }

        // Simpan lokasi transaksi yang akan dihapus
        $lokasiTransaksiDihapus = $transaksi->lokasi;

        $transaksi->delete();

        // Jika jumlah transaksi tinggal 1 (yang baru saja dihapus), hapus juga data barangnya
        if ($barang && $jumlahTransaksi == 1) {
            $barang->delete();
        } elseif ($barang) {
            // Jika masih ada transaksi lain, kembalikan lokasi barang ke lokasi transaksi terakhir
            $transaksiTerakhir = TransaksiBarang::where('idDataBarang', $barang->id)
            ->orderByDesc('tanggal_transaksi')
            ->orderByDesc('waktu')
            ->first();
            if ($transaksiTerakhir) {
            $barang->lokasi = $transaksiTerakhir->lokasi;
            $barang->save();
            }
        }

        // Update urutan id transaksi (reindex id)
        $transaksis = TransaksiBarang::orderBy('id')->get();
        $newId = 1;
        foreach ($transaksis as $t) {
            if ($t->id != $newId) {
            // Update id only if different
            TransaksiBarang::where('id', $t->id)->update(['id' => $newId]);
            }
            $newId++;
        }
        return redirect()->route('dashboard', ['menu' => 'tbarang'])
            ->with('success', 'Transaksi barang berhasil dihapus.' . (($barang && $jumlahTransaksi == 1) ? ' Data barang terkait juga dihapus.' : ''));
    }

    public function transaksi_kendaraan_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lokasi'            => 'required|string|max:100',
            'jenisTransaksi'    => 'required|in:Masuk,Keluar',
            'tanggal_transaksi' => 'required|date',
            // 'waktu_transaksi' optional
            // 'alasan' optional
        ], [
            'lokasi.required'            => 'Lokasi kendaraan wajib diisi.',
            'lokasi.string'              => 'Lokasi kendaraan harus berupa teks.',
            'lokasi.max'                 => 'Lokasi kendaraan maksimal 100 karakter.',
            'jenisTransaksi.required'    => 'Jenis transaksi wajib dipilih.',
            'jenisTransaksi.in'          => 'Jenis transaksi harus Masuk atau Keluar.',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi.',
            'tanggal_transaksi.date'     => 'Tanggal transaksi tidak valid.',
        ]);
        // Jika jenis_kendaraan bukan 'Umum', cari berdasarkan kendaraan_gabung, jika tidak, kosongkan $barangnya
        if (strtolower($_COOKIE['status']) !== 'umum') {
            $barangnya = DataKendaraan::where('kode', $request->kendaraan_gabung)->first();
        } elseif($request->filled('kode')) {
            $barangnya = DataKendaraan::where('kode', $request->kode)->first();
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $kendaraan = DataKendaraan::where('kode', $request->kendaraan_gabung)->first();
        if (strtolower($request->jenisTransaksi) === 'masuk') {
            $kendaraan = DataKendaraan::where('kode', $request->kode)->first();
            if ($kendaraan) {
                if ($request->filled('kode')) {
                    if ($kendaraan->status === 'Tersedia') {
                        return back()->withErrors(['nama_kendaraan' => 'Kendaraan sudah tersedia, Mohon Periksa Kembali.'])->withInput();
                    }
                    // Jika transaksi masuk dan ada kode, ubah status jadi Tersedia
                    $kendaraan->status = 'Tersedia';
                    $kendaraan->lokasi = $request->lokasi;
                    $kendaraan->save();
                }
            }else {
                    // Jika transaksi masuk dan tidak ada kode, tambah data baru
                    $kendaraan = new DataKendaraan();
                    $kendaraan->namaKendaraan = $request->nama_kendaraan;
                    $kendaraan->nomorPolisi = $request->nomor_polisi;
                    $kendaraan->jenisKendaraan = $request->jenis_kendaraan;
                    $kendaraan->status = 'Tersedia';
                    $kendaraan->lokasi = $request->lokasi;
                    // Membuat kode unik base36 dengan panjang 7 digit, diawali huruf 'K', dan memastikan tidak kembar
                    do {
                        $unique = uniqid('', true) . random_int(1000, 9999);
                        $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 7, '0', STR_PAD_LEFT));
                        $kode = 'K' . $kodeBase36;
                    } while (DataKendaraan::where('kode', $kode)->exists());
                    $kendaraan->kode = $kode;
                    $kendaraan->save();


                    $barangnya = DataKendaraan::where('namaKendaraan', $request->nama_kendaraan)
                    ->where('nomorPolisi', $request->nomor_polisi)
                    ->where('jenisKendaraan', $request->jenis_kendaraan)
                    ->first();
                }
        } elseif (strtolower($request->jenisTransaksi) === 'keluar') {
            if (!$kendaraan) {
                return back()->withErrors(['kode' => 'Kode kendaraan tidak ditemukan untuk transaksi keluar.'])->withInput();
            }
            if ($kendaraan->status === 'Tidak Tersedia') {
                return back()->withErrors(['nama_kendaraan' => 'Kendaraan tidak tersedia, Mohon Periksa Kembali.'])->withInput();
            }
            // Jika transaksi keluar, ubah status jadi Tidak Tersedia
            $kendaraan->status = 'Tidak Tersedia';
            $kendaraan->save();
        }




        // Membuat kode unik transaksi kendaraan, format: TK + 12 digit acak base36, pastikan unik
        do {
            $unique = uniqid('', true) . random_int(1000, 9999);
            $kodeTransaksi = 'TK' . strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 12, '0', STR_PAD_LEFT));
        } while (TransaksiKendaraan::where('kode', $kodeTransaksi)->exists());

        $transaksi = new TransaksiKendaraan();
        $transaksi->nama_pegawai = $_COOKIE['nama'];
        $transaksi->status_pegawai = $_COOKIE['jabatan'];
        $transaksi->idDataKendaraan = $barangnya->id;
        $transaksi->jenisTransaksi = $request->jenisTransaksi;
        $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
        $transaksi->kode = $kodeTransaksi;
        $transaksi->lokasi = $request->lokasi;
        $transaksi->nip = $_COOKIE['nip'];
        $transaksi->alasan = $request->alasan ?? null;
        $transaksi->waktu = $request->filled('waktu_transaksi') ? $request->waktu_transaksi : Carbon::now()->format('H:i:s');
        $transaksi->save();

        // Update status kendaraan
        if (strtolower($request->jenisTransaksi) === 'keluar') {
            $kendaraan->status = 'Tidak Tersedia';
        } elseif (strtolower($request->jenisTransaksi) === 'masuk') {
            $kendaraan->status = 'Tersedia';
        }
        $kendaraan->save();

        return redirect()->route('dashboard', ['menu' => 'tkendaraan'])
            ->with('success', 'Transaksi kendaraan berhasil disimpan.');
    }

    // public function transaksi_kendaraan_edit(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'lokasi'            => 'required|string|max:100',
    //         'tanggal_transaksi' => 'required|date',
    //     ]);

    //     if ($validator->fails()) {
    //         return back()->withErrors($validator)->withInput();
    //     }

    //     $transaksi = TransaksiKendaraan::findOrFail($id);
    //     $kendaraan = DataKendaraan::findOrFail($transaksi->idDataKendaraan);

    //     // Jika jenis transaksi diubah
    //     if (strtolower($transaksi->jenisTransaksi) !== strtolower($request->jenisTransaksi)) {
    //         if (strtolower($request->jenisTransaksi) === 'keluar') {
    //             if ($kendaraan->status === 'Tidak Tersedia') {
    //                 return back()->withErrors(['nama_kendaraan' => 'Kendaraan tidak tersedia, Mohon Periksa Kembali.'])->withInput();
    //             }
    //             $kendaraan->status = 'Tidak Tersedia';
    //         } elseif (strtolower($request->jenisTransaksi) === 'masuk') {
    //             if ($kendaraan->status === 'Tersedia') {
    //                 return back()->withErrors(['nama_kendaraan' => 'Kendaraan sudah tersedia, Mohon Periksa Kembali.'])->withInput();
    //             }
    //             $kendaraan->status = 'Tersedia';
    //             $kendaraan->lokasi = $request->lokasi;
    //         }
    //         $kendaraan->save();
    //     }

    //     $kendaraan->lokasi = $request->lokasi;
    //     $kendaraan->save();

    //     // Update data transaksi
    //     $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
    //     $transaksi->alasan = $request->alasan ?? null;
    //     $transaksi->lokasi = $request->lokasi;
    //     $transaksi->waktu = $request->filled('waktu_transaksi') ? $request->waktu_transaksi : $transaksi->waktu;
    //     $transaksi->save();

    //     return redirect()->route('dashboard', ['menu' => 'tkendaraan'])
    //         ->with('success', 'Transaksi kendaraan berhasil diperbarui.');
    // }

    public function transaksi_kendaraan_destroy($id)
    {
        $transaksi = TransaksiKendaraan::findOrFail($id);

        // Hitung jumlah transaksi terkait kendaraan ini
        $kendaraan = DataKendaraan::find($transaksi->idDataKendaraan);
        $jumlahTransaksi = 0;
        if ($kendaraan) {
            $jumlahTransaksi = TransaksiKendaraan::where('idDataKendaraan', $kendaraan->id)->count();
        }

        // Kembalikan status kendaraan jika transaksi dihapus
        if ($kendaraan) {
            if ($transaksi->jenisTransaksi === 'Keluar') {
            // Jika transaksi keluar dihapus, kendaraan harus tersedia
            $kendaraan->status = 'Tersedia';
            } elseif ($transaksi->jenisTransaksi === 'Masuk') {
            // Jika transaksi masuk dihapus, kendaraan harus tidak tersedia
            $kendaraan->status = 'Tidak Tersedia';
            }
            $kendaraan->save();
        }

        $transaksi->delete();

        // Jika jumlah transaksi tinggal 1 (yang baru saja dihapus), hapus juga data kendaraannya
        if ($kendaraan && $jumlahTransaksi == 1) {
            $kendaraan->delete();
        }

        // Update urutan id transaksi (reindex id)
        $transaksis = TransaksiKendaraan::orderBy('id')->get();
        $newId = 1;
        foreach ($transaksis as $t) {
            if ($t->id != $newId) {
            // Update id only if different
            TransaksiKendaraan::where('id', $t->id)->update(['id' => $newId]);
            }
            $newId++;
        }

        return redirect()->route('dashboard', ['menu' => 'tkendaraan'])
            ->with('success', 'Transaksi kendaraan berhasil dihapus.' . (($kendaraan && $jumlahTransaksi == 1) ? ' Data kendaraan terkait juga dihapus.' : ''));
    }

    public function data_pegawai_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip'      => 'required|numeric|unique:data_pegawai,nipPegawai',
            'nama'     => 'required|string|max:255',
            'jabatan'  => 'required|string|max:100',
            'password' => [
            'required',
            'string',
            'min:8',
            'regex:/[a-z]/',      // huruf kecil
            'regex:/[A-Z]/',      // huruf kapital
            'regex:/[0-9]/',      // angka
            'regex:/[\W_]/',      // simbol
            ],
        ], [
            'nip.required'        => 'NIP Pegawai wajib diisi.',
            'nip.numeric'         => 'NIP Pegawai harus berupa angka.',
            'nip.unique'          => 'NIP Pegawai sudah terdaftar.',
            'nama.required'       => 'Nama pegawai wajib diisi.',
            'jabatan.required'    => 'Jabatan pegawai wajib diisi.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal 8 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'password.regex'      => 'Password harus mengandung huruf kecil, huruf kapital, angka, dan simbol.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pegawai = new DataPegawai();
        $pegawai->nipPegawai = $request->nip;
        $pegawai->namaPegawai = $request->nama;
        $pegawai->status = $request->statusPegawai;
        $pegawai->jabatan = $request->jabatan;
        $pegawai->password = Hash::make($request->password);
        $pegawai->save();

        return redirect()->route('dashboard', ['menu' => 'dpegawai'])
            ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function data_pegawai_edit(Request $request, $id)
    {
        $pegawai = DataPegawai::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nip'      => 'required|numeric|unique:data_pegawai,nipPegawai,' . $pegawai->id,
            'nama'     => 'required|string|max:255',
            'jabatan'  => 'required|string|max:100',
            'password' => [
            'nullable',
            'string',
            'min:8',
            'regex:/[a-z]/',      // huruf kecil
            'regex:/[A-Z]/',      // huruf kapital
            'regex:/[0-9]/',      // angka
            'regex:/[\W_]/',      // simbol
            ],
        ], [
            'nip.required'        => 'NIP Pegawai wajib diisi.',
            'nip.numeric'         => 'NIP Pegawai harus berupa angka.',
            'nip.unique'          => 'NIP Pegawai sudah terdaftar.',
            'nama.required'       => 'Nama pegawai wajib diisi.',
            'jabatan.required'    => 'Jabatan pegawai wajib diisi.',
            'password.min'        => 'Password minimal 8 karakter.',
            'password.regex'      => 'Password harus mengandung huruf kecil, huruf kapital, angka, dan simbol.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pegawai->nipPegawai = $request->nip;
        $pegawai->namaPegawai = $request->nama;
        $pegawai->password = Hash::make($request->password);
        $pegawai->status = $request->statusPegawai;
        $pegawai->jabatan = $request->jabatan;
        $pegawai->save();

        return redirect()->route('dashboard', ['menu' => 'dpegawai'])
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function data_pegawai_destroy($id)
    {
        $pegawai = DataPegawai::findOrFail($id);
        $pegawai->delete();

        return redirect()->route('dashboard', ['menu' => 'dpegawai'])
            ->with('success', 'Data pegawai berhasil dihapus.');
    }
}
