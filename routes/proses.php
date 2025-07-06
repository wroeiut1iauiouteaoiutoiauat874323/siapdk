<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\prosesController;


Route::post('login', [prosesController::class, 'login'])->name('login');
Route::post('login/lempar', [prosesController::class, 'login_lempar'])->name('login.lempar');

// Start Transaksi Barang
Route::post('/transaksi_barang/store', [prosesController::class, 'transaksi_barang_store'])->name('transaksi_barang.store');
Route::post('/transaksi_barang/{id}/edit', [prosesController::class, 'transaksi_barang_edit'])->name('transaksi_barang.edit');
Route::delete('/transaksi_barang/{id}/delete', [prosesController::class, 'transaksi_barang_destroy'])->name('transaksi_barang.hapus');
// End transaksi Barang

// Start Transaksi Kendaraan
Route::post('/transaksi_kendaraan/store', [prosesController::class, 'transaksi_kendaraan_store'])->name('transaksi_kendaraan.store');
Route::post('/transaksi_kendaraan/{id}/edit', [prosesController::class, 'transaksi_kendaraan_edit'])->name('transaksi_kendaraan.edit');
Route::delete('/transaksi_kendaraan/{id}/delete', [prosesController::class, 'transaksi_kendaraan_destroy'])->name('transaksi_kendaraan.hapus');
// End Transaksi Kendaraan

// Start Data Pegawai
Route::post('/data_pegawai/store', [prosesController::class, 'data_pegawai_store'])->name('data_pegawai.store');
Route::post('/data_pegawai/{id}/edit', [prosesController::class, 'data_pegawai_edit'])->name('data_pegawai.edit');
Route::delete('/data_pegawai/{id}/delete', [prosesController::class, 'data_pegawai_destroy'])->name('data_pegawai.destroy');
// End Data Pegawai






















// Start Admin
// Start User Manajemen Admin
Route::post('/admin/user_manajemen/store', [prosesController::class, 'admin_usermanajemen_store'])->name('admin.user_manajemen.store');
Route::get('/admin/user_manajemen/{id}/edit', [prosesController::class, 'admin_usermanajemen_edit'])->name('admin.user_manajemen.edit');
Route::post('/admin/user_manajemen/{id}', [prosesController::class, 'admin_usermanajemen_update'])->name('admin.user_manajemen.update');
Route::delete('/admin/user_manajemen/{id}/delete', [prosesController::class, 'admin_usermanajemen_destroy'])->name('admin.user_manajemen.delete');
// End User Manajemen Admin

// Start Export To Excel Admin
Route::post('/export_data_barang', [prosesController::class, 'export_data_barang'])->name('export.data_barang');
Route::post('/export_mutasi', [prosesController::class, 'export_mutasi'])->name('export.mutasi');
Route::post('/export_penghapusan', [prosesController::class, 'export_penghapusan'])->name('export.penghapusan');
// End Export To Excel Admin
// End Admin

// Start Campur
// Start Data Barang
Route::post('/data_barang/pilihan', [prosesController::class, 'data_barang_pilihan'])->name('data_barang.pilihan');
// End Data Barang

// Start Mutasi
Route::post('/mutasi/pilihan', [prosesController::class, 'mutasi_pilihan'])->name('mutasi_pilihan');
// End Mutasi

// Start Penghapusan
Route::post('/penghapusan/pilihan', [prosesController::class, 'penghapusan_pilihan'])->name('penghapusan_pilihan');
// End Penghapusan
// End Campur

// Start Pengguna
// Pengguna Data Barang
Route::post('/pengguna/data_barang/store', [prosesController::class, 'pengguna_databarang_store'])->name('pengguna.data_barang.store');
Route::get('/pengguna/data_barang/{id}/edit', [prosesController::class, 'pengguna_databarang_edit'])->name('pengguna.data_barang.edit');
Route::post('/pengguna/data_barang/{id}', [prosesController::class, 'pengguna_databarang_update'])->name('pengguna.data_barang.update');
Route::get('/pengguna/data_barang/{id}/delete', [prosesController::class, 'pengguna_databarang_destroy'])->name('pengguna.data_barang.delete');
// End Pengguna Data Barang
// Pengguna Mutasi
Route::post('/pengguna/mutasi/store', [prosesController::class, 'pengguna_mutasi_store'])->name('pengguna.mutasi.store');
Route::get('/pengguna/mutasi/{id}/edit', [prosesController::class, 'pengguna_mutasi_edit'])->name('pengguna.mutasi.edit');
Route::post('/pengguna/mutasi/{id}', [prosesController::class, 'pengguna_mutasi_update'])->name('pengguna.mutasi.update');
Route::get('/pengguna/mutasi/{id}/delete', [prosesController::class, 'pengguna_mutasi_destroy'])->name('pengguna.mutasi.delete');
// End Pengguna Mutasi
// Pengguna Penghapusan
Route::post('/pengguna/penghapusan/store', [prosesController::class, 'pengguna_penghapusan_store'])->name('pengguna.penghapusan.store');
Route::get('/pengguna/penghapusan/{id}/edit', [prosesController::class, 'pengguna_penghapusan_edit'])->name('pengguna.penghapusan.edit');
Route::post('/pengguna/penghapusan/{id}', [prosesController::class, 'pengguna_penghapusan_update'])->name('pengguna.penghapusan.update');
Route::get('/pengguna/penghapusan/{id}/delete', [prosesController::class, 'pengguna_penghapusan_destroy'])->name('pengguna.penghapusan.delete');
// End Pengguna Penghapusan
// End Pengguna
