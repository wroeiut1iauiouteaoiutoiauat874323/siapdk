<?php

namespace App\Exports;

use App\Models\mutasi_now;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MutasiNowExport implements FromCollection, WithHeadings
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = mutasi_now::select(
                'id',
                'asset',
                'kode_fa',
                'nama_barang',
                'outlet_actual',
                'type_barang',
                'location',
                'jabatan',
                'user_domain',
                'nik',
                'komputer_nama',
                'ip_address',
                'kondisi',
                'keterangan',
                'serial_number',
                'sophos',
                'landesk',
                'mutasi_asal',
                'mutasi_tujuan',
                'keterangan_mutasi',
                'area_user'
            )->get();

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
            'Asset',
            'Kode Fa Fams',
            'Nama Barang',
            'Outlet Asal',
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
            'Mutasi Asal',
            'Mutasi Tujuan',
            'Keterangan Mutasi',
            'Area User'
        ];
    }
}

