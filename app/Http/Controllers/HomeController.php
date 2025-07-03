<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\user_manajemen;
use App\Helpers\jurnalhelper;
use App\Models\data_barang_now;
use App\Models\data_barang;
use App\Models\mutasi_now;
use App\Models\mutasi;
use App\Models\penghapusan_now;
use App\Models\penghapusan;

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

    public function hy(){
        return view('hy');
    }


    public function dashboard($menu)
    {
        session_start();
        if (!isset($_COOKIE['kukis'])) {
            return redirect()->route('login');
        }
        if (isset($_COOKIE['kukis'])) {
            $kukis = $_COOKIE['kukis'];
            if (jurnalhelper::cekkukis($kukis)) {
                $usernya = user_manajemen::where('kukis', $_COOKIE['kukis'])->first();
                if($menu == 'dashboard' ||  $menu == 'data_barang' || $menu == 'user_manajemen' || $menu == 'mutasi' || $menu == 'pengguna' || $menu == 'penghapusan' || $menu == 'export_to_excel'){

                    if($_COOKIE['posisi'] == 'pengguna'){

                        if($menu == 'dashboard'){
                            $datanya = user_manajemen::all();
                            jurnalhelper::resetedit();
                            jurnalhelper::resetsession();
                            return view('dashboard', ['posisi' => $_COOKIE['posisi'], 'nama' => $_COOKIE['nama'], 'nik' => $_COOKIE['nik'], 'area' => $_COOKIE['area'], 'waktu' => $_COOKIE['current_time_formatted'], 'tanggal' => $_COOKIE['tanggal']], compact('menu'));
                        }

                        if($menu == 'data_barang'){
                            jurnalhelper::resetsessionmutasi();
                            jurnalhelper::resetsessionpenghapusan();
                            if(!isset($_SESSION['data_barang_time'])){
                                return redirect()->route('dashboard', ['menu' => 'dashboard']);
                            }
                            if($_SESSION['data_barang_time'] == 'now'){
                                $datanya = data_barang_now::paginate(10);
                            } else {
                                if($_SESSION['data_barang_area'] == 'all' && $_SESSION['data_barang_bulan'] == 'all' && $_SESSION['data_barang_tahun'] == 'all'){
                                    $datanya = data_barang_now::paginate(10);
                                } else{
                                    if($_SESSION['data_barang_bulan'] == 'all'){
                                        if($_SESSION['data_barang_tahun'] == 'all'){
                                            $datanya = data_barang::paginate(10);
                                        } else {
                                            $datanya = data_barang::where('tahun', $_SESSION['data_barang_tahun'])->paginate(10);
                                        }
                                    } elseif($_SESSION['data_barang_tahun'] == 'all'){
                                        $datanya = data_barang::where('bulan', $_SESSION['data_barang_bulan'])->paginate(10);
                                    } else {
                                        $datanya = data_barang::where('bulan', $_SESSION['data_barang_bulan'])->where('tahun', $_SESSION['data_barang_tahun'])->paginate(10);
                                    }

                                }
                            }
                            $data_user = user_manajemen::all();
                            $data_barang_old = data_barang::all();
                            return view('dashboard', ['posisi' => $_COOKIE['posisi'], 'nama' => $_COOKIE['nama'], 'nik' => $_COOKIE['nik'], 'area' => $_COOKIE['area'], 'waktu' => $_COOKIE['current_time_formatted'], 'tanggal' => $_COOKIE['tanggal'], 'menu' => $menu, 'datanya' => $datanya], compact('data_barang_old', 'data_user'));
                        }

                        if($menu == 'mutasi'){
                            jurnalhelper::resetsessiondatabarang();
                            jurnalhelper::resetsessionpenghapusan();
                            if($_SESSION['mutasi_time'] == 'now'){
                                $datanya = mutasi_now::paginate(10);
                            } else {
                                if($_SESSION['mutasi_area'] == 'all' && $_SESSION['mutasi_bulan'] == 'all' && $_SESSION['mutasi_tahun'] == 'all'){
                                    $datanya = mutasi_now::paginate(10);
                                } else{
                                    if($_SESSION['mutasi_bulan'] == 'all'){
                                        if($_SESSION['mutasi_tahun'] == 'all'){
                                            $datanya = mutasi::paginate(10);
                                        } else {
                                            $datanya = mutasi::where('tahun', $_SESSION['mutasi_tahun'])->paginate(10);
                                        }
                                    } elseif($_SESSION['mutasi_tahun'] == 'all'){
                                        $datanya = mutasi::where('bulan', $_SESSION['mutasi_bulan'])->paginate(10);
                                    } else {
                                        $datanya = mutasi::where('bulan', $_SESSION['mutasi_bulan'])->where('tahun', $_SESSION['mutasi_tahun'])->paginate(10);
                                    }

                                }
                            }
                            $data_user = user_manajemen::all();
                            $mutasi_old = mutasi::all();
                            return view('dashboard', ['posisi' => $_COOKIE['posisi'], 'nama' => $_COOKIE['nama'], 'nik' => $_COOKIE['nik'], 'area' => $_COOKIE['area'], 'waktu' => $_COOKIE['current_time_formatted'], 'tanggal' => $_COOKIE['tanggal'], 'menu' => $menu, 'datanya' => $datanya], compact('mutasi_old', 'data_user'));
                        }

                        if($menu == 'penghapusan'){
                            jurnalhelper::resetsessionmutasi();
                            jurnalhelper::resetsessiondatabarang();
                            if($_SESSION['penghapusan_time'] == 'now'){
                                $datanya = penghapusan_now::paginate(10);
                            } else {

                                if($_SESSION['penghapusan_area'] == 'all' && $_SESSION['penghapusan_bulan'] == 'all' && $_SESSION['penghapusan_tahun'] == 'all'){
                                    $datanya = penghapusan_now::paginate(10);
                                } else{
                                    if($_SESSION['penghapusan_bulan'] == 'all'){
                                        if($_SESSION['penghapusan_tahun'] == 'all'){
                                            $datanya = penghapusan::paginate(10);
                                        } else {
                                            $datanya = penghapusan::where('tahun', $_SESSION['penghapusan_tahun'])->paginate(10);
                                        }
                                    } elseif($_SESSION['penghapusan_tahun'] == 'all'){
                                        $datanya = penghapusan::where('bulan', $_SESSION['penghapusan_bulan'])->paginate(10);
                                    } else {
                                        $datanya = penghapusan::where('bulan', $_SESSION['penghapusan_bulan'])->where('tahun', $_SESSION['penghapusan_tahun'])->paginate(10);
                                    }

                                }
                            }
                            $data_user = user_manajemen::all();
                            $penghapusan_old = penghapusan::all();
                            return view('dashboard', ['posisi' => $_COOKIE['posisi'], 'nama' => $_COOKIE['nama'], 'nik' => $_COOKIE['nik'], 'area' => $_COOKIE['area'], 'waktu' => $_COOKIE['current_time_formatted'], 'tanggal' => $_COOKIE ['tanggal'], 'menu' => $menu, 'datanya' => $datanya], compact('penghapusan_old', 'data_user'));
                        }

                        if($menu == 'export_to_excel'){
                            jurnalhelper::resetsession();
                            jurnalhelper::resetedit();
                            $data_user = user_manajemen::all();
                            $data_barang_old = data_barang::all();
                            $mutasi_old = mutasi::all();
                            $penghapusan_old = penghapusan::all();
                            return view('dashboard', ['posisi' => $_COOKIE['posisi'], 'nama' => $_COOKIE['nama'], 'nik' => $_COOKIE['nik'], 'area' => $_COOKIE['area'], 'waktu' => $_COOKIE['current_time_formatted'], 'tanggal' => $_COOKIE ['tanggal'], 'menu' => $menu], compact('data_user', 'penghapusan_old', 'mutasi_old', 'data_barang_old'));
                        }

                    } elseif($_COOKIE['posisi'] == 'admin'){

                        if($menu == 'dashboard'){
                            $datanya = user_manajemen::all();
                            jurnalhelper::resetsession();
                            jurnalhelper::resetedit();
                            return view('dashboard', ['posisi' => $_COOKIE['posisi'], 'nama' => $_COOKIE['nama'], 'nik' => $_COOKIE['nik'], 'area' => $_COOKIE['area'], 'waktu' => $_COOKIE['current_time_formatted'], 'tanggal' => $_COOKIE['tanggal'], 'menu' => $menu, 'datanya' => $datanya]);
                        }

                        if($menu == 'user_manajemen'){
                            $datanya = user_manajemen::paginate(10);
                            jurnalhelper::resetsession();
                            return view('dashboard', ['posisi' => $_COOKIE['posisi'], 'nama' => $_COOKIE['nama'], 'nik' => $_COOKIE['nik'], 'area' => $_COOKIE['area'], 'waktu' => $_COOKIE['current_time_formatted'], 'tanggal' => $_COOKIE['tanggal'], 'menu' => $menu, 'datanya' => $datanya]);
                        }

                        if ($menu == 'data_barang'){
                            jurnalhelper::resetsessionmutasi();
                            jurnalhelper::resetedit();
                            jurnalhelper::resetsessionpenghapusan();
                            if($_SESSION['data_barang_time'] == 'now'){
                                $datanya = data_barang_now::paginate(10);
                            } else {
                                if($_SESSION['data_barang_area'] == 'all' && $_SESSION['data_barang_bulan'] == 'all' && $_SESSION['data_barang_tahun'] == 'all'){
                                    $datanya = data_barang_now::paginate(10);
                                } else{
                                    if($_SESSION['data_barang_area'] == 'all'){
                                        if($_SESSION['data_barang_bulan'] == 'all'){
                                            $datanya = data_barang::where('tahun', $_SESSION['data_barang_tahun'])->paginate(10);
                                        } elseif($_SESSION['data_barang_tahun'] == 'all'){
                                            $datanya = data_barang::where('bulan', $_SESSION['data_barang_bulan'])->paginate(10);
                                        }elseif($_SESSION['data_barang_bulan'] != 'all' && $_SESSION['data_barang_tahun'] != 'all'){
                                            $datanya = data_barang::where('tahun', $_SESSION['data_barang_tahun'])->where('bulan', $_SESSION['data_barang_bulan'])->paginate(10);
                                        }
                                    }elseif($_SESSION['data_barang_bulan'] == 'all'){
                                        if($_SESSION['data_barang_area'] == 'all'){
                                            $datanya = data_barang::where('tahun', $_SESSION['data_barang_tahun'])->paginate(10);
                                        } elseif($_SESSION['data_barang_tahun'] == 'all'){
                                            $datanya = data_barang::where('area_user', $_SESSION['data_barang_area'])->paginate(10);
                                        }elseif($_SESSION['data_barang_area'] != 'all' && $_SESSION['data_barang_tahun'] != 'all'){
                                            $datanya = data_barang::where('tahun', $_SESSION['data_barang_tahun'])->where('area_user', $_SESSION['data_barang_area'])->paginate(10);
                                        }
                                    }elseif($_SESSION['data_barang_tahun'] == 'all'){
                                        if($_SESSION['data_barang_area'] == 'all'){
                                            $datanya = data_barang::where('bulan', $_SESSION['data_barang_bulan'])->paginate(10);
                                        } elseif($_SESSION['data_barang_bulan'] == 'all'){
                                            $datanya = data_barang::where('area_user', $_SESSION['data_barang_area'])->paginate(10);
                                        }elseif($_SESSION['data_barang_area'] != 'all' && $_SESSION['data_barang_bulan'] != 'all'){
                                            $datanya = data_barang::where('bulan', $_SESSION['data_barang_bulan'])->where('area_user', $_SESSION['data_barang_area'])->paginate(10);
                                        }
                                    }else{
                                        $datanya = data_barang::where('bulan', $_SESSION['data_barang_bulan'])->where('area_user', $_SESSION['data_barang_area'])->where('tahun', $_SESSION['data_barang_tahun'])->paginate(10);
                                    }
                                }
                            }
                            $data_user = user_manajemen::all();
                            $data_barang_old = data_barang::all();
                            return view('dashboard', ['posisi' => $_COOKIE['posisi'], 'nama' => $_COOKIE['nama'], 'nik' => $_COOKIE['nik'], 'area' => $_COOKIE['area'], 'waktu' => $_COOKIE['current_time_formatted'], 'tanggal' => $_COOKIE['tanggal'], 'menu' => $menu, 'datanya' => $datanya], compact('data_barang_old', 'data_user'));
                        }

                        if($menu == 'mutasi'){
                            jurnalhelper::resetsessiondatabarang();
                            jurnalhelper::resetedit();
                            jurnalhelper::resetsessionpenghapusan();
                            if($_SESSION['mutasi_time'] == 'now'){
                                $datanya = mutasi_now::paginate(10);
                            } else {
                                if($_SESSION['mutasi_area'] == 'all' && $_SESSION['mutasi_bulan'] == 'all' && $_SESSION['mutasi_tahun'] == 'all'){
                                    $datanya = mutasi_now::paginate(10);
                                } else{
                                    if($_SESSION['mutasi_area'] == 'all'){
                                        if($_SESSION['mutasi_bulan'] == 'all'){
                                            $datanya = mutasi::where('tahun', $_SESSION['mutasi_tahun'])->paginate(10);
                                        } elseif($_SESSION['mutasi_tahun'] == 'all'){
                                            $datanya = mutasi::where('bulan', $_SESSION['mutasi_bulan'])->paginate(10);
                                        }elseif($_SESSION['mutasi_bulan'] != 'all' && $_SESSION['mutasi_tahun'] != 'all'){
                                            $datanya = mutasi::where('tahun', $_SESSION['mutasi_tahun'])->where('bulan', $_SESSION['mutasi_bulan'])->paginate(10);
                                        }
                                    }elseif($_SESSION['mutasi_bulan'] == 'all'){
                                        if($_SESSION['mutasi_area'] == 'all'){
                                            $datanya = mutasi::where('tahun', $_SESSION['mutasi_tahun'])->paginate(10);
                                        } elseif($_SESSION['mutasi_tahun'] == 'all'){
                                            $datanya = mutasi::where('area_user', $_SESSION['mutasi_area'])->paginate(10);
                                        }elseif($_SESSION['mutasi_area'] != 'all' && $_SESSION['mutasi_tahun'] != 'all'){
                                            $datanya = mutasi::where('tahun', $_SESSION['mutasi_tahun'])->where('area_user', $_SESSION['mutasi_area'])->paginate(10);
                                        }
                                    }elseif($_SESSION['mutasi_tahun'] == 'all'){
                                        if($_SESSION['mutasi_area'] == 'all'){
                                            $datanya = mutasi::where('bulan', $_SESSION['mutasi_bulan'])->paginate(10);
                                        } elseif($_SESSION['mutasi_bulan'] == 'all'){
                                            $datanya = mutasi::where('area_user', $_SESSION['mutasi_area'])->paginate(10);
                                        }elseif($_SESSION['mutasi_area'] != 'all' && $_SESSION['mutasi_bulan'] != 'all'){
                                            $datanya = mutasi::where('bulan', $_SESSION['mutasi_bulan'])->where('area_user', $_SESSION['mutasi_area'])->paginate(10);
                                        }
                                    }else{
                                        $datanya = mutasi::where('bulan', $_SESSION['mutasi_bulan'])->where('area_user', $_SESSION['mutasi_area'])->where('tahun', $_SESSION['mutasi_tahun'])->paginate(10);
                                    }
                                }
                            }
                            $data_user = user_manajemen::all();
                            $mutasi_old = mutasi::all();
                            return view('dashboard', ['posisi' => $_COOKIE['posisi'], 'nama' => $_COOKIE['nama'], 'nik' => $_COOKIE['nik'], 'area' => $_COOKIE['area'], 'waktu' => $_COOKIE['current_time_formatted'], 'tanggal' => $_COOKIE['tanggal'], 'menu' => $menu, 'datanya' => $datanya], compact('mutasi_old', 'data_user'));
                        }

                        if($menu == 'penghapusan'){
                            jurnalhelper::resetsessionmutasi();
                            jurnalhelper::resetedit();
                            jurnalhelper::resetsessiondatabarang();
                            if($_SESSION['penghapusan_time'] == 'now'){
                                $datanya = penghapusan_now::paginate(10);
                            } else {
                                if($_SESSION['penghapusan_area'] == 'all' && $_SESSION['penghapusan_bulan'] == 'all' && $_SESSION['penghapusan_tahun'] == 'all'){
                                    $datanya = penghapusan_now::paginate(10);
                                } else{
                                    if($_SESSION['penghapusan_area'] == 'all'){
                                        if($_SESSION['penghapusan_bulan'] == 'all'){
                                            $datanya = penghapusan::where('tahun', $_SESSION['penghapusan_tahun'])->paginate(10);
                                        } elseif($_SESSION['penghapusan_tahun'] == 'all'){
                                            $datanya = penghapusan::where('bulan', $_SESSION['penghapusan_bulan'])->paginate(10);
                                        }elseif($_SESSION['penghapusan_bulan'] != 'all' && $_SESSION['penghapusan_tahun'] != 'all'){
                                            $datanya = penghapusan::where('tahun', $_SESSION['penghapusan_tahun'])->where('bulan', $_SESSION['penghapusan_bulan'])->paginate(10);
                                        }
                                    }elseif($_SESSION['penghapusan_bulan'] == 'all'){
                                        if($_SESSION['penghapusan_area'] == 'all'){
                                            $datanya = penghapusan::where('tahun', $_SESSION['penghapusan_tahun'])->paginate(10);
                                        } elseif($_SESSION['penghapusan_tahun'] == 'all'){
                                            $datanya = penghapusan::where('area_user', $_SESSION['penghapusan_area'])->paginate(10);
                                        }elseif($_SESSION['penghapusan_area'] != 'all' && $_SESSION['penghapusan_tahun'] != 'all'){
                                            $datanya = penghapusan::where('tahun', $_SESSION['penghapusan_tahun'])->where('area_user', $_SESSION['penghapusan_area'])->paginate(10);
                                        }
                                    }elseif($_SESSION['penghapusan_tahun'] == 'all'){
                                        if($_SESSION['penghapusan_area'] == 'all'){
                                            $datanya = penghapusan::where('bulan', $_SESSION['penghapusan_bulan'])->paginate(10);
                                        } elseif($_SESSION['penghapusan_bulan'] == 'all'){
                                            $datanya = penghapusan::where('area_user', $_SESSION['penghapusan_area'])->paginate(10);
                                        }elseif($_SESSION['penghapusan_area'] != 'all' && $_SESSION['penghapusan_bulan'] != 'all'){
                                            $datanya = penghapusan::where('bulan', $_SESSION['penghapusan_bulan'])->where('area_user', $_SESSION['penghapusan_area'])->paginate(10);
                                        }
                                    }else{
                                        $datanya = penghapusan::where('bulan', $_SESSION['penghapusan_bulan'])->where('area_user', $_SESSION['penghapusan_area'])->where('tahun', $_SESSION['penghapusan_tahun'])->paginate(10);
                                    }
                                }
                            }
                            $data_user = user_manajemen::all();
                            $penghapusan_old = penghapusan::all();
                            return view('dashboard', ['posisi' => $_COOKIE['posisi'], 'nama' => $_COOKIE['nama'], 'nik' => $_COOKIE['nik'], 'area' => $_COOKIE['area'], 'waktu' => $_COOKIE['current_time_formatted'], 'tanggal' => $_COOKIE ['tanggal'], 'menu' => $menu, 'datanya' => $datanya], compact('penghapusan_old', 'data_user'));
                        }

                        if($menu == 'export_to_excel'){
                            jurnalhelper::resetsession();
                            jurnalhelper::resetedit();
                            $data_user = user_manajemen::all();
                            $data_barang_old = data_barang::all();
                            $mutasi_old = mutasi::all();
                            $penghapusan_old = penghapusan::all();
                            return view('dashboard', ['posisi' => $_COOKIE['posisi'], 'nama' => $_COOKIE['nama'], 'nik' => $_COOKIE['nik'], 'area' => $_COOKIE['area'], 'waktu' => $_COOKIE['current_time_formatted'], 'tanggal' => $_COOKIE ['tanggal'], 'menu' => $menu], compact('data_user', 'penghapusan_old', 'mutasi_old', 'data_barang_old'));
                        }

                    }
                }
            }else{
                // return redirect()->route('dashboard', ['menu' => 'dashboard']);
            }
        return redirect()->route('index');
        }
    }
    public function logout()
    {
        $usernya = user_manajemen::where('kukis', $_COOKIE['kukis'])->first();
        if (isset($_COOKIE['kukis'])) {
            $kukis = $_COOKIE['kukis'];
            if (jurnalhelper::cekkukis($kukis)) {
                jurnalhelper::hapuskukis($usernya);
            }
        }
        return redirect()->route('index');
    }

}
