<?php

use App\Enums\SantriStatus;
use App\Enums\UserRole;
use App\Livewire\Kesantrian\Santri as SantriComponent;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders the santri page for an admin', function () {
    $this->actingAs($this->admin)
        ->get(route('kesantrian.santri'))
        ->assertOk()
        ->assertSeeLivewire(SantriComponent::class);
});

test('creates a new santri via the enroll action', function () {
    $kelas = Kelas::factory()->create(['kapasitas' => 5]);
    $data = Santri::factory()->make(['kelas_id' => $kelas->id])->toArray();

    $component = Livewire::actingAs($this->admin)->test(SantriComponent::class);

    foreach ($data as $key => $value) {
        if (property_exists($component->instance(), $key)) {
            $component->set($key, $value instanceof BackedEnum ? $value->value : $value);
        }
    }

    $component->call('save')->assertHasNoErrors();

    expect(Santri::query()->where('noinduk', $data['noinduk'])->exists())->toBeTrue();
});

test('rejects enrollment when the kelas is full', function () {
    $kelas = Kelas::factory()->create(['kapasitas' => 1]);
    Santri::factory()->create(['kelas_id' => $kelas->id]);
    $data = Santri::factory()->make(['kelas_id' => $kelas->id])->toArray();

    $component = Livewire::actingAs($this->admin)->test(SantriComponent::class);

    foreach ($data as $key => $value) {
        if (property_exists($component->instance(), $key)) {
            $component->set($key, $value instanceof BackedEnum ? $value->value : $value);
        }
    }

    $component->call('save');

    expect(Santri::query()->where('noinduk', $data['noinduk'])->exists())->toBeFalse();
});

test('approves a pending santri registration', function () {
    $santri = Santri::factory()->pendingApproval()->create();

    Livewire::actingAs($this->admin)
        ->test(SantriComponent::class)
        ->call('approve', $santri->id);

    expect($santri->fresh()->status)->toBe(SantriStatus::Aktif);
});

test('promotes selected santris to a new kelas', function () {
    $kelasAsal = Kelas::factory()->create();
    $kelasTujuan = Kelas::factory()->create();
    $santris = Santri::factory()->count(2)->create(['kelas_id' => $kelasAsal->id]);

    Livewire::actingAs($this->admin)
        ->test(SantriComponent::class)
        ->set('selected', $santris->pluck('id')->all())
        ->set('promoteKelasId', $kelasTujuan->id)
        ->call('promote');

    expect($kelasTujuan->santris()->count())->toBe(2);
});

test('prevents deleting a santri with academic history', function () {
    $santri = Santri::factory()->create();
    Nilai::factory()->create(['santri_id' => $santri->id]);

    Livewire::actingAs($this->admin)
        ->test(SantriComponent::class)
        ->call('delete', $santri->id);

    expect(Santri::query()->whereKey($santri->id)->exists())->toBeTrue();
});
