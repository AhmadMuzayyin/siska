<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return redirect('admin/login');
})->name('login');
Route::get(env('GOOGLE_CALLBACK_URL'), [SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::middleware(['auth'])->group(function () {
    Route::post('/absensi/session', [AbsensiController::class, 'session'])->name('absensi.session');
    Route::post('/absensi/forget', [AbsensiController::class, 'forget'])->name('absensi.forget');
    Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
});
