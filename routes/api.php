<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/siswa', [App\Http\Controllers\Api\SiswaController::class, 'index']);
Route::post('/siswa-paket', [App\Http\Controllers\Api\UjianSiswaController::class, 'ShowUjianByUjianId']);

Route::get('/is-available-jadwal/{jadwal_ujian_id}', [App\Http\Controllers\Api\JadwalUjianController::class, 'IsUjianAvailable']);
Route::get('/is-any-onprogress', [App\Http\Controllers\Api\JadwalUjianController::class, 'IsAnyOnProgress']);
Route::get('/active-jadwal', [App\Http\Controllers\Api\JadwalUjianController::class, 'GetActiveUjian']);

Route::get('/koreksi/start/{jadwal_ujian_id}', [App\Http\Controllers\Api\RemoteKoreksiController::class, 'StartRemoteKoreksi']);
Route::get('/koreksi/finish/{jadwal_ujian_id}/{state}', [App\Http\Controllers\Api\RemoteKoreksiController::class, 'FinishHookRemoteKoreksi']);
Route::get('/koreksi/elapsed/{jadwal_ujian_id}', [App\Http\Controllers\Api\RemoteKoreksiController::class, 'GetElapsedTime']);
Route::get('/koreksi/fullstat/{jadwal_ujian_id}', [App\Http\Controllers\Api\RemoteKoreksiController::class, 'GetFullStat']);
