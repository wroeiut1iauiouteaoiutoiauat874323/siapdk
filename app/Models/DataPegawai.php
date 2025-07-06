<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPegawai extends Model
{
    use HasFactory;


    protected $table = 'data_pegawai';

    protected $fillable = [
        'namaPegawai',
        'nipPegawai',
        'password',
        'status',
        'jabatan', // Tambahkan kolom jabatan
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi ke transaksi barang
    public function transaksiBarang()
    {
        return $this->hasMany(TransaksiBarang::class, 'idDataPegawai');
    }

    // Relasi ke transaksi kendaraan
    public function transaksiKendaraan()
    {
        return $this->hasMany(TransaksiKendaraan::class, 'idDataPegawai');
    }
}
