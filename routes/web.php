<?php

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
Route::resource("kriteria", "KriteriaController")->except(['create']);
Route::resource("alternatif", "AlternatifController")->except(['create','show']);
Route::resource("crips", "CripsController")->except(['index','create','show']);
Route::resource('/penilaian', 'PenilaianController');
Route::get('/perhitungan','AlgoritmaController@index')->name('perhitungan.index');
