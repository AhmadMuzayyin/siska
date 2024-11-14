<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('index');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');
Route::post('/subscribe', [SubscriptionController::class, 'store'])->name('subscribe.store');
Route::get('/daftar', function () {
    return view('register');
});
Route::post('/daftar', [RegisterController::class, 'store'])->name('register.store');
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
