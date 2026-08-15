<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\SantriRegistrationController;
use App\Livewire\Dashboard;
use App\Livewire\Public\DaftarSantri;
use App\Livewire\Public\Galeri;
use App\Livewire\Public\Home;
use App\Livewire\Public\Program;
use Illuminate\Support\Facades\Route;

Route::livewire('/', Home::class)->name('home');
Route::livewire('/program', Program::class)->name('program');
Route::livewire('/galeri', Galeri::class)->name('galeri');

Route::view('/tentang', 'tentang')->name('about');

Route::livewire('/daftar', DaftarSantri::class)->name('santri.register.form');
Route::post('/daftar', [SantriRegistrationController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('santri.register');

Route::get('/kontak', [ContactController::class, 'create'])->name('contact.show');

Route::post('/kontak', [ContactController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('contact.store');

Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/syarat-ketentuan', 'terms')->name('terms');
Route::view('/kebijakan-cookie', 'cookies')->name('cookies');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('dashboard', Dashboard::class)->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
