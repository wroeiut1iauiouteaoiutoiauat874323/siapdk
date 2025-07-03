<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKendaraan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_kendaraan';

    protected $fillable = [
        'idDataKendaraan',
        'idDataPegawai',
        'jenisTransaksi',
        'jumlahPinjam',
        'tanggalPinjam',
        'tanggalDikembalikan',
        'statusTransaksi',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(DataKendaraan::class, 'idDataKendaraan');
    }

    public function pegawai()
    {
        return $this->belongsTo(DataPegawai::class, 'idDataPegawai');
    }
}
