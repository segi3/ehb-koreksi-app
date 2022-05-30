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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/koreksi', [App\Http\Controllers\HomeController::class, 'ShowKoreksi'])->name('koreksi');
Route::get('/hasil', [App\Http\Controllers\HomeController::class, 'ShowHasilDev'])->name('hasil');

Route::get('/export/agregasi-ujian/{jadwal_ujian_id}', [App\Http\Controllers\ExcelExportController::class, 'ExportHasilAgregasiDataUjianLevel']);
