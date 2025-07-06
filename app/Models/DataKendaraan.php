<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKendaraan extends Model
{
    use HasFactory;

    protected $table = 'data_kendaraan';

    protected $fillable = [
        'jenisKendaraan',
        'namaKendaraan',
        'nomorPolisi',
        'status',
        'kode',
        'lokasi'
    ];

    public function transaksiKendaraan()
    {
        return $this->hasMany(TransaksiKendaraan::class, 'idDataKendaraan');
    }
}
