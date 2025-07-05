<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\DataPegawai;
use App\Helpers\jurnalhelper;
use App\Models\DataKendaraan;
use App\Models\DataBarang;
use App\Models\TransaksiBarang;
use App\Models\TransaksiKendaraan;

class HomeController extends Controller
{
    public function index()
    {
        if (isset($_COOKIE['kukis'])) {
            $kukis = $_COOKIE['kukis'];
            if (jurnalhelper::cekkukis($kukis)) {
                return redirect()->route('dashboard', ['menu' => 'dashboard']);
            }
        }
        return view('login');
    }

    public function search($menu)
    {
        if (!isset($_COOKIE['kukis'])) {
            return redirect()->route('login');
        }
        if (isset($_COOKIE['kukis'])) {
            $kukis = $_COOKIE['kukis'];
            if (jurnalhelper::cekkukis($kukis)) {
                $usernya = DataPegawai::where('kukis', $_COOKIE['kukis'])->first();
                if($menu == 'barang' ||  $menu == 'tbarang' || $menu == 'kendaraan' || $menu == 'tkendaraan' || $menu == 'profil' ){
                    if($menu == 'barang'){
                        $data_user = DataPegawai::all();
                        $data_barang = DataBarang::all();
                        $key = request()->input('search');
                        $datanya = DataBarang::where('namaBarang', 'like', '%' . $key . '%')->paginate(15);
                        return view('dashboard', [
                            'status' => $_COOKIE['status'],
                            'nama' => $_COOKIE['nama'],
                            'nip' => $_COOKIE['nip'],
                            'waktu' => $_COOKIE['current_time_formatted'],
                            'tanggal' => $_COOKIE['tanggal'],
                            'menu' => $menu,
                            'datanya' => $datanya
                        ], compact('data_user'));
                    }
                    if($menu == 'kendaraan'){
                        $data_user = DataPegawai::all();
                        $data_barang = DataBarang::all();
                        $key = request()->input('search');
                        $datanya = DataKendaraan::where('namaKendaraan', 'like', '%' . $key . '%')->paginate(15);
                        return view('dashboard', [
                            'status' => $_COOKIE['status'],
                            'nama' => $_COOKIE['nama'],
                            'nip' => $_COOKIE['nip'],
                            'waktu' => $_COOKIE['current_time_formatted'],
                            'tanggal' => $_COOKIE['tanggal'],
                            'menu' => $menu,
                            'datanya' => $datanya
                        ], compact('data_user'));
                    }
                    if($menu == 'tbarang'){
                        $data_user = DataPegawai::all();
                        $data_barang = DataBarang::all();
                        $key = request()->input('search');
                        $datanya = TransaksiBarang::with(['barang', 'pegawai'])
                            ->whereHas('barang', function ($query) use ($key) {
                                $query->where('namaBarang', 'like', "%{$key}%");
                            })
                            ->orWhere('nama_pegawai', 'like', "%{$key}%")
                            ->orderByDesc('tanggal_transaksi')
                            ->paginate(15);
                        return view('dashboard', [
                            'status' => $_COOKIE['status'],
                            'nama' => $_COOKIE['nama'],
                            'nip' => $_COOKIE['nip'],
                            'waktu' => $_COOKIE['current_time_formatted'],
                            'tanggal' => $_COOKIE['tanggal'],
                            'menu' => $menu,
                            'datanya' => $datanya
                        ], compact('data_user', 'data_barang'));
                    }
                    if($_COOKIE['status'] == 'admin'){

                    }
                }
                return redirect()->route('dashboard', ['menu' => 'barang']);
            }else{
            }
        return redirect()->route('index');
        }
    }

    public function dashboard($menu)
    {
        if (!isset($_COOKIE['kukis'])) {
            return redirect()->route('login');
        }
        if (isset($_COOKIE['kukis'])) {
            $kukis = $_COOKIE['kukis'];
            if (jurnalhelper::cekkukis($kukis)) {
                $usernya = DataPegawai::where('kukis', $_COOKIE['kukis'])->first();
                if($menu == 'barang' ||  $menu == 'tbarang' || $menu == 'kendaraan' || $menu == 'tkendaraan' || $menu == 'profil' ){
                    if($menu == 'barang'){
                        $data_user = DataPegawai::all();
                        $data_barang = DataBarang::all();
                        $datanya = DataBarang::paginate(15);
                        foreach ($datanya as $barang) {
                            $jumlahPinjaman = \App\Models\TransaksiBarang::where('idDataBarang', $barang->id)
                                ->where('statustransaksi', 'Dipinjam')
                                ->sum('jumlahPinjam');
                            $jumlahPengembalian = \App\Models\TransaksiBarang::where('idDataBarang', $barang->id)
                                ->where('statustransaksi', 'Dikembalikan')
                                ->sum('jumlahPinjam');
                            $barang->jumlahTersedia = $barang->jumlahTotal - $jumlahPinjaman + $jumlahPengembalian;
                            $barang->save();
                        }
                        return view('dashboard', [
                            'status' => $_COOKIE['status'],
                            'nama' => $_COOKIE['nama'],
                            'nip' => $_COOKIE['nip'],
                            'waktu' => $_COOKIE['current_time_formatted'],
                            'tanggal' => $_COOKIE['tanggal'],
                            'menu' => $menu,
                            'datanya' => $datanya
                        ], compact('data_user'));
                    }
                    if($menu == 'kendaraan'){
                        $data_user = DataPegawai::all();
                        $data_barang = DataBarang::all();
                        $datanya = DataKendaraan::paginate(15);
                        return view('dashboard', [
                            'status' => $_COOKIE['status'],
                            'nama' => $_COOKIE['nama'],
                            'nip' => $_COOKIE['nip'],
                            'waktu' => $_COOKIE['current_time_formatted'],
                            'tanggal' => $_COOKIE['tanggal'],
                            'menu' => $menu,
                            'datanya' => $datanya
                        ], compact('data_user'));
                    }
                    if($menu == 'tbarang'){
                        $data_user = DataPegawai::all();
                        $data_barang = DataBarang::all();
                        $datanya = TransaksiBarang::with(['barang', 'pegawai'])
                            ->orderByDesc('tanggal_transaksi')
                            ->orderByDesc('waktu')
                            ->paginate(15);
                        return view('dashboard', ['status' => $_COOKIE['status'], 'nama' => $_COOKIE['nama'], 'nip' => $_COOKIE['nip'], 'waktu' => $_COOKIE['current_time_formatted'], 'tanggal' => $_COOKIE['tanggal'], 'menu' => $menu, 'datanya' => $datanya], compact( 'data_user', 'data_barang'));
                    }
                    if($menu == 'tkendaraan'){
                        $data_user = DataPegawai::all();
                        $data_kendaraan = DataKendaraan::all();
                        $datanya = Transaksikendaraan::with(['kendaraan', 'pegawai'])
                            ->orderByDesc('tanggal_transaksi')
                            ->orderByDesc('waktu')
                            ->paginate(15);
                        return view('dashboard', [
                            'status' => $_COOKIE['status'],
                            'nama' => $_COOKIE['nama'],
                            'nip' => $_COOKIE['nip'],
                            'waktu' => $_COOKIE['current_time_formatted'],
                            'tanggal' => $_COOKIE['tanggal'],
                            'menu' => $menu,
                            'datanya' => $datanya
                        ], compact('data_user', 'data_kendaraan'));
                    }
                    if($_COOKIE['status'] == 'admin'){

                    }
                }
                return redirect()->route('dashboard', ['menu' => 'barang']);
            }else{
            }
        return redirect()->route('index');
        }
    }
    public function logout()
    {
        $usernya = DataPegawai::where('kukis', $_COOKIE['kukis'])->first();
        if (isset($_COOKIE['kukis'])) {
            $kukis = $_COOKIE['kukis'];
            if (jurnalhelper::cekkukis($kukis)) {
                jurnalhelper::hapuskukis($usernya);
            }
        }
        return redirect()->route('index');
    }

}
