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

    public function data_barang_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'namaBarang' => 'required|string|max:255',
            'jenisBarangPersediaan' => 'required|string|max:100',
            'jumlahTotal' => 'required|integer|min:1',
        ], [
            'namaBarang.required' => 'Nama barang wajib diisi.',
            'jenisBarangPersediaan.required' => 'Kategori wajib diisi.',
            'jumlahTotal.required' => 'Jumlah total wajib diisi.',
            'jumlahTotal.integer' => 'Jumlah total harus berupa angka.',
            'jumlahTotal.min' => 'Jumlah total minimal 1.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cek apakah nama barang dan kategori sudah ada
        $exists = DataBarang::where('namaBarang', $request->namaBarang)
            ->where('jenisBarangPersediaan', $request->jenisBarangPersediaan)
            ->exists();

        if ($exists) {
            return back()->withErrors(['namaBarang' => 'Nama barang dengan kategori tersebut sudah ada.'])->withInput();
        }

        $barang = new DataBarang();
        $barang->namaBarang = $request->namaBarang;
        $barang->jenisBarangPersediaan = $request->jenisBarangPersediaan;
        $barang->jumlahTotal = $request->jumlahTotal;
        $barang->jumlahTersedia = $request->jumlahTotal;
        // Membuat kode unik base36 dengan panjang 7 digit, diawali huruf 'B', dan memastikan tidak kembar
        do {
            $unique = uniqid('', true) . random_int(1000, 9999);
            $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 7, '0', STR_PAD_LEFT));
            $kode = 'B' . $kodeBase36;
        } while (DataBarang::where('kode', $kode)->exists());
        $barang->kode = $kode;
        $barang->save();
        return redirect()->route('dashboard', ['menu' => 'barang'])
            ->with('success', 'Data barang berhasil disimpan.');
    }


    public function data_barang_edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'namaBarang' => 'required|string|max:255',
            'jenisBarangPersediaan' => 'required|string|max:100',
            'jumlahTotal' => 'required|integer|min:1',
        ], [
            'namaBarang.required' => 'Nama barang wajib diisi.',
            'jenisBarangPersediaan.required' => 'Kategori wajib diisi.',
            'jumlahTotal.required' => 'Jumlah total wajib diisi.',
            'jumlahTotal.integer' => 'Jumlah total harus berupa angka.',
            'jumlahTotal.min' => 'Jumlah total minimal 1.',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $barang = DataBarang::findOrFail($id);
        $jumlahTotalLama = $barang->jumlahTotal;
        $jumlahTersediaLama = $barang->jumlahTersedia;
        $jumlahTotalBaru = $request->jumlahTotal;

        // Hitung selisih perubahan total
        $selisih = $jumlahTotalBaru - $jumlahTotalLama;

        // Jika dikurangi, pastikan jumlahTersedia tidak lebih besar dari jumlahTotal baru
        if ($selisih < 0) {
            // Jika pengurangan menyebabkan jumlah tersedia menjadi 0, error
            if ($jumlahTersediaLama + $selisih <= 0) {
            return back()->withErrors(['jumlahTotal' => 'Jumlah total tidak boleh dikurangi sehingga barang tersedia menjadi 0 atau kurang.'])->withInput();
            }
        }

        // Update jumlahTersedia sesuai perubahan jumlahTotal
        $barang->jumlahTotal = $jumlahTotalBaru;
        $barang->namaBarang = $request->namaBarang;
        $barang->jenisBarangPersediaan = $request->jenisBarangPersediaan;

        // Jika jumlahTotal bertambah, tambahkan ke jumlahTersedia
        if ($selisih > 0) {
            $barang->jumlahTersedia += $selisih;
        }
        // Jika jumlahTotal berkurang, kurangi jumlahTersedia dengan selisih
        elseif ($selisih < 0) {
            $barang->jumlahTersedia += $selisih; // selisih negatif, jadi mengurangi
            if ($barang->jumlahTersedia < 0) {
            $barang->jumlahTersedia = 0;
            }
        }

        $barang->save();

        return redirect()->route('dashboard', ['menu' => 'barang'])
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    public function data_barang_destroy($id)
    {
        // Cek apakah barang sudah digunakan di transaksi
        $transaksiCount = TransaksiBarang::where('idDataBarang', $id)->count();
        if ($transaksiCount > 0) {
            return redirect()->route('dashboard', ['menu' => 'barang'])
            ->withErrors(['error' => 'Data barang tidak dapat dihapus karena sudah digunakan pada transaksi.']);
        }

        $barang = DataBarang::findOrFail($id);
        $barang->delete();

        return redirect()->route('dashboard', ['menu' => 'barang'])
            ->with('success', 'Data barang berhasil dihapus.');
    }

    public function data_kendaraan_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'namaKendaraan' => 'required|string|max:255',
            'jenisKendaraan' => 'required|string|max:100',
            'nomorPolisi' => 'required|string|max:100',
        ], [
            'namaKendaraan.required' => 'Nama kendaraan wajib diisi.',
            'jenisKendaraan.required' => 'Jenis kendaraan wajib diisi.',
            'nomorPolisi.required' => 'Nomor polisi atau ciri wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cek apakah nama kendaraan dan nomor polisi sudah ada
        $exists = DataKendaraan::where('namaKendaraan', $request->namaKendaraan)
            ->where('nomorPolisi', $request->nomorPolisi)
            ->exists();

        if ($exists) {
            return back()->withErrors(['namaKendaraan' => 'Nama kendaraan dengan nomor polisi tersebut sudah ada.'])->withInput();
        }

        $kendaraan = new DataKendaraan();
        $kendaraan->namaKendaraan = $request->namaKendaraan;
        $kendaraan->jenisKendaraan = $request->jenisKendaraan;
        $kendaraan->nomorPolisi = $request->nomorPolisi;
        // Jika ada alasanKendaraan, gabungkan dengan keterangan
        if ($request->filled('alasanKendaraan')) {
            $kendaraan->keterangan = trim($request->keterangan . ' ' . $request->alasanKendaraan);
        } else {
            $kendaraan->keterangan = $request->keterangan;
        }
        // Jika ingin meminjam, kendaraan harus tersedia
        if ($request->keterangan !== 'Ada') {
            $kendaraan->status = 'Tidak Tersedia';
        } else{
            $kendaraan->status = 'Tersedia';
        }
        // Membuat kode unik base36 dengan panjang 7 digit, diawali huruf 'K', dan memastikan tidak kembar
        do {
            $unique = uniqid('', true) . random_int(1000, 9999);
            $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 7, '0', STR_PAD_LEFT));
            $kode = 'K' . $kodeBase36;
        } while (DataKendaraan::where('kode', $kode)->exists());
        $kendaraan->kode = $kode;
        $kendaraan->save();

        return redirect()->route('dashboard', ['menu' => 'kendaraan'])
            ->with('success', 'Data kendaraan berhasil disimpan.');
    }

    public function data_kendaraan_edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'namaKendaraan' => 'required|string|max:255',
            'jenisKendaraan' => 'required|string|max:100',
            'nomorPolisi' => 'required|string|max:100',
        ], [
            'namaKendaraan.required' => 'Nama kendaraan wajib diisi.',
            'jenisKendaraan.required' => 'Jenis kendaraan wajib diisi.',
            'nomorPolisi.required' => 'Nomor polisi atau ciri wajib diisi.',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cek apakah nama kendaraan dan nomor polisi sudah ada (kecuali untuk data ini sendiri)
        $exists = DataKendaraan::where('namaKendaraan', $request->namaKendaraan)
            ->where('nomorPolisi', $request->nomorPolisi)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['namaKendaraan' => 'Nama kendaraan dengan nomor polisi tersebut sudah ada.'])->withInput();
        }

        $kendaraan = DataKendaraan::findOrFail($id);
        $kendaraan->namaKendaraan = $request->namaKendaraan;
        $kendaraan->jenisKendaraan = $request->jenisKendaraan;
        $kendaraan->nomorPolisi = $request->nomorPolisi;
        // Jika ada alasanKendaraan, gabungkan dengan keterangan
        if ($request->filled('alasanKendaraan')) {
            $kendaraan->keterangan = trim($request->keterangan . ' ' . $request->alasanKendaraan);
        } else {
            $kendaraan->keterangan = $request->keterangan;
        }
        // Jika ingin meminjam, kendaraan harus tersedia
        if ($request->keterangan !== 'Ada') {
            $kendaraan->status = 'Tidak Tersedia';
        } else{
            $kendaraan->status = 'Tersedia';
        }
        $kendaraan->save();

        return redirect()->route('dashboard', ['menu' => 'kendaraan'])
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function data_kendaraan_destroy($id)
    {
        // Cek apakah kendaraan sudah digunakan di transaksi
        $transaksiCount = TransaksiKendaraan::where('idDataKendaraan', $id)->count();
        if ($transaksiCount > 0) {
            return redirect()->route('dashboard', ['menu' => 'kendaraan'])
            ->withErrors(['error' => 'Data kendaraan tidak dapat dihapus karena sudah digunakan pada transaksi.']);
        }

        $kendaraan = DataKendaraan::findOrFail($id);
        $kendaraan->delete();

        return redirect()->route('dashboard', ['menu' => 'kendaraan'])
            ->with('success', 'Data kendaraan berhasil dihapus.');
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
        $barang = DataBarang::where('namaBarang', $request->nama_barang)
            ->where('jenisBarangPersediaan', $request->jenisBarangPersediaan)
            ->first();

        // Pastikan field jumlahTersedia sudah ada di tabel data_barang
        if ($request->jenisTransaksi === 'Masuk') {
            if (!$barang) {
                // Jika barang belum ada, buat data barang baru
                $barang = new DataBarang();
                $barang->namaBarang = $request->input('namaBarang', $request->nama_barang);
                $barang->jenisBarangPersediaan = $request->input('jenisBarangPersediaan', $request->jenisBarangPersediaan);
                $barang->jumlahTotal = $request->jumlahPinjam;
                $barang->jumlahTersedia = $request->jumlahPinjam;
                // Membuat kode unik base36 dengan panjang 7 digit, diawali huruf 'B', dan memastikan tidak kembar
                do {
                    $unique = uniqid('', true) . random_int(1000, 9999);
                    $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 7, '0', STR_PAD_LEFT));
                    $kode = 'B' . $kodeBase36;
                } while (DataBarang::where('kode', $kode)->exists());
                $barang->kode = $kode;
                $barang->save();
            }
            // Tambah jumlahTersedia
            $barang->jumlahTotal += $request->jumlahPinjam;
            $barang->jumlahTersedia += $request->jumlahPinjam;
        } elseif ($request->jenisTransaksi === 'Keluar') {
            $barang->jumlahTersedia -= $request->jumlahPinjam;
            $barang->jumlahTotal += $request->jumlahPinjam;
        }
        $barang->save();

        // Cari idDataBarang yang namaBarang dan jenisBarangPersediaan sama
        $idDataBarangCek = DataBarang::where('namaBarang', $request->nama_barang)
            ->where('jenisBarangPersediaan', $request->jenisBarangPersediaan)
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
                $barangLama->jumlahTersedia -= $transaksi->jumlahPinjam;
                $barangLama->jumlahTotal -= $transaksi->jumlahPinjam;
                if ($barangLama->jumlahTersedia < 0) $barangLama->jumlahTersedia = 0;
                if ($barangLama->jumlahTotal < 0) $barangLama->jumlahTotal = 0;
            } elseif ($transaksi->jenisTransaksi === 'Keluar') {
                $barangLama->jumlahTersedia += $transaksi->jumlahPinjam;
                $barangLama->jumlahTotal -= $transaksi->jumlahPinjam;
                if ($barangLama->jumlahTersedia > $barangLama->jumlahTotal) {
                    $barangLama->jumlahTersedia = $barangLama->jumlahTotal;
                }
                if ($barangLama->jumlahTotal < 0) $barangLama->jumlahTotal = 0;
            }
            $barangLama->save();
        }

        // Ambil barang baru berdasarkan nama dan kategori
        $barangBaru = DataBarang::where('namaBarang', $request->nama_barang)
            ->where('jenisBarangPersediaan', $request->jenisBarangPersediaan)
            ->first();

        // Jika barang tidak ditemukan, buat baru (hanya untuk transaksi Masuk)
        if (!$barangBaru && $request->jenisTransaksi === 'Masuk') {
            $barangBaru = new DataBarang();
            $barangBaru->namaBarang = $request->nama_barang;
            $barangBaru->jenisBarangPersediaan = $request->jenisBarangPersediaan;
            $barangBaru->jumlahTotal = $request->jumlahPinjam;
            $barangBaru->jumlahTersedia = $request->jumlahPinjam;
            // Membuat kode unik base36 dengan panjang 7 digit, diawali huruf 'B', dan memastikan tidak kembar
            do {
                $unique = uniqid('', true) . random_int(1000, 9999);
                $kodeBase36 = strtoupper(str_pad(base_convert(crc32($unique), 10, 36), 7, '0', STR_PAD_LEFT));
                $kode = 'B' . $kodeBase36;
            } while (DataBarang::where('kode', $kode)->exists());
            $barangBaru->kode = $kode;
            $barangBaru->save();
        }

        // Validasi stok untuk transaksi keluar
        if ($request->jenisTransaksi === 'Keluar') {
            if (!$barangBaru || $barangBaru->jumlahTersedia < $request->jumlahPinjam) {
                return back()->withErrors(['jumlahPinjam' => 'Stok barang tidak mencukupi.'])->withInput();
            }
        }

        // Update stok barang baru sesuai transaksi baru
        if ($barangBaru) {
            if ($request->jenisTransaksi === 'Masuk') {
                $barangBaru->jumlahTotal += $request->jumlahPinjam;
                $barangBaru->jumlahTersedia += $request->jumlahPinjam;
            } elseif ($request->jenisTransaksi === 'Keluar') {
                $barangBaru->jumlahTersedia -= $request->jumlahPinjam;
                $barangBaru->jumlahTotal += $request->jumlahPinjam;
                if ($barangBaru->jumlahTersedia < 0) $barangBaru->jumlahTersedia = 0;
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
            $barang->jumlahTersedia -= $transaksi->jumlahPinjam;
            $barang->jumlahTotal -= $transaksi->jumlahPinjam;
            if ($barang->jumlahTersedia < 0) $barang->jumlahTersedia = 0;
            } elseif ($transaksi->jenisTransaksi === 'Keluar') {
            // Jika transaksi keluar dihapus, stok dikembalikan
            $barang->jumlahTersedia += $transaksi->jumlahPinjam;
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

        // Ambil kendaraan lama dan baru
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
