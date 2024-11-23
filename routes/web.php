<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TahunAkademikController;
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
    Route::controller(JadwalPelajaranController::class)->group(function () {
        Route::get('/jadwal/print', 'print')->name('jadwal.print');
    });
    Route::controller(AbsensiController::class)->group(function () {
        Route::post('/absensi/session', 'session')->name('absensi.session');
        Route::post('/absensi/forget', 'forget')->name('absensi.forget');
        Route::get('/absensi/create', 'create')->name('absensi.create');
        Route::post('/absensi', 'store')->name('absensi.store');
    });
    Route::controller(NilaiController::class)->group(function () {
        Route::get('/nilai/print/{santri}', 'print')->name('nilai.print');
    });
    Route::controller(TahunAkademikController::class)->group(function () {
        Route::patch('/tahun-akademik/semester', 'semester')->name('semester.update');
        Route::delete('/tahun-akademik/semester/{id}', 'destroy')->name('semester.destroy');
    });
});
