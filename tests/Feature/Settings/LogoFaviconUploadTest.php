<?php

use App\Enums\UserRole;
use App\Livewire\Settings\Index;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('admin can upload logo and favicon', function () {
    Storage::fake('public');

    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $logoFile = UploadedFile::fake()->image('logo.png');
    $faviconFile = UploadedFile::fake()->image('favicon.png');

    $this->actingAs($admin);

    Livewire::test(Index::class)
        ->set('logo_upload', $logoFile)
        ->set('favicon_upload', $faviconFile)
        ->call('saveGeneral')
        ->assertHasNoErrors();

    $setting = Setting::query()->first();

    expect($setting->logo)->not->toBeNull();
    expect($setting->favicon)->not->toBeNull();
    expect($setting->logo_url)->not->toBeNull();
    expect($setting->favicon_url)->not->toBeNull();

    $cleanLogoPath = ltrim(preg_replace('/^\/?storage\//', '', $setting->logo), '/');
    $cleanFaviconPath = ltrim(preg_replace('/^\/?storage\//', '', $setting->favicon), '/');

    Storage::disk('public')->assertExists($cleanLogoPath);
    Storage::disk('public')->assertExists($cleanFaviconPath);
});

test('admin can remove logo and favicon', function () {
    Storage::fake('public');

    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $logoPath = UploadedFile::fake()->image('logo.png')->store('logos', 'public');
    $faviconPath = UploadedFile::fake()->image('favicon.ico')->store('favicons', 'public');

    $setting = Setting::query()->firstOrCreate([], [
        'lembaga' => 'Test Institution',
    ]);
    $setting->update([
        'logo' => $logoPath,
        'favicon' => $faviconPath,
    ]);

    $this->actingAs($admin);

    Livewire::test(Index::class)
        ->call('removeLogo')
        ->assertHasNoErrors();

    expect($setting->fresh()->logo)->toBeNull();
    Storage::disk('public')->assertMissing($logoPath);

    Livewire::test(Index::class)
        ->call('removeFavicon')
        ->assertHasNoErrors();

    expect($setting->fresh()->favicon)->toBeNull();
    Storage::disk('public')->assertMissing($faviconPath);
});
