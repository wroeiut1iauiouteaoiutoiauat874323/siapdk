<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiBarang extends Model
{
    use HasFactory;

    protected $table = 'transaksi_barang';

    protected $fillable = [
        'idDataBarang',
        'idDataPegawai',
        'jenisTransaksi',
        'jumlahPinjam',
        'tanggalPinjam',
        'tanggalDikembalikan',
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
