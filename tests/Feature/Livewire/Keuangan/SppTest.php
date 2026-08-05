<?php

use App\Enums\UserRole;
use App\Livewire\Keuangan\Spp as SppComponent;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\Spp;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
    Semester::factory()->active()->create();
});

test('renders the spp page for an admin', function () {
    $this->actingAs($this->admin)
        ->get(route('keuangan.spp'))
        ->assertOk()
        ->assertSeeLivewire(SppComponent::class);
});

test('records an spp payment with bulan and tahun derived from tanggal', function () {
    $santri = Santri::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(SppComponent::class)
        ->set('santriId', $santri->id)
        ->set('tanggal', '2026-03-10')
        ->set('nominal', 150000)
        ->call('save')
        ->assertHasNoErrors();

    $spp = Spp::query()->where('santri_id', $santri->id)->first();
    expect($spp->bulan)->toBe(3)->and($spp->tahun)->toBe(2026);
});

test('rejects a duplicate payment for the same month', function () {
    $santri = Santri::factory()->create();

    $component = Livewire::actingAs($this->admin)->test(SppComponent::class)
        ->set('santriId', $santri->id)
        ->set('tanggal', '2026-03-10')
        ->set('nominal', 150000);

    $component->call('save');
    $component->set('tanggal', '2026-03-20')->call('save');

    expect(Spp::query()->where('santri_id', $santri->id)->count())->toBe(1);
});
