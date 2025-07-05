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

        $kendaraan = new DataKendaraan();
        $kendaraan->namaKendaraan = $request->namaKendaraan;
        $kendaraan->jenisKendaraan = $request->jenisKendaraan;
        $kendaraan->nomorPolisi = $request->nomorPolisi;
        $kendaraan->status = 'Tersedia';
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

        $kendaraan = DataKendaraan::findOrFail($id);
        $kendaraan->namaKendaraan = $request->namaKendaraan;
        $kendaraan->jenisKendaraan = $request->jenisKendaraan;
        $kendaraan->nomorPolisi = $request->nomorPolisi;
        $kendaraan->save();

        return redirect()->route('dashboard', ['menu' => 'kendaraan'])
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function data_kendaraan_destroy($id)
    {
        $kendaraan = DataKendaraan::findOrFail($id);
        $kendaraan->delete();

        // Set idKendaraan pada transaksi terkait menjadi null
        TransaksiKendaraan::where('idDataKendaraan', $id)->update(['idDataKendaraan' => null]);

        // Update urutan id kendaraan (reindex id)
        $kendaraans = DataKendaraan::orderBy('id')->get();
        $newId = 1;
        foreach ($kendaraans as $k) {
            if ($k->id != $newId) {
                // Update id only if different
                DataKendaraan::where('id', $k->id)->update(['id' => $newId]);
            }
            $newId++;
        }

        return redirect()->route('dashboard', ['menu' => 'kendaraan'])
            ->with('success', 'Data kendaraan berhasil dihapus.');
    }

    public function transaksi_barang_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pegawai' => 'required|string|max:255',
            'status_pegawai' => 'required|string|max:100',
            'nama_barang' => 'required|integer|exists:data_barang,id',
            'jenisTransaksi' => 'required|in:Masuk,Keluar',
            'jumlahPinjam' => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
        ], [
            'nama_pegawai.required' => 'Nama pegawai wajib diisi.',
            'status_pegawai.required' => 'Status pegawai wajib diisi.',
            'nama_barang.required' => 'Nama barang wajib dipilih.',
            'nama_barang.exists' => 'Barang yang dipilih tidak ditemukan.',
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
        $barang = DataBarang::findOrFail($request->nama_barang);

        // Pastikan field jumlahTersedia sudah ada di tabel data_barang
        if ($request->jenisTransaksi === 'Masuk') {
            // Jika input masuk lebih dari stok, error salah input
            if ($request->jumlahPinjam > $barang->jumlahTersedia) {
                return back()->withErrors(['jumlahPinjam' => 'Input jumlah masuk tidak boleh lebih dari stok yang tersedia.'])->withInput();
            }
            // Tambah jumlahTersedia
            $barang->jumlahTersedia += $request->jumlahPinjam;
        } elseif ($request->jenisTransaksi === 'Keluar') {
            // Kurangi jumlahTersedia, cek stok cukup
            if ($barang->jumlahTersedia < $request->jumlahPinjam) {
                return back()->withErrors(['jumlahPinjam' => 'Stok barang tidak mencukupi.'])->withInput();
            }
            $barang->jumlahTersedia -= $request->jumlahPinjam;
        }
        $barang->save();

        $statusTransaksi = $request->jenisTransaksi === 'Masuk' ? 'Dikembalikan' : 'Dipinjam';

        $transaksi = new TransaksiBarang();
        $transaksi->nama_pegawai = $request->nama_pegawai;
        $transaksi->status_pegawai = $request->status_pegawai;
        $transaksi->idDataBarang = $request->nama_barang;
        $transaksi->jenisTransaksi = $request->jenisTransaksi;
        $transaksi->jumlahPinjam = $request->jumlahPinjam;
        $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
        $transaksi->waktu = $request->waktu_transaksi;
        $transaksi->statusTransaksi = $statusTransaksi;
        $transaksi->save();

        return redirect()->route('dashboard', ['menu' => 'tbarang'])
            ->with('success', 'Transaksi barang berhasil disimpan.');
    }

    public function transaksi_barang_edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_pegawai' => 'required|string|max:255',
            'status_pegawai' => 'required|string|max:100',
            'nama_barang' => 'required|integer|exists:data_barang,id',
            'jenisTransaksi' => 'required|in:Masuk,Keluar',
            'jumlahPinjam' => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
        ], [
            'nama_pegawai.required' => 'Nama pegawai wajib diisi.',
            'status_pegawai.required' => 'Status pegawai wajib diisi.',
            'nama_barang.required' => 'Nama barang wajib dipilih.',
            'nama_barang.exists' => 'Barang yang dipilih tidak ditemukan.',
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

        // Ambil data barang lama dan transaksi lama
        $transaksi = TransaksiBarang::findOrFail($id);
        $barangLama = DataBarang::findOrFail($transaksi->idDataBarang);
        $barangBaru = DataBarang::findOrFail($request->nama_barang);

        // Kembalikan stok barang lama jika barang diganti atau jumlah berubah
        if ($transaksi->jenisTransaksi === 'Masuk') {
            $barangLama->jumlahTersedia -= $transaksi->jumlahPinjam;
        } elseif ($transaksi->jenisTransaksi === 'Keluar') {
            $barangLama->jumlahTersedia += $transaksi->jumlahPinjam;
        }
        $barangLama->save();

        // Update stok barang baru sesuai transaksi baru
        if ($request->jenisTransaksi === 'Masuk') {
            if ($request->jumlahPinjam > $barangBaru->jumlahTersedia) {
            return back()->withErrors(['jumlahPinjam' => 'Input jumlah masuk tidak boleh lebih dari stok yang tersedia.'])->withInput();
            }
            $barangBaru->jumlahTersedia += $request->jumlahPinjam;
        } elseif ($request->jenisTransaksi === 'Keluar') {
            if ($barangBaru->jumlahTersedia < $request->jumlahPinjam) {
            return back()->withErrors(['jumlahPinjam' => 'Stok barang tidak mencukupi.'])->withInput();
            }
            $barangBaru->jumlahTersedia -= $request->jumlahPinjam;
        }
        $barangBaru->save();

        $statusTransaksi = $request->jenisTransaksi === 'Masuk' ? 'Dikembalikan' : 'Dipinjam';

        $transaksi->nama_pegawai = $request->nama_pegawai;
        $transaksi->status_pegawai = $request->status_pegawai;
        $transaksi->idDataBarang = $request->nama_barang;
        $transaksi->jenisTransaksi = $request->jenisTransaksi;
        $transaksi->jumlahPinjam = $request->jumlahPinjam;
        $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
        $transaksi->waktu = $request->waktu_transaksi;
        $transaksi->statusTransaksi = $statusTransaksi;
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
            if ($barang->jumlahTersedia < 0) $barang->jumlahTersedia = 0;
            } elseif ($transaksi->jenisTransaksi === 'Keluar') {
            // Jika transaksi keluar dihapus, stok dikembalikan
            $barang->jumlahTersedia += $transaksi->jumlahPinjam;
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

        $statusTransaksi = $request->jenisTransaksi === 'masuk' ? 'Dikembalikan' : 'Dipinjam';
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

        $transaksi = new TransaksiKendaraan();
        $transaksi->nama_pegawai = $request->nama_pegawai;
        $transaksi->status_pegawai = $request->status_pegawai;
        $transaksi->idDataKendaraan = $kendaraan->id;
        $transaksi->jenisTransaksi = $request->jenisTransaksi;
        $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
        $transaksi->statusTransaksi = $statusTransaksi;
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
}
