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
    return redirect('/login');
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/koreksi', [App\Http\Controllers\HomeController::class, 'ShowKoreksi'])->name('koreksi');
Route::get('/koreksi/log/{jadwal_ujian_id}', [App\Http\Controllers\KoreksiLogController::class, 'ShowKoreksiLog']);

Route::get('/agregasi-hasil', [App\Http\Controllers\HomeController::class, 'ShowHasilDev']);
Route::get('/agregasi-hasil/rayon', [App\Http\Controllers\HomeController::class, 'ShowAgregasiRayon']);

Route::get('/agregasi-kisi', [App\Http\Controllers\HomeController::class, 'ShowAgregasiKisi']);
Route::get('/detail-kisi', [App\Http\Controllers\HomeController::class, 'ShowDetailKisi']);
Route::get('/mutu-kisi', [App\Http\Controllers\HomeController::class, 'ShowMutuKisi']);

Route::get('/export/agregasi-ujian/{jadwal_ujian_id}', [App\Http\Controllers\ExcelExportController::class, 'ExportHasilAgregasiDataUjianLevel']);
Route::get('/download/mutu/kisi/{mapel}', [App\Http\Controllers\DownloadController::class, 'DownloadMutuKisiMapel']);
