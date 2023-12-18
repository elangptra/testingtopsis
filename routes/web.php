<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\NilaiAltController;
use App\Http\Controllers\PerhitunganController;


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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register/simpan', [AuthController::class, 'registerSimpan'])->name('register.simpan');


Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'homedashboard'])->name('dashboard');

Route::controller(KriteriaController::class)->prefix('kriteria')->group(function () {
    Route::get('', 'index')->name('kriteria');
    Route::get('tambah', 'create')->name('kriteria.create');
    Route::post('tambah', 'store')->name('kriteria.create.store');
    Route::get('edit/{kode_kriteria}', 'edit')->name('kriteria.edit');
    Route::post('edit/{kode_kriteria}', 'update')->name('kriteria.create.update');
    Route::get('detail/{kode_kriteria}', 'show')->name('kriteria.detail');
    Route::get('hapus/{kode_kriteria}', 'destroy')->name('kriteria.destroy');
    Route::get('/search','search')->name('kriteria.search');
});

Route::controller(AlternatifController::class)->prefix('alternatif')->group(function () {
    Route::get('', 'index')->name('alternatif');
    Route::get('tambah', 'create')->name('alternatif.create');
    Route::post('tambah', 'store')->name('alternatif.create.store');
    Route::get('edit/{kode_alternatif}', 'edit')->name('alternatif.edit');
    Route::post('edit/{kode_alternatif}', 'update')->name('alternatif.create.update');
    Route::get('detail/{kode_alternatif}', 'show')->name('alternatif.detail');
    Route::get('hapus/{kode_alternatif}', 'destroy')->name('alternatif.destroy');
    Route::get('/search','search')->name('alternatif.search');
});

Route::controller(NilaiAltController::class)->prefix('nilaialt')->group(function () {
    Route::get('', 'index')->name('nilaialt');
    Route::get('tambah', 'create')->name('nilaialt.create');
    Route::post('tambah', 'store')->name('nilaialt.create.store');
    Route::get('edit/{kode_alt}', 'edit')->name('nilaialt.edit');
    Route::post('edit/{kode_alt}', 'update')->name('nilaialt.create.update');
    Route::get('detail/{kode_alt}', 'show')->name('nilaialt.detail');
    Route::get('hapus/{kode_alt}', 'destroy')->name('nilaialt.destroy');
    Route::get('/search','search')->name('nilaialt.search');
});

Route::controller(PerhitunganController::class)->prefix('normalisasi')->group(function(){
    Route::get('normal','index')->name('normalisasi_nilai');
});

Route::get('/normalisasi', [App\Http\Controllers\PerhitunganController::class, 'normalisasi'])->name('normalisasi');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
