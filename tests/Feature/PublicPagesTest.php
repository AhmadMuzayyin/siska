<?php

use App\Models\Kelas;
use App\Models\Setting;

test('the home page renders with lembaga name from settings', function () {
    Setting::query()->updateOrCreate([], ['lembaga' => 'Pesantren Test']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Pesantren Test');
});

test('the santri registration form page renders', function () {
    Kelas::factory()->create();

    $this->get(route('santri.register.form'))
        ->assertOk()
        ->assertSee('Pendaftaran Santri Baru');
});

test('the contact page renders', function () {
    $this->get(route('contact.show'))
        ->assertOk()
        ->assertSee('Hubungi Kami');
});

test('the about page renders', function () {
    $this->get(route('about'))
        ->assertOk()
        ->assertSee('Tentang Kami')
        ->assertSee('Visi');
});

test('the program page renders', function () {
    $this->get(route('program'))
        ->assertOk()
        ->assertSee('Program Pendidikan Al-Hikmah')
        ->assertSee('Taman Pendidikan Al-Qur\'an');
});

test('the galeri page renders', function () {
    $this->get(route('galeri'))
        ->assertOk()
        ->assertSee('Galeri & Dokumentasi Kegiatan');
});
