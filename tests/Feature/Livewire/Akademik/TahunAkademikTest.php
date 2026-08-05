<?php

use App\Enums\UserRole;
use App\Livewire\Akademik\TahunAkademik as TahunAkademikComponent;
use App\Models\Nilai;
use App\Models\Semester;
use App\Models\TahunAkademik;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders the tahun akademik page for an admin', function () {
    $this->actingAs($this->admin)
        ->get(route('akademik.tahun-akademik'))
        ->assertOk()
        ->assertSeeLivewire(TahunAkademikComponent::class);
});

test('creates a tahun akademik', function () {
    Livewire::actingAs($this->admin)
        ->test(TahunAkademikComponent::class)
        ->set('nama', '2026/2027')
        ->call('saveTahun')
        ->assertHasNoErrors();

    expect(TahunAkademik::query()->where('nama', '2026/2027')->exists())->toBeTrue();
});

test('adds a semester to a tahun akademik and enforces the 2-semester limit', function () {
    $tahun = TahunAkademik::factory()->create();

    $component = Livewire::actingAs($this->admin)->test(TahunAkademikComponent::class);

    $component->call('createSemester', $tahun->id)
        ->set('tipe', 'ganjil')
        ->set('mulai', '2026-07-01')
        ->set('selesai', '2026-12-31')
        ->call('saveSemester')
        ->assertHasNoErrors();

    $component->call('createSemester', $tahun->id)
        ->set('tipe', 'genap')
        ->set('mulai', '2027-01-01')
        ->set('selesai', '2027-06-30')
        ->call('saveSemester')
        ->assertHasNoErrors();

    expect(Semester::query()->where('tahun_akademik_id', $tahun->id)->count())->toBe(2);

    // A third semester should be rejected.
    $component->call('createSemester', $tahun->id)
        ->set('tipe', 'ganjil')
        ->set('mulai', '2027-07-01')
        ->set('selesai', '2027-12-31')
        ->call('saveSemester');

    expect(Semester::query()->where('tahun_akademik_id', $tahun->id)->count())->toBe(2);
});

test('activating a semester from the ui deactivates its sibling', function () {
    $tahun = TahunAkademik::factory()->create();
    $active = Semester::factory()->for($tahun)->active()->create();
    $inactive = Semester::factory()->for($tahun)->create();

    Livewire::actingAs($this->admin)
        ->test(TahunAkademikComponent::class)
        ->call('activateSemester', $inactive->id);

    expect($active->fresh()->is_aktif)->toBeFalse()
        ->and($inactive->fresh()->is_aktif)->toBeTrue();
});

test('prevents deleting a semester that already has data', function () {
    $semester = Semester::factory()->create();
    Nilai::factory()->create(['semester_id' => $semester->id]);

    Livewire::actingAs($this->admin)
        ->test(TahunAkademikComponent::class)
        ->call('deleteSemester', $semester->id);

    expect(Semester::query()->whereKey($semester->id)->exists())->toBeTrue();
});
