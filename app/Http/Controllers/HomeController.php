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
                if($menu == 'barang' ||  $menu == 'tbarang' || $menu == 'kendaraan' || $menu == 'tkendaraan' || $menu == 'dpegawai' ){
                    if($menu == 'barang'){
                        $data_user = DataPegawai::all();
                        $data_barang = DataBarang::all();
                        $key = request()->input('search');
                        $datanya = DataBarang::where(function ($query) use ($key) {
                                $query->where('namaBarang', 'like', '%' . $key . '%')
                                      ->orWhere('kode', 'like', '%' . $key . '%')
                                      ->orWhere('jenisBarangPersediaan', 'like', '%' . $key . '%')
                                      ->orWhere('lokasi', 'like', '%' . $key . '%');
                            })
                            ->orderBy('namaBarang')
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
                    if($menu == 'kendaraan'){
                        $data_user = DataPegawai::all();
                        $data_barang = DataBarang::all();
                        $key = request()->input('search');
                        $datanya = DataKendaraan::where(function ($query) use ($key) {
                                $query->where('namaKendaraan', 'like', '%' . $key . '%')
                                      ->orWhere('kode', 'like', '%' . $key . '%')
                                      ->orWhere('jenisKendaraan', 'like', '%' . $key . '%')
                                      ->orWhere('nomorPolisi', 'like', '%' . $key . '%')
                                      ->orWhere('lokasi', 'like', '%' . $key . '%');
                            })
                            ->paginate(15);
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

                    if($_COOKIE['status'] == 'bukanumum'){
                        if($menu == 'tbarang'){
                            $data_user = DataPegawai::all();
                            $data_barang = DataBarang::all();
                            $key = request()->input('search');
                            $datanya = TransaksiBarang::where(function ($query) use ($key) {
                                    $query->whereHas('barang', function ($q) use ($key) {
                                        $q->where('namaBarang', 'like', '%' . $key . '%')
                                        ->orWhere('jenisBarangPersediaan', 'like', '%' . $key . '%')
                                        ->orWhere('kode', 'like', '%' . $key . '%');
                                    })
                                    ->orWhere('kode', 'like', '%' . $key . '%')
                                    ->orWhere('nama_pegawai', 'like', "%{$key}%");
                                })
                                ->where('nip', $_COOKIE['nip'])
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
                            ], compact('data_user', 'data_barang'));
                        }
                        if($menu == 'tkendaraan'){
                            $data_user = DataPegawai::all();
                            $data_kendaraan = DataKendaraan::all();
                            $key = request()->input('search');
                            $datanya = Transaksikendaraan::where(function ($query) use ($key) {
                                    $query->whereHas('kendaraan', function ($q) use ($key) {
                                        $q->where('namaKendaraan', 'like', '%' . $key . '%')
                                        ->orWhere('jenisKendaraan', 'like', '%' . $key . '%')
                                        ->orWhere('nomorPolisi', 'like', '%' . $key . '%')
                                        ->orWhere('kode', 'like', '%' . $key . '%')
                                        ->orWhere('lokasi', 'like', '%' . $key . '%');
                                    })
                                    ->orWhere('kode', 'like', '%' . $key . '%')
                                    ->orWhere('nama_pegawai', 'like', "%{$key}%");
                                })
                                ->where('nip', $_COOKIE['nip'])
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
                    }else{
                        if($menu == 'dpegawai'){
                            $key = request()->input('search');
                            $data_user = DataPegawai::where(function ($query) use ($key) {
                                    $query->where('namaPegawai', 'like', '%' . $key . '%')
                                          ->orWhere('nipPegawai', 'like', '%' . $key . '%')
                                          ->orWhere('status', 'like', '%' . $key . '%');
                                })
                                ->orderBy('namaPegawai')
                                ->paginate(15);
                            return view('dashboard', [
                                'status' => $_COOKIE['status'],
                                'nama' => $_COOKIE['nama'],
                                'nip' => $_COOKIE['nip'],
                                'waktu' => $_COOKIE['current_time_formatted'],
                                'tanggal' => $_COOKIE['tanggal'],
                                'menu' => $menu,
                                'datanya' => $data_user
                            ], compact('data_user'));
                        }
                        if($menu == 'tbarang'){
                            $data_user = DataPegawai::all();
                            $data_barang = DataBarang::all();
                            $key = request()->input('search');
                            $datanya = TransaksiBarang::where(function ($query) use ($key) {
                                    $query->whereHas('barang', function ($q) use ($key) {
                                        $q->where('namaBarang', 'like', '%' . $key . '%')
                                        ->orWhere('jenisBarangPersediaan', 'like', '%' . $key . '%')
                                        ->orWhere('kode', 'like', '%' . $key . '%');
                                    })
                                    ->orWhere('kode', 'like', '%' . $key . '%')
                                    ->orWhere('nama_pegawai', 'like', "%{$key}%");
                                })
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
                            ], compact('data_user', 'data_barang'));
                        }
                        if($menu == 'tkendaraan'){
                            $data_user = DataPegawai::all();
                            $data_kendaraan = DataKendaraan::all();
                            $key = request()->input('search');
                            $datanya = Transaksikendaraan::where(function ($query) use ($key) {
                                    $query->whereHas('kendaraan', function ($q) use ($key) {
                                        $q->where('namaKendaraan', 'like', '%' . $key . '%')
                                        ->orWhere('jenisKendaraan', 'like', '%' . $key . '%')
                                        ->orWhere('nomorPolisi', 'like', '%' . $key . '%')
                                        ->orWhere('kode', 'like', '%' . $key . '%')
                                        ->orWhere('lokasi', 'like', '%' . $key . '%');
                                    })
                                    ->orWhere('kode', 'like', '%' . $key . '%')
                                    ->orWhere('nama_pegawai', 'like', "%{$key}%");
                                })
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
                if($menu == 'barang' ||  $menu == 'tbarang' || $menu == 'kendaraan' || $menu == 'tkendaraan' || $menu == 'dpegawai' ){
                    if($menu == 'barang'){
                        $data_user = DataPegawai::all();
                        $data_barang = DataBarang::all();
                        $datanya = DataBarang::orderBy('namaBarang')->paginate(15);
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

                    if($_COOKIE['status'] == 'bukanumum'){
                        if($menu == 'tbarang'){
                               $data_user = DataPegawai::all();
                               $data_barang = DataBarang::orderBy('namaBarang')->get();
                               $datanya = TransaksiBarang::with(['barang', 'pegawai'])
                                    ->where('nip', $_COOKIE['nip'])
                                   ->orderByDesc('tanggal_transaksi')
                                   ->orderByDesc('waktu')
                                   ->paginate(15);
                               return view('dashboard', ['status' => $_COOKIE['status'], 'nama' => $_COOKIE['nama'], 'nip' => $_COOKIE['nip'], 'waktu' => $_COOKIE['current_time_formatted'], 'tanggal' => $_COOKIE['tanggal'], 'menu' => $menu, 'datanya' => $datanya], compact( 'data_user', 'data_barang'));
                           }
                           if($menu == 'tkendaraan'){
                               $data_user = DataPegawai::all();
                               $data_kendaraan = DataKendaraan::orderBy('namaKendaraan')->get();
                            $datanya = Transaksikendaraan::with(['kendaraan', 'pegawai'])
                                ->where('nip', $_COOKIE['nip'])
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
                    }else{
                        if($menu == 'dpegawai'){
                            $datanya = DataPegawai::all();
                            $data_user = DataPegawai::orderBy('namaPegawai')->paginate(15);
                            return view('dashboard', [
                                'status' => $_COOKIE['status'],
                                'nama' => $_COOKIE['nama'],
                                'nip' => $_COOKIE['nip'],
                                'waktu' => $_COOKIE['current_time_formatted'],
                                'tanggal' => $_COOKIE['tanggal'],
                                'menu' => $menu,
                                'datanya' => $data_user
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
