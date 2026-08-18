<?php

use App\Enums\UserRole;
use App\Livewire\Settings\Index;
use App\Models\Setting;
use App\Models\User;
use Livewire\Livewire;

test('admin can access public page content settings tab and see column structure', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    Setting::query()->firstOrCreate([], [
        'lembaga' => 'Pesantren Digital Modern',
    ]);

    $this->actingAs($admin);

    Livewire::test(Index::class, ['tab' => 'pages'])
        ->assertSee('Konten Halaman Publik')
        ->assertSee('Halaman Beranda')
        ->assertSee('Halaman Program')
        ->assertSee('Halaman Tentang Kami')
        ->assertSee('Halaman Kontak')
        ->assertSee('Halaman Galeri')
        ->assertSee('Background & Hero Slides');
});

test('admin can customize content per page from settings tab', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    Setting::query()->firstOrCreate([], [
        'lembaga' => 'Pesantren Digital Modern',
    ]);

    $this->actingAs($admin);

    Livewire::test(Index::class, ['tab' => 'pages'])
        ->set('content.hero_title', 'Pesantren Unggulan Terpadu')
        ->set('content.page_program_title', 'Kurikulum Khusus Tahfidz')
        ->set('content.page_about_title', 'Sejarah & Profil Lembaga')
        ->set('content.page_contact_title', 'Pusat Informasi & Sekretariat')
        ->set('content.page_gallery_title', 'Koleksi Dokumentasi Kegiatan')
        ->call('savePageContent')
        ->assertHasNoErrors();

    $setting = Setting::query()->first()->fresh();
    $content = $setting->landing_custom_content['default'] ?? [];

    expect($content['hero_title'])->toBe('Pesantren Unggulan Terpadu');
    expect($content['page_program_title'])->toBe('Kurikulum Khusus Tahfidz');
    expect($content['page_about_title'])->toBe('Sejarah & Profil Lembaga');
    expect($content['page_contact_title'])->toBe('Pusat Informasi & Sekretariat');
    expect($content['page_gallery_title'])->toBe('Koleksi Dokumentasi Kegiatan');
});

test('non-admin user cannot access public page content settings', function () {
    $guru = User::factory()->create([
        'role' => UserRole::Guru,
    ]);

    $this->actingAs($guru);

    Livewire::test(Index::class, ['tab' => 'pages'])
        ->assertSet('tab', 'profile')
        ->assertDontSee('Konten Halaman Publik');
});
