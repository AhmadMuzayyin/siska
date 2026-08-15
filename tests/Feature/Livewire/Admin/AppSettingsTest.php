<?php

use App\Enums\UserRole;
use App\Livewire\Settings\Index;
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
        ->assertSee(__('Pengaturan'));
});

test('updates the application general settings', function () {
    Livewire::actingAs($this->admin)
        ->test(Index::class)
        ->set('lembaga', 'Pondok Pesantren Baru')
        ->set('email_lembaga', 'pondok@example.com')
        ->set('telepon', '081234567890')
        ->set('alamat', 'Jl. Pesantren No. 1')
        ->set('payroll_cutoff_day', 20)
        ->call('saveGeneral')
        ->assertHasNoErrors();

    expect(Setting::query()->first()->lembaga)->toBe('Pondok Pesantren Baru');
});
