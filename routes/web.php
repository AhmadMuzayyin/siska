<?php

use App\Http\Controllers\AbsensiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/absensi/session', [AbsensiController::class, 'session'])->name('absensi.session');
Route::post('/absensi/forget', [AbsensiController::class, 'forget'])->name('absensi.forget');
Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
