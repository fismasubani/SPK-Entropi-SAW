<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntropiController;
use App\Http\Controllers\AlgoritmaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource("kriteria", "KriteriaController")->except(['create']);
Route::resource("alternatif", "AlternatifController")->except(['create','show']);
Route::resource("crips", "CripsController")->except(['index','create','show']);
Route::resource('/penilaian', 'PenilaianController');
Route::get('/perhitungan','AlgoritmaController@index')->name('perhitungan.index');
Route::get('/admin/perhitungan-entropi', [EntropiController::class, 'index'])->name('admin.perhitungan.entropi');
Route::post('/entropi/simpan-bobot', [EntropiController::class, 'simpanBobot'])->name('entropi.simpanBobot');
Route::get('/entropi', [EntropiController::class, 'index'])->name('entropi.index');
Route::get('/perhitungan', [AlgoritmaController::class, 'index'])->name('perhitungan.index');
Route::get('/perhitungan/cetak-pdf', [AlgoritmaController::class, 'cetak'])->name('perhitungan.cetak');
