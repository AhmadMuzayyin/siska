<?php

use App\Enums\UserRole;
use App\Livewire\Admin\AppSettings;
use App\Models\Setting;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    Setting::factory()->create(['lembaga' => 'Pondok Lama']);
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders the settings page for an admin', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.settings'))
        ->assertOk()
        ->assertSeeLivewire(AppSettings::class);
});

test('a non-admin cannot access the settings page', function () {
    $guru = User::factory()->create(['role' => UserRole::Guru]);

    $this->actingAs($guru)
        ->get(route('admin.settings'))
        ->assertForbidden();
});

test('updates the application setting', function () {
    Livewire::actingAs($this->admin)
        ->test(AppSettings::class)
        ->set('lembaga', 'Pondok Pesantren Baru')
        ->set('payroll_cutoff_day', 20)
        ->call('save')
        ->assertHasNoErrors();

    expect(Setting::query()->first()->lembaga)->toBe('Pondok Pesantren Baru');
});
