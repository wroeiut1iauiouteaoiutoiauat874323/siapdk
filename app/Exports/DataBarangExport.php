<?php

namespace App\Exports;

use App\Models\data_barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataBarangExport implements FromCollection, WithHeadings
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
        $query = data_barang::query();

        $query->when($this->bulan != 'all', function ($q) {
            return $q->where('bulan', $this->bulan);
        });

        $query->when($this->tahun != 'all', function ($q) {
            return $q->where('tahun', $this->tahun);
        });

        $query->when($this->area != 'all', function ($q) {
            return $q->where('area_user', $this->area);
        });

        $data = $query->get(['id',
                'tanggal_perolehan',
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
            'Area User'
        ];
    }
}
