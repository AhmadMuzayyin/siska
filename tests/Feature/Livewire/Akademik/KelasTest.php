<?php

use App\Enums\UserRole;
use App\Livewire\Akademik\Kelas as KelasComponent;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\User;
use App\Models\WaliKelas;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders the kelas page for an admin', function () {
    $this->actingAs($this->admin)
        ->get(route('akademik.kelas'))
        ->assertOk()
        ->assertSeeLivewire(KelasComponent::class);
});

test('a guru cannot create or delete kelas', function () {
    $guruUser = User::factory()->create(['role' => UserRole::Guru]);

    Livewire::actingAs($guruUser)
        ->test(KelasComponent::class)
        ->call('create')
        ->assertForbidden();
});

test('creates a new kelas', function () {
    Livewire::actingAs($this->admin)
        ->test(KelasComponent::class)
        ->set('nama', '7A')
        ->set('kapasitas', 25)
        ->call('save')
        ->assertHasNoErrors();

    expect(Kelas::query()->where('nama', '7A')->exists())->toBeTrue();
});

test('validates required fields when creating kelas', function () {
    Livewire::actingAs($this->admin)
        ->test(KelasComponent::class)
        ->set('nama', '')
        ->call('save')
        ->assertHasErrors(['nama']);
});

test('updates an existing kelas', function () {
    $kelas = Kelas::factory()->create(['nama' => 'Lama']);

    Livewire::actingAs($this->admin)
        ->test(KelasComponent::class)
        ->call('edit', $kelas->id)
        ->set('nama', 'Baru')
        ->call('save')
        ->assertHasNoErrors();

    expect($kelas->fresh()->nama)->toBe('Baru');
});

test('prevents deleting a kelas that still has santris', function () {
    $kelas = Kelas::factory()->create();
    Santri::factory()->create(['kelas_id' => $kelas->id]);

    Livewire::actingAs($this->admin)
        ->test(KelasComponent::class)
        ->call('delete', $kelas->id);

    expect(Kelas::query()->whereKey($kelas->id)->exists())->toBeTrue();
});

test('deletes a kelas with no santris', function () {
    $kelas = Kelas::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(KelasComponent::class)
        ->call('delete', $kelas->id);

    expect(Kelas::query()->whereKey($kelas->id)->exists())->toBeFalse();
});

test('assigns a wali kelas when saving a kelas', function () {
    $kelas = Kelas::factory()->create();
    $guru = Guru::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(KelasComponent::class)
        ->call('edit', $kelas->id)
        ->set('waliKelasGuruId', $guru->id)
        ->call('save')
        ->assertHasNoErrors();

    expect(WaliKelas::query()->where('kelas_id', $kelas->id)->where('guru_id', $guru->id)->exists())->toBeTrue();
});

test('removes the wali kelas when the guru is unset', function () {
    $kelas = Kelas::factory()->create();
    $guru = Guru::factory()->create();
    WaliKelas::factory()->create(['kelas_id' => $kelas->id, 'guru_id' => $guru->id]);

    Livewire::actingAs($this->admin)
        ->test(KelasComponent::class)
        ->call('edit', $kelas->id)
        ->set('waliKelasGuruId', null)
        ->call('save')
        ->assertHasNoErrors();

    expect(WaliKelas::query()->where('kelas_id', $kelas->id)->exists())->toBeFalse();
});
