<?php

namespace App\Helpers;
use App\Models\user_manajemen;

class jurnalhelper
{
    public static function cekkukis($kukis)
    {
        return view('hy');
        $usernya = user_manajemen::where('kukis', $kukis)->first();
        if ($usernya) {
            return true;
        } else {
            return false;
        }

    }

    public static function buatkukis($usernya)
    {
        do {
                $kukis = bin2hex(random_bytes(32));
            } while (user_manajemen::where('kukis', $kukis)->exists());

            $usernya->kukis = $kukis;
            $usernya->save();

        $current_time = time();
        $current_time_gmt7 = time() + (7 * 3600);
        $current_time_formatted = date("H:i:s", $current_time_gmt7);
        $current_time_formatted_tanggal = date("d-m-Y", $current_time_gmt7);

        setcookie('current_time_formatted', $current_time_formatted, time() + 60 * 60 * 24, '/');
        setcookie('tanggal', $current_time_formatted_tanggal, time() + 60 * 60 * 24, '/');
        setcookie('kukis', $kukis, time() + 60 * 60 * 24, '/');
        setcookie('nama', $usernya->nama, time() + 60 * 60 * 24, '/');
        setcookie('nik', $usernya->nik, time() + 60 * 60 * 24, '/');
        setcookie('area', $usernya->area, time() + 60 * 60 * 24, '/');
        setcookie('posisi', $usernya->posisi, time() + 60 * 60 * 24, '/');


    }

    public static function hapuskukis($usernya)
    {
        $usernya->kukis = null;
        $usernya->save();

        setcookie('kukis', '', time() - 3600, '/');
        setcookie('posisi', '', time() - 3600, '/');
    }

    public static function resetsessiondatabarang(){
        $_SESSION['data_barang_time'] = 'now';
        $_SESSION['data_barang_area']= 'all';
        $_SESSION['data_barang_bulan']= 'all';
        $_SESSION['data_barang_tahun']= 'all';
    }
    public static function resetsession(){
        $_SESSION['data_barang_time'] = 'now';
        $_SESSION['data_barang_area']= 'all';
        $_SESSION['data_barang_bulan']= 'all';
        $_SESSION['data_barang_tahun']= 'all';
        $_SESSION['mutasi_time'] = 'now';
        $_SESSION['mutasi_area']= 'all';
        $_SESSION['mutasi_bulan']= 'all';
        $_SESSION['mutasi_tahun']= 'all';
        $_SESSION['penghapusan_time'] = 'now';
        $_SESSION['penghapusan_area']= 'all';
        $_SESSION['penghapusan_bulan']= 'all';
        $_SESSION['penghapusan_tahun']= 'all';
    }
    public static function resetsessionmutasi(){
        $_SESSION['mutasi_time'] = 'now';
        $_SESSION['mutasi_area']= 'all';
        $_SESSION['mutasi_bulan']= 'all';
        $_SESSION['mutasi_tahun']= 'all';
    }
    public static function resetsessionpenghapusan(){
        $_SESSION['penghapusan_time'] = 'now';
        $_SESSION['penghapusan_area']= 'all';
        $_SESSION['penghapusan_bulan']= 'all';
        $_SESSION['penghapusan_tahun']= 'all';
    }

    public static function resetedit(){
        $_SESSION['edit'] = 'tidak';
        session(['edit' => 'tidak']);
    }
}
