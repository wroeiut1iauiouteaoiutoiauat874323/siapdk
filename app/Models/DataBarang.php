<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBarang extends Model
{
    use HasFactory;

    protected $table = 'data_barang';

    protected $fillable = [
        'jenisBarangPersediaan',
        'namaBarang',
        'kode',
        'lokasi'
    ];

    public function transaksiBarang()
    {
        return $this->hasMany(TransaksiBarang::class, 'idDataBarang');
    }
}
