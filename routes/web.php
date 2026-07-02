<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlternatifController;
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
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/dashboard/{id}', [DashboardController::class, 'show'])->name('dashboard.show');
Route::patch('/riwayat-perhitungan/{id}/info', [DashboardController::class, 'updateInfo'])->name('riwayat-perhitungan.updateInfo');
Route::delete('/dashboard/{id}', [DashboardController::class, 'destroy'])->name('dashboard.destroy');
Route::get('/riwayat-perhitungan', [DashboardController::class, 'index'])->name('riwayat-perhitungan.index');
Route::get('/dashboard/{id}/cetak', [DashboardController::class, 'cetakPDF'])->name('dashboard.cetak');

Route::resource("kriteria", "KriteriaController")->except(['create']);

Route::resource("alternatif", "AlternatifController")->except(['create','show']);
Route::post('/alternatif/import', [AlternatifController::class, 'import'])->name('alternatif.import');
Route::post('/alternatif/delete-all', [AlternatifController::class, 'deleteAll'])->name('alternatif.deleteAll');


Route::resource("crips", "CripsController")->except(['index','create','show']);
Route::resource('/penilaian', 'PenilaianController');
Route::get('/perhitungan','AlgoritmaController@index')->name('perhitungan.index');

Route::get('/admin/perhitungan-entropi', [EntropiController::class, 'index'])->name('admin.perhitungan.entropi');
Route::post('/entropi/simpan-bobot', [EntropiController::class, 'simpanBobot'])->name('entropi.simpanBobot');
Route::get('/entropi', [EntropiController::class, 'index'])->name('entropi.index');


Route::get('/perhitungan', [AlgoritmaController::class, 'index'])->name('perhitungan.index');
Route::post('/perhitungan/simpan', [AlgoritmaController::class, 'simpanRiwayat'])->name('perhitungan.simpan');
