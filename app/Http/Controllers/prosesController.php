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
            'nama_pegawai' => 'required|string|max:255',
            'status_pegawai' => 'required|string|max:100',
            'nama_barang' => 'required',
            'jenisTransaksi' => 'required|in:Masuk,Keluar',
            'jumlahPinjam' => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
        ], [
            'nama_pegawai.required' => 'Nama pegawai wajib diisi.',
            'status_pegawai.required' => 'Status pegawai wajib diisi.',
            'nama_barang.required' => 'Nama barang wajib dipilih.',
            'jenisTransaksi.required' => 'Jenis transaksi wajib dipilih.',
            'jumlahPinjam.required' => 'Jumlah wajib diisi.',
            'jumlahPinjam.integer' => 'Jumlah harus berupa angka.',
            'jumlahPinjam.min' => 'Jumlah minimal 1.',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi.',
            'tanggal_transaksi.date' => 'Tanggal transaksi tidak valid.',
        ]);

        // Tambahan validasi agar jumlahPinjam tidak boleh minus
        if ($request->jumlahPinjam < 1) {
            return back()->withErrors(['jumlahPinjam' => 'Jumlah tidak boleh kurang dari 1.'])->withInput();
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Ambil data barang
        $baranginput = DataBarang::where('namaBarang', $request->nama_barang)
            ->where('jenisBarangPersediaan', $request->jenisBarangPersediaan)
            ->where('lokasi', $request->input('lokasiBarang'))
            ->first();

        // Pastikan field jumlahTersedia sudah ada di tabel data_barang
        if ($request->jenisTransaksi === 'Masuk') {
            if (!$baranginput) {
            //     // Jika barang belum ada, buat data barang baru
                $barang = new DataBarang();
                $barang->namaBarang = $request->input('namaBarang', $request->nama_barang);
                $barang->jenisBarangPersediaan = $request->input('jenisBarangPersediaan', $request->jenisBarangPersediaan);
                $barang->jumlahTotal = $request->jumlahPinjam;
                $barang->lokasi = $request->input('lokasiBarang');
                // Membuat kode unik base36 dengan panjang 7 digit, diawali huruf 'B', dan memastikan tidak kembar
                do {
                    $unique = uniqid('', true) . random_int(1000, 9999);
                    $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 7, '0', STR_PAD_LEFT));
                    $kode = 'B' . $kodeBase36;
                } while (DataBarang::where('kode', $kode)->exists());
                $barang->kode = $kode;
                $barang->save();
            }else{
            // Tambah jumlahTotal
            $baranginput->jumlahTotal += $request->jumlahPinjam;
            $baranginput->save();
            }
        } elseif ($request->jenisTransaksi === 'Keluar') {
            $baranginput->jumlahTotal += $request->jumlahPinjam;
            $baranginput->save();
        }

        // Cari idDataBarang yang namaBarang dan jenisBarangPersediaan sama
        $idDataBarangCek = DataBarang::where('namaBarang', $request->nama_barang)
            ->where('jenisBarangPersediaan', $request->jenisBarangPersediaan)
            ->where('lokasi', $request->input('lokasiBarang'))
            ->value('id');
        $transaksi = new TransaksiBarang();
        $transaksi->nama_pegawai = $request->nama_pegawai;
        $transaksi->status_pegawai = $request->status_pegawai;
        $transaksi->idDataBarang = $idDataBarangCek;
        $transaksi->jenisTransaksi = $request->jenisTransaksi;
        $transaksi->jumlahPinjam = $request->jumlahPinjam;
        $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
        $transaksi->waktu = $request->waktu_transaksi;
        if ($request->filled('alasan')) {
            $transaksi->alasan = $request->alasan;
        }
        // Membuat kode unik transaksi barang, format: TB + 8 digit acak base36, pastikan unik
        do {
            $unique = uniqid('', true) . random_int(1000, 9999);
            $kodeTransaksi = 'TB' . strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 8, '0', STR_PAD_LEFT));
        } while (TransaksiBarang::where('kode', $kodeTransaksi)->exists());
        $transaksi->kode = $kodeTransaksi;
        $transaksi->save();

        return redirect()->route('dashboard', ['menu' => 'tbarang'])
            ->with('success', 'Transaksi barang berhasil disimpan.');
    }

    public function transaksi_barang_edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_pegawai' => 'required|string|max:255',
            'status_pegawai' => 'required|string|max:100',
            'nama_barang' => 'required',
            'jenisBarangPersediaan' => 'required',
            'jenisTransaksi' => 'required|in:Masuk,Keluar',
            'jumlahPinjam' => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
        ], [
            'nama_pegawai.required' => 'Nama pegawai wajib diisi.',
            'status_pegawai.required' => 'Status pegawai wajib diisi.',
            'nama_barang.required' => 'Nama barang wajib dipilih.',
            'jenisBarangPersediaan.required' => 'Kategori barang wajib dipilih.',
            'jenisTransaksi.required' => 'Jenis transaksi wajib dipilih.',
            'jumlahPinjam.required' => 'Jumlah wajib diisi.',
            'jumlahPinjam.integer' => 'Jumlah harus berupa angka.',
            'jumlahPinjam.min' => 'Jumlah minimal 1.',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi.',
            'tanggal_transaksi.date' => 'Tanggal transaksi tidak valid.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $transaksi = TransaksiBarang::findOrFail($id);
        $barangLama = DataBarang::find($transaksi->idDataBarang);

        // Kembalikan stok barang lama sesuai jenis transaksi lama
        if ($barangLama) {
            if ($transaksi->jenisTransaksi === 'Masuk') {
                $barangLama->jumlahTotal -= $transaksi->jumlahPinjam;
                if ($barangLama->jumlahTotal < 0) $barangLama->jumlahTotal = 0;
            } elseif ($transaksi->jenisTransaksi === 'Keluar') {
                $barangLama->jumlahTotal -= $transaksi->jumlahPinjam;
                if ($barangLama->jumlahTotal < 0) $barangLama->jumlahTotal = 0;
            }
            $barangLama->save();
        }

        // Ambil barang baru berdasarkan nama dan kategori
        $barangBaru = DataBarang::where('namaBarang', $request->nama_barang)
            ->where('jenisBarangPersediaan', $request->jenisBarangPersediaan)
            ->where('lokasi', $request->input('lokasi'))
            ->first();

        // Jika barang tidak ditemukan, buat baru (hanya untuk transaksi Masuk)
        if (!$barangBaru && $request->jenisTransaksi === 'Masuk') {
            $barangBaru = new DataBarang();
            $barangBaru->namaBarang = $request->nama_barang;
            $barangBaru->jenisBarangPersediaan = $request->jenisBarangPersediaan;
            $barangBaru->jumlahTotal = $request->jumlahPinjam;
            $barangBaru->lokasi = $request->input('lokasi');
            // Membuat kode unik base36 dengan panjang 7 digit, diawali huruf 'B', dan memastikan tidak kembar
            do {
                $unique = uniqid('', true) . random_int(1000, 9999);
                $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 7, '0', STR_PAD_LEFT));
                $kode = 'B' . $kodeBase36;
            } while (DataBarang::where('kode', $kode)->exists());
            $barangBaru->kode = $kode;
            $barangBaru->save();
        }

        // Update stok barang baru sesuai transaksi baru
        if ($barangBaru) {
            if ($request->jenisTransaksi === 'Masuk') {
                $barangBaru->jumlahTotal += $request->jumlahPinjam;
            } elseif ($request->jenisTransaksi === 'Keluar') {
                $barangBaru->jumlahTotal += $request->jumlahPinjam;
            }
            $barangBaru->save();
        }

        // Update transaksi
        $transaksi->nama_pegawai = $request->nama_pegawai;
        $transaksi->status_pegawai = $request->status_pegawai;
        $transaksi->idDataBarang = $barangBaru ? $barangBaru->id : null;
        $transaksi->jenisTransaksi = $request->jenisTransaksi;
        $transaksi->jumlahPinjam = $request->jumlahPinjam;
        $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
        $transaksi->waktu = $request->waktu_transaksi;
        $transaksi->alasan = $request->filled('alasan') ? $request->alasan : null;
        $transaksi->save();

        return redirect()->route('dashboard', ['menu' => 'tbarang'])
            ->with('success', 'Transaksi barang berhasil diperbarui.');
    }

    public function transaksi_barang_destroy($id)
    {
        $transaksi = TransaksiBarang::findOrFail($id);

        // Kembalikan stok barang jika transaksi dihapus
        $barang = DataBarang::find($transaksi->idDataBarang);
        if ($barang) {
            if ($transaksi->jenisTransaksi === 'Masuk') {
                // Jika transaksi masuk dihapus, stok dikurangi
                $barang->jumlahTotal -= $transaksi->jumlahPinjam;
                if ($barang->jumlahTotal < 0) $barang->jumlahTotal = 0;
            } elseif ($transaksi->jenisTransaksi === 'Keluar') {
                // Jika transaksi keluar dihapus, stok dikembalikan
                $barang->jumlahTotal += $transaksi->jumlahPinjam;
            }
            $barang->save();
        }

        $transaksi->delete();

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
            ->with('success', 'Transaksi barang berhasil dihapus.');
    }

    public function transaksi_kendaraan_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pegawai' => 'required|string|max:255',
            'status_pegawai' => 'required|string|max:100',
            'nama_kendaraan' => 'required',
            'jenisTransaksi' => 'required|in:Masuk,Keluar',
            'tanggal_transaksi' => 'required|date',
        ], [
            'nama_pegawai.required' => 'Nama pegawai wajib diisi.',
            'status_pegawai.required' => 'Status pegawai wajib diisi.',
            'nama_kendaraan.required' => 'Nama kendaraan wajib dipilih.',
            'nama_kendaraan.exists' => 'Kendaraan yang dipilih tidak ditemukan.',
            'jenisTransaksi.required' => 'Jenis transaksi wajib dipilih.',
            'jumlahPinjam.required' => 'Jumlah wajib diisi.',
            'jumlahPinjam.integer' => 'Jumlah harus berupa angka.',
            'jumlahPinjam.min' => 'Jumlah minimal 1.',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi.',
            'tanggal_transaksi.date' => 'Tanggal transaksi tidak valid.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Ambil idDataKendaraan berdasarkan namaKendaraan dan nomorPolisi
        $kendaraan = DataKendaraan::where('namaKendaraan', $request->nama_kendaraan)
            ->where('nomorPolisi', $request->nomor_polisi)
            ->first();

        if (!$kendaraan) {
            return back()->withErrors(['nama_kendaraan' => 'Kendaraan atau plat nomor tidak sinkron.'])->withInput();
        }

        // Logika validasi status kendaraan sesuai jenis transaksi
        if (strtolower($request->jenisTransaksi) === 'keluar') {
            // Jika ingin meminjam, kendaraan harus tersedia
            if ($kendaraan->status !== 'Tersedia') {
            return back()->withErrors(['nama_kendaraan' => 'Kendaraan tidak tersedia untuk dipinjam.'])->withInput();
            }
        } elseif (strtolower($request->jenisTransaksi) === 'masuk') {
            // Jika ingin mengembalikan, kendaraan harus tidak tersedia
            if ($kendaraan->status === 'Tersedia') {
            return back()->withErrors(['nama_kendaraan' => 'Kendaraan sudah tersedia, tidak perlu dikembalikan.'])->withInput();
            }
        }

        // Validasi apakah data kendaraan ditemukan
        if (!$kendaraan) {
            return back()->withErrors(['nama_kendaraan' => 'Data kendaraan tidak ditemukan atau tidak sesuai.'])->withInput();
        }
        // Jika ingin meminjam, kendaraan harus tersedia
        if ($kendaraan->keterangan !== 'Ada') {
        return back()->withErrors(['nama_kendaraan' => 'Kendaraan tidak tersedia untuk dipinjam.'])->withInput();
        }
        // Membuat kode unik base36 dengan panjang 12 digit, diawali huruf 'TK', dan memastikan tidak kembar
        $usedCodes = [];
        function generateKodeUnik12($usedCodes, $prefix = 'TK', $length = 12) {
            do {
            $unique = uniqid('', true) . random_int(1000, 9999);
            $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), $length, '0', STR_PAD_LEFT));
            $kode = $prefix . $kodeBase36;
            } while (in_array($kode, $usedCodes));
            return $kode;
        }

        $kode = generateKodeUnik12($usedCodes);

        $transaksi = new TransaksiKendaraan();
        $transaksi->nama_pegawai = $request->nama_pegawai;
        $transaksi->status_pegawai = $request->status_pegawai;
        $transaksi->idDataKendaraan = $kendaraan->id;
        $transaksi->jenisTransaksi = $request->jenisTransaksi;
        $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
        $transaksi->kode = $kode;
        $transaksi->alasan = $request->alasan ?? null; // Jika alasan tidak diisi, set null
        // Jika ada waktu transaksi, simpan juga
        if ($request->filled('waktu_transaksi')) {
            $transaksi->waktu = $request->waktu_transaksi;
        } else {
            $transaksi->waktu = Carbon::now()->format('H:i:s'); // Atau bisa diisi dengan waktu sekarang
        }
        $transaksi->save();

        // Update status kendaraan
        if (strtolower($request->jenisTransaksi) === 'keluar') {
            $kendaraan->status = 'Tidak Tersedia';
            $kendaraan->save();
        } elseif (strtolower($request->jenisTransaksi) === 'masuk') {
            $kendaraan->status = 'Tersedia';
            $kendaraan->save();
        }

        return redirect()->route('dashboard', ['menu' => 'tkendaraan'])
            ->with('success', 'Transaksi kendaraan berhasil disimpan.');
    }

    public function transaksi_kendaraan_edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_pegawai' => 'required|string|max:255',
            'status_pegawai' => 'required|string|max:100',
            'nama_kendaraan' => 'required',
            'jenisTransaksi' => 'required|in:Masuk,Keluar',
            'tanggal_transaksi' => 'required|date',
        ], [
            'nama_pegawai.required' => 'Nama pegawai wajib diisi.',
            'status_pegawai.required' => 'Status pegawai wajib diisi.',
            'nama_kendaraan.required' => 'Nama kendaraan wajib dipilih.',
            'jenisTransaksi.required' => 'Jenis transaksi wajib dipilih.',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi.',
            'tanggal_transaksi.date' => 'Tanggal transaksi tidak valid.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $transaksi = TransaksiKendaraan::findOrFail($id);

        // Ambil kendaraan lama dan barug
        $kendaraanLama = DataKendaraan::find($transaksi->idDataKendaraan);
        $kendaraanBaru = DataKendaraan::where('namaKendaraan', $request->nama_kendaraan)
            ->where('nomorPolisi', $request->nomor_polisi)
            ->first();

        if (!$kendaraanBaru) {
            return back()->withErrors(['nama_kendaraan' => 'Kendaraan atau plat nomor tidak sinkron.'])->withInput();
        }

        // Jika kendaraan diganti, kembalikan status kendaraan lama
        if ($kendaraanLama && $kendaraanLama->id !== $kendaraanBaru->id) {
            if ($transaksi->jenisTransaksi === 'Keluar') {
                $kendaraanLama->status = 'Tersedia';
            } elseif ($transaksi->jenisTransaksi === 'Masuk') {
                $kendaraanLama->status = 'Tidak Tersedia';
            }
            $kendaraanLama->save();
        }

        // Update data transaksi
        $transaksi->nama_pegawai = $request->nama_pegawai;
        $transaksi->status_pegawai = $request->status_pegawai;
        $transaksi->idDataKendaraan = $kendaraanBaru->id;
        $transaksi->jenisTransaksi = $request->jenisTransaksi;
        $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
        if ($request->filled('waktu_transaksi')) {
            $transaksi->waktu = $request->waktu_transaksi;
        } else {
            $transaksi->waktu = Carbon::now()->format('H:i:s');
        }
        if ($request->filled('alasan')) {
            $transaksi->alasan = $request->alasan;
        } else {
            $transaksi->alasan = null;
        }
        $transaksi->save();

        // Update status kendaraan baru
        if (strtolower($request->jenisTransaksi) === 'keluar') {
            $kendaraanBaru->status = 'Tidak Tersedia';
        } elseif (strtolower($request->jenisTransaksi) === 'masuk') {
            $kendaraanBaru->status = 'Tersedia';
        }
        $kendaraanBaru->save();

        return redirect()->route('dashboard', ['menu' => 'tkendaraan'])
            ->with('success', 'Transaksi kendaraan berhasil diperbarui.');
    }

    public function transaksi_kendaraan_destroy($id)
    {
        $transaksi = TransaksiKendaraan::findOrFail($id);

        // Kembalikan status kendaraan jika transaksi dihapus
        $kendaraan = DataKendaraan::find($transaksi->idDataKendaraan);
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
            ->with('success', 'Transaksi kendaraan berhasil dihapus.');
    }
}
