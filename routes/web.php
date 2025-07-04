<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\prosesController;
use App\Http\Controllers\salahController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('hy', [HomeController::class, 'hy'])->name('hy');
Route::get('/', [HomeController::class, 'index'])->name('index');

// tampilan sub menu
// Route::get('/dashboard/{menu}', [HomeController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard/{menu}', [HomeController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard/{menu}/search/', [HomeController::class, 'search'])->name('dashboard.search');
Route::get('/logout', [HomeController::class, 'logout'])->name('logout');


// intinya yang salah-salah disini
Route::get('/login', [salahController::class, 'salahlogin'])->name('login');
Route::get('/{salahweb}', [salahController::class, 'salahweb'])->name('salahweb');

