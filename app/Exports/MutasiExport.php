<?php

namespace App\Exports;

use App\Models\mutasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MutasiExport implements FromCollection, WithHeadings
{
    protected $bulan;
    protected $tahun;
    protected $area;

    public function __construct($bulan, $tahun, $area)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->area = $area;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = mutasi::query();

        $query->when($this->bulan != 'all', function ($q) {
            return $q->where('bulan', $this->bulan);
        });

        $query->when($this->tahun != 'all', function ($q) {
            return $q->where('tahun', $this->tahun);
        });

        $query->when($this->area != 'all', function ($q) {
            return $q->where('area_user', $this->area);
        });

        $data = $query->get([
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
            ]);

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
