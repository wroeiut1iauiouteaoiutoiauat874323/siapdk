<?php

namespace App\Http\Controllers;

use App\Exports\DataBarangNowExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\user_manajemen;
use App\Models\area_user_aplikasi;
use App\Helpers\jurnalhelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataBarangExport;
use App\Exports\MutasiExport;
use App\Exports\MutasiNowExport;
use App\Exports\PenghapusanExport;
use App\Exports\PenghapusanNowExport;
use App\Models\data_barang_now;
use App\Models\data_barang;
use App\Models\penghapusan_now;
use App\Models\penghapusan;
use App\Models\mutasi_now;
use App\Models\mutasi;
use Carbon\Carbon;

class prosesController extends Controller
{

    // Start Proses Akun
    public function login(Request $request)
    {
        session_start();
        $input = $request->only('nik', 'password');
        $validator = Validator::make($input, [
            'nik' => 'required|numeric',
            'password' => 'required'
        ], [
            'nik.required' => 'Nomor Induk Kependudukan (NIK) diperlukan.',
            'nik.numeric' => 'Nomor Induk Kependudukan (NIK) harus berupa angka.',
            'password.required' => 'Kata sandi diperlukan.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $usernya = user_manajemen::where('nik', $request->nik)->first();

        if (!$usernya) {
            return back()->withErrors(['nik' => 'NIK tidak ditemukan.'])->withInput();
        }

        if (!$usernya || !Hash::check($request->password, $usernya->password)) {
            return back()->withErrors(['password' => 'NIK atau kata sandi salah.'])->withInput();
        }

        $data_area = area_user_aplikasi::where('nik', $request->nik)->get();
        $_SESSION['nik_diambil'] = $request->nik;
        return view('area', ['data_area' => $data_area]);
    }

    public function login_lempar(Request $request)
    {
        session_start();
        $input = $request->only('area');
        $validator = Validator::make($input, [
            'area' => 'required|string'
        ], [
            'area.required' => 'Area diperlukan.',
            'area.numeric' => 'Area harus berupa tulisan.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $usernya = user_manajemen::where('nik', $_SESSION['nik_diambil'])->where('area', $request->area)->first();
        jurnalhelper::buatkukis($usernya);

        return redirect()->route('dashboard', ['menu' => 'dashboard']);
    }

    // End Proses Akun


    // Start Admin
    // Start Proses User Manajemen Admin
    public function admin_usermanajemen_store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'nik' => 'required|numeric',
            'posisi' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'daerah' => 'required|string|max:255',
        ]);

        $user = new user_manajemen;

        $user->nama = $request->nama;
        $user->nik = $request->nik;
        $user->password = bcrypt($request->password);
        $user->posisi = $request->posisi;
        $user->area = $request->area . " " . $request->daerah;
        $user->save();

        $area_user_aplikasi = new area_user_aplikasi;
        $area_user_aplikasi->nik = $request->nik;
        $area_user_aplikasi->area_user = $request->area . " " . $request->daerah;
        $area_user_aplikasi->save();

        return redirect()->route('dashboard', ['menu' => 'user_manajemen']);
    }

    public function admin_usermanajemen_edit($id)
    {
        session_start();
        $useredit = user_manajemen::findOrFail($id);
        $_SESSION['edit'] = 'iya';
        session(['useredit' => $useredit]);

        $words = explode(' ', $useredit->area);

        $wordsExceptFirst = array_slice($words, 1);

        $katabaru = array();
        foreach ($wordsExceptFirst as $word) {
            array_push($katabaru, $word);

        }

        $katapertama = $words[0];
        $katabaru = implode(" ", $katabaru);
        session(['area' => $katabaru]);
        session(['katapertama' => $katapertama]);

        return redirect()->route('dashboard', ['menu' => 'user_manajemen']);
    }

    public function admin_usermanajemen_update(Request $request, $id)
    {
        session_start();
            $_SESSION['edit'] = 'tidak';
        if(isset($request->tomboledit)){

            $user = user_manajemen::findOrFail($id);

            $user->nama = $request->nama;
            $user->nik = $request->nik;
            if($request->password != null){
                $user->password = bcrypt($request->password);
            }
            $user->posisi = $request->posisi;
            $user->area = $request->area." ".$request->daerah;
            $user->save();

            return redirect()->route('dashboard', ['menu' => 'user_manajemen']);
        }

        if(isset($request->tombolbatal)){
            $_SESSION['edit'] = 'tidak';
            return redirect()->route('dashboard', ['menu' => 'user_manajemen']);
        }
    }

    public function admin_usermanajemen_destroy($id)
    {
        $user = user_manajemen::findOrFail($id);
        $user->delete();
        return redirect()->route('dashboard', ['menu' => 'user_manajemen']);
    }
    // End User Manajemen Admin


    // Start Export to Excel Admin
    public function export_data_barang(Request $request)
    {
        session_start();
        if($request->area == 'all' && $request->bulan == 'all' && $request->tahun == 'all'){
            return Excel::download(new DataBarangNowExport, 'Data Barang Sekarang.xlsx');
        } else {
            if($_COOKIE['posisi'] == 'pengguna'){
                $areanya = $_COOKIE['area'];
                return Excel::download(new DataBarangExport($request->bulan, $request->tahun, $areanya), 'Data Barang ' . $areanya . " ". $request->bulan . " " . $request->tahun .'.xlsx');
            }
            return Excel::download(new DataBarangExport($request->bulan, $request->tahun, $request->area), 'Data Barang ' . $request->area . " ". $request->bulan . " " . $request->tahun .'.xlsx');
        }
    }

    public function export_mutasi(Request $request)
    {
        session_start();
        if($request->area == 'all' && $request->bulan == 'all' && $request->tahun == 'all'){
            return Excel::download(new MutasiNowExport, 'Mutasi Sekarang.xlsx');
        } else {
            if($_COOKIE['posisi'] == 'pengguna'){
                $areanya = $_COOKIE['area'];
                return Excel::download(new MutasiExport($request->bulan, $request->tahun, $areanya), 'Data Barang ' . $areanya . " ". $request->bulan . " " . $request->tahun .'.xlsx');
            }
            return Excel::download(new MutasiExport($request->bulan, $request->tahun, $request->area), 'Mutasi ' . $request->area . " ". $request->bulan . " " . $request->tahun .'.xlsx');
        }
    }
    public function export_penghapusan(Request $request)
    {
        session_start();
        if($request->area == 'all' && $request->bulan == 'all' && $request->tahun == 'all'){
            return Excel::download(new PenghapusanNowExport, 'Penghapusan Sekarang.xlsx');
        } else {
            if($_COOKIE['posisi'] == 'pengguna'){
                $areanya = $_COOKIE['area'];
                return Excel::download(new PenghapusanExport($request->bulan, $request->tahun, $areanya), 'Data Barang ' . $areanya . " ". $request->bulan . " " . $request->tahun .'.xlsx');
            }
            return Excel::download(new PenghapusanExport($request->bulan, $request->tahun, $request->area), 'Penghapusan ' . $request->area . " ". $request->bulan . " " . $request->tahun .'.xlsx');
        }
    }
    // End Export To Excel Admin
    // End Admin

    // Start Campur
    public function data_barang_pilihan(Request $request)
    {
        session_start();
        $_SESSION['data_barang_time'] = 'old';
        $_SESSION['data_barang_area']= $request->area;
        $_SESSION['data_barang_bulan']= $request->bulan;
        $_SESSION['data_barang_tahun']= $request->tahun;
        return redirect()->route('dashboard', ['menu' => 'data_barang']);
    }
    public function mutasi_pilihan(Request $request)
    {
        session_start();
        $_SESSION['mutasi_time'] = 'old';
        $_SESSION['mutasi_area']= $request->area;
        $_SESSION['mutasi_bulan']= $request->bulan;
        $_SESSION['mutasi_tahun']= $request->tahun;
        return redirect()->route('dashboard', ['menu' => 'mutasi']);
    }

    public function penghapusan_pilihan(Request $request)
    {
        session_start();
        $_SESSION['penghapusan_time'] = 'old';
        $_SESSION['penghapusan_area']= $request->area;
        $_SESSION['penghapusan_bulan']= $request->bulan;
        $_SESSION['penghapusan_tahun']= $request->tahun;
        return redirect()->route('dashboard', ['menu' => 'penghapusan']);
    }

    // End Campur

    // Start Pengguna
    // Start Data Barang Pengguna
    public function pengguna_databarang_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_perolehan' => 'required|date',
            'nama_barang_asset' => 'required|string|max:255',
            'kode_fa_fams' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'outlet_pencatatan' => 'required|string|max:255',
            'outlet_actual' => 'required|string|max:255',
            'type_barang' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nama_user' => 'required|string|max:255',
            'nik' => 'required|numeric',
            'nama_komputer' => 'required|string|max:255',
            'ip_address' => 'required|string|max:255',
            'kondisi' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'shopos' => 'required|string|max:255',
            'landesk' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data_barang_now = new data_barang_now;
        $data_barang_now->tanggal_perolehan = $request->tanggal_perolehan;
        $data_barang_now->asset = $request->nama_barang_asset;
        $data_barang_now->kode_fa_fams = $request->kode_fa_fams;
        $data_barang_now->nama_barang = $request->nama_barang;
        $data_barang_now->outlet_pencatatan = $request->outlet_pencatatan;
        $data_barang_now->outlet_actual = $request->outlet_actual;
        $data_barang_now->type_barang = $request->type_barang;
        $data_barang_now->location = $request->location;
        $data_barang_now->jabatan = $request->jabatan;
        $data_barang_now->nama_user = $request->nama_user;
        $data_barang_now->nik = $request->nik;
        $data_barang_now->komputer_nama = $request->nama_komputer;
        $data_barang_now->ip_address = $request->ip_address;
        $data_barang_now->kondisi = $request->kondisi;
        $data_barang_now->keterangan = $request->keterangan;
        $data_barang_now->serial_number = $request->serial_number;
        $data_barang_now->sophos = $request->shopos;
        $data_barang_now->landesk = $request->landesk;
        $data_barang_now->save();


        jurnalhelper::resetedit();
        return redirect()->route('dashboard', ['menu' => 'data_barang']);
    }

    public function pengguna_databarang_edit($id)
    {
        session_start();
        $useredit = data_barang_now::findOrFail($id);
        session(['edit' => 'iya']);
        session(['useredit' => $useredit]);

        return redirect()->route('dashboard', ['menu' => 'data_barang']);
    }

    public function pengguna_databarang_update(Request $request, $id)
    {

        if(isset($request->submittombol)){
            $data_barang_now = data_barang_now::findOrFail($id);
            $data_barang_now->tanggal_perolehan = $request->tanggal_perolehan;
            $data_barang_now->asset = $request->nama_barang_asset;
            $data_barang_now->kode_fa_fams = $request->kode_fa_fams;
            $data_barang_now->nama_barang = $request->nama_barang;
            $data_barang_now->outlet_pencatatan = $request->outlet_pencatatan;
            $data_barang_now->outlet_actual = $request->outlet_actual;
            $data_barang_now->type_barang = $request->type_barang;
            $data_barang_now->location = $request->location;
            $data_barang_now->jabatan = $request->jabatan;
            $data_barang_now->nama_user = $request->nama_user;
            $data_barang_now->nik = $request->nik;
            $data_barang_now->komputer_nama = $request->nama_komputer;
            $data_barang_now->ip_address = $request->ip_address;
            $data_barang_now->kondisi = $request->kondisi;
            $data_barang_now->keterangan = $request->keterangan;
            $data_barang_now->serial_number = $request->serial_number;
            $data_barang_now->sophos = $request->sophos;
            $data_barang_now->landesk = $request->landesk;

            $data_barang_now->save();

            jurnalhelper::resetedit();
            return redirect()->route('dashboard', ['menu' => 'data_barang']);
        }

        if(isset($request->bataltombol)){
            jurnalhelper::resetedit();
            return redirect()->route('dashboard', ['menu' => 'data_barang']);
        }

    }

    public function pengguna_databarang_destroy($id){
        $user = data_barang_now::findOrFail($id);

        $user->delete();

        jurnalhelper::resetedit();
        return redirect()->route('dashboard', ['menu' => 'data_barang']);
    }
    // End Data Barang Pengguna
    // Start Mutasi Pengguna
    public function pengguna_mutasi_store(Request $request)
    {

        $mutasi_now = new mutasi_now;
        $mutasi_now->tanggal_perolehan = $request->tanggal_perolehan;
        $mutasi_now->asset = $request->nama_barang_asset;
        $mutasi_now->kode_fa_fams = $request->kode_fa_fams;
        $mutasi_now->nama_barang = $request->nama_barang;
        $mutasi_now->outlet_pencatatan = $request->outlet_pencatatan;
        $mutasi_now->outlet_actual = $request->outlet_actual;
        $mutasi_now->type_barang = $request->type_barang;
        $mutasi_now->location = $request->location;
        $mutasi_now->jabatan = $request->jabatan;
        $mutasi_now->nama_user = $request->nama_user;
        $mutasi_now->nik = $request->nik;
        $mutasi_now->komputer_nama = $request->nama_komputer;
        $mutasi_now->ip_address = $request->ip_address;
        $mutasi_now->kondisi = $request->kondisi;
        $mutasi_now->keterangan = $request->keterangan;
        $mutasi_now->serial_number = $request->serial_number;
        $mutasi_now->sophos = $request->shopos;
        $mutasi_now->landesk = $request->landesk;
        $mutasi_now->save();


        jurnalhelper::resetedit();
        return redirect()->route('dashboard', ['menu' => 'mutasi']);
    }

    public function pengguna_mutasi_edit($id)
    {
        session_start();
        $useredit = mutasi_now::findOrFail($id);
        session(['edit' => 'iya']);
        session(['useredit' => $useredit]);

        return redirect()->route('dashboard', ['menu' => 'mutasi']);
    }

    public function pengguna_mutasi_update(Request $request, $id)
    {

        $mutasi_now = mutasi_now::findOrFail($id);
        $mutasi_now->tanggal_perolehan = $request->tanggal_perolehan;
        $mutasi_now->asset = $request->nama_barang_asset;
        $mutasi_now->kode_fa_fams = $request->kode_fa_fams;
        $mutasi_now->nama_barang = $request->nama_barang;
        $mutasi_now->outlet_pencatatan = $request->outlet_pencatatan;
        $mutasi_now->outlet_actual = $request->outlet_actual;
        $mutasi_now->type_barang = $request->type_barang;
        $mutasi_now->location = $request->location;
        $mutasi_now->jabatan = $request->jabatan;
        $mutasi_now->nama_user = $request->nama_user;
        $mutasi_now->nik = $request->nik;
        $mutasi_now->komputer_nama = $request->nama_komputer;
        $mutasi_now->ip_address = $request->ip_address;
        $mutasi_now->kondisi = $request->kondisi;
        $mutasi_now->keterangan = $request->keterangan;
        $mutasi_now->serial_number = $request->serial_number;
        $mutasi_now->sophos = $request->sophos;
        $mutasi_now->landesk = $request->landesk;

        $mutasi_now->save();

        jurnalhelper::resetedit();

        return redirect()->route('dashboard', ['menu' => 'mutasi']);
    }

    public function pengguna_mutasi_destroy($id){
        $user = mutasi_now::findOrFail($id);

        $user->delete();

        jurnalhelper::resetedit();
        return redirect()->route('dashboard', ['menu' => 'mutasi']);
    }
    // End Mutasi Pengguna

    // Start Penghapusan Pengguna

    public function pengguna_penghapusan_store(Request $request)
    {
        $penghapusan_now = new penghapusan_now;
        $penghapusan_now->tanggal_perolehan = $request->tanggal_perolehan;
        $penghapusan_now->asset = $request->nama_barang_asset;
        $penghapusan_now->kode_fa_fams = $request->kode_fa_fams;
        $penghapusan_now->nama_barang = $request->nama_barang;
        $penghapusan_now->outlet_pencatatan = $request->outlet_pencatatan;
        $penghapusan_now->outlet_actual = $request->outlet_actual;
        $penghapusan_now->type_barang = $request->type_barang;
        $penghapusan_now->location = $request->location;
        $penghapusan_now->jabatan = $request->jabatan;
        $penghapusan_now->nama_user = $request->nama_user;
        $penghapusan_now->nik = $request->nik;
        $penghapusan_now->komputer_nama = $request->nama_komputer;
        $penghapusan_now->ip_address = $request->ip_address;
        $penghapusan_now->kondisi = $request->kondisi;
        $penghapusan_now->keterangan = $request->keterangan;
        $penghapusan_now->serial_number = $request->serial_number;
        $penghapusan_now->sophos = $request->shopos;
        $penghapusan_now->landesk = $request->landesk;
        $penghapusan_now->save();


        return redirect()->route('dashboard', ['menu' => 'penghapusan']);
    }

    public function pengguna_penghapusan_edit($id)
    {
        session_start();
        $useredit = penghapusan_now::findOrFail($id);
        session(['edit' => 'iya']);
        session(['useredit' => $useredit]);

        return redirect()->route('dashboard', ['menu' => 'penghapusan']);
    }

    public function pengguna_penghapusan_update(Request $request, $id)
    {

        $penghapusan_now = penghapusan_now::findOrFail($id);
        $penghapusan_now->tanggal_perolehan = $request->tanggal_perolehan;
        $penghapusan_now->asset = $request->nama_barang_asset;
        $penghapusan_now->kode_fa_fams = $request->kode_fa_fams;
        $penghapusan_now->nama_barang = $request->nama_barang;
        $penghapusan_now->outlet_pencatatan = $request->outlet_pencatatan;
        $penghapusan_now->outlet_actual = $request->outlet_actual;
        $penghapusan_now->type_barang = $request->type_barang;
        $penghapusan_now->location = $request->location;
        $penghapusan_now->jabatan = $request->jabatan;
        $penghapusan_now->nama_user = $request->nama_user;
        $penghapusan_now->nik = $request->nik;
        $penghapusan_now->komputer_nama = $request->nama_komputer;
        $penghapusan_now->ip_address = $request->ip_address;
        $penghapusan_now->kondisi = $request->kondisi;
        $penghapusan_now->keterangan = $request->keterangan;
        $penghapusan_now->serial_number = $request->serial_number;
        $penghapusan_now->sophos = $request->sophos;
        $penghapusan_now->landesk = $request->landesk;

        $penghapusan_now->save();

        jurnalhelper::resetedit();

        return redirect()->route('dashboard', ['menu' => 'penghapusan']);
    }

    public function pengguna_penghapusan_destroy($id){
        $user = penghapusan_now::findOrFail($id);

        $user->delete();

        return redirect()->route('dashboard', ['menu' => 'penghapusan']);
    }

    // End Penghapusan Pengguna
    // End Pengguna

}
