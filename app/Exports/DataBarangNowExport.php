<?php

namespace App\Exports;

use App\Models\data_barang_now;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataBarangNowExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return data_barang_now::all();
        $data = data_barang_now::select('id', 'tanggal_perolehan',
        'asset',
        'kode_fa_fams',
        'nama_barang',
        'outlet_pencatatan',
        'outlet_actual',
        'type_barang',
        'location',
        'jabatan',
        'nama_user',
        'nik',
        'komputer_nama',
        'ip_address',
        'kondisi',
        'keterangan',
        'serial_number',
        'sophos',
        'landesk',
        'capex_or_selisih',
        'area_user')->get();

        $nomorUrut = 0;
        $data->transform(function ($item) use (&$nomorUrut) {
            $nomorUrut++;
            $item->id = $nomorUrut;
            return $item;
        });

        return $data;
    }


    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal Perolehan',
            'Asset',
            'Kode Fa Fams',
            'Nama Barang',
            'Outlet Pencatatan',
            'Outlet Actual',
            'Type Barang',
            'Location',
            'Jabatan',
            'Nama User',
            'Nik',
            'Komputer Nama',
            'IP Address',
            'Kondisi',
            'Keterangan',
            'Serial Number',
            'Sophos',
            'Landesk',
            'Capex atau Selisih',
            'Area User	'
        ];



    }

}
