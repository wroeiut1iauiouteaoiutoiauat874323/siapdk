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
            'jumlahTersedia' => 'required|integer|min:1',
        ], [
            'namaBarang.required' => 'Nama barang wajib diisi.',
            'jenisBarangPersediaan.required' => 'Kategori wajib diisi.',
            'jumlahTotal.required' => 'Jumlah total wajib diisi.',
            'jumlahTotal.integer' => 'Jumlah total harus berupa angka.',
            'jumlahTotal.min' => 'Jumlah total minimal 1.',
            'jumlahTersedia.required' => 'Jumlah tersedia wajib diisi.',
            'jumlahTersedia.integer' => 'Jumlah tersedia harus berupa angka.',
            'jumlahTersedia.min' => 'Jumlah tersedia minimal 1.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $barang = new DataBarang();
        $barang->namaBarang = $request->namaBarang;
        $barang->jenisBarangPersediaan = $request->jenisBarangPersediaan;
        $barang->jumlahTotal = $request->jumlahTotal;
        $barang->jumlahTersedia = $request->jumlahTersedia;
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
            'jumlahTersedia' => 'required|integer|min:1',
        ], [
            'namaBarang.required' => 'Nama barang wajib diisi.',
            'jenisBarangPersediaan.required' => 'Kategori wajib diisi.',
            'jumlahTotal.required' => 'Jumlah total wajib diisi.',
            'jumlahTotal.integer' => 'Jumlah total harus berupa angka.',
            'jumlahTotal.min' => 'Jumlah total minimal 1.',
            'jumlahTersedia.required' => 'Jumlah tersedia wajib diisi.',
            'jumlahTersedia.integer' => 'Jumlah tersedia harus berupa angka.',
            'jumlahTersedia.min' => 'Jumlah tersedia minimal 1.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $barang = DataBarang::findOrFail($id);
        $barang->namaBarang = $request->namaBarang;
        $barang->jenisBarangPersediaan = $request->jenisBarangPersediaan;
        $barang->jumlahTotal = $request->jumlahTotal;
        $barang->jumlahTersedia = $request->jumlahTersedia;
        $barang->save();

        return redirect()->route('dashboard', ['menu' => 'barang'])
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    public function data_barang_destroy($id)
    {
        $barang = DataBarang::findOrFail($id);
        $barang->delete();

        // Set idBarang pada transaksi terkait menjadi null
        TransaksiBarang::where('idDataBarang', $id)->update(['idDataBarang' => null]);

        // Update urutan id barang (reindex id)
        $barangs = DataBarang::orderBy('id')->get();
        $newId = 1;
        foreach ($barangs as $b) {
            if ($b->id != $newId) {
            // Update id only if different
            DataBarang::where('id', $b->id)->update(['id' => $newId]);
            }
            $newId++;
        }

        return redirect()->route('dashboard', ['menu' => 'barang'])
            ->with('success', 'Data barang berhasil dihapus.');
    }

    public function data_kendaraan_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'namaKendaraan' => 'required|string|max:255',
            'jenisKendaraan' => 'required|string|max:100',
            'nomorPolisi' => 'required|string|max:20',
        ], [
            'namaKendaraan.required' => 'Nama kendaraan wajib diisi.',
            'jenisKendaraan.required' => 'Jenis kendaraan wajib diisi.',
            'nomorPolisi.required' => 'Nomor polisi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $kendaraan = new DataKendaraan();
        $kendaraan->namaKendaraan = $request->namaKendaraan;
        $kendaraan->jenisKendaraan = $request->jenisKendaraan;
        $kendaraan->nomorPolisi = $request->nomorPolisi;
        $kendaraan->save();

        return redirect()->route('dashboard', ['menu' => 'kendaraan'])
            ->with('success', 'Data kendaraan berhasil disimpan.');
    }

    public function data_kendaraan_edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'namaKendaraan' => 'required|string|max:255',
            'jenisKendaraan' => 'required|string|max:100',
            'nomorPolisi' => 'required|string|max:20',
            'statusKendaraan' => 'required|string|max:50',
        ], [
            'namaKendaraan.required' => 'Nama kendaraan wajib diisi.',
            'jenisKendaraan.required' => 'Jenis kendaraan wajib diisi.',
            'nomorPolisi.required' => 'Nomor polisi wajib diisi.',
            'statusKendaraan.required' => 'Status wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $kendaraan = DataKendaraan::findOrFail($id);
        $kendaraan->namaKendaraan = $request->namaKendaraan;
        $kendaraan->jenisKendaraan = $request->jenisKendaraan;
        $kendaraan->nomorPolisi = $request->nomorPolisi;
        $kendaraan->status = $request->statusKendaraan;
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
            'jenisTransaksi' => 'required|in:masuk,keluar',
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

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $statusTransaksi = $request->jenisTransaksi === 'masuk' ? 'Dikembalikan' : 'Dipinjam';

        $transaksi = new TransaksiBarang();
        $transaksi->nama_pegawai = $request->nama_pegawai;
        $transaksi->status_pegawai = $request->status_pegawai;
        $transaksi->idDataBarang = $request->nama_barang;
        $transaksi->jenisTransaksi = $request->jenisTransaksi;
        $transaksi->jumlahPinjam = $request->jumlahPinjam;
        $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
        $transaksi->statusTransaksi = $statusTransaksi;
        $transaksi->save();

        return redirect()->route('dashboard', ['menu' => 'tbarang'])
            ->with('success', 'Transaksi barang berhasil disimpan.');
    }
}
