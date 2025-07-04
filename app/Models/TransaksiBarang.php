<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiBarang extends Model
{
    use HasFactory;

    protected $table = 'transaksi_barang';

    protected $fillable = [
        'tanggal_transaksi',
        'idDataBarang',
        'idDataPegawai',
        'jenisTransaksi',
        'jumlahPinjam',
        'statusTransaksi',
    ];

    public function barang()
    {
        return $this->belongsTo(DataBarang::class, 'idDataBarang');
    }

    public function pegawai()
    {
        return $this->belongsTo(DataPegawai::class, 'idDataPegawai');
    }
}
