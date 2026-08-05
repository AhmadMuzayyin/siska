<?php

use App\Enums\UserRole;
use App\Livewire\Kesantrian\Nilai as NilaiComponent;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders the nilai page for an admin', function () {
    $this->actingAs($this->admin)
        ->get(route('kesantrian.nilai'))
        ->assertOk()
        ->assertSeeLivewire(NilaiComponent::class);
});

test('records a nilai and computes predikat', function () {
    $semester = Semester::factory()->create();
    $kelas = Kelas::factory()->create();
    $mapel = Mapel::factory()->create(['kkm' => 60]);
    $santri = Santri::factory()->create(['kelas_id' => $kelas->id]);

    Livewire::actingAs($this->admin)
        ->test(NilaiComponent::class)
        ->set('semesterId', $semester->id)
        ->set('kelasId', $kelas->id)
        ->set('mapelId', $mapel->id)
        ->call('setNilai', $santri->id, '85');

    $nilai = Nilai::query()->where('santri_id', $santri->id)->where('mapel_id', $mapel->id)->first();

    expect($nilai->nilai)->toBe(85)
        ->and($nilai->predikat->value)->toBe('B')
        ->and($nilai->isLulus)->toBeTrue();
});

test('rejects a nilai outside the 0-100 range', function () {
    $semester = Semester::factory()->create();
    $kelas = Kelas::factory()->create();
    $mapel = Mapel::factory()->create();
    $santri = Santri::factory()->create(['kelas_id' => $kelas->id]);

    Livewire::actingAs($this->admin)
        ->test(NilaiComponent::class)
        ->set('semesterId', $semester->id)
        ->set('kelasId', $kelas->id)
        ->set('mapelId', $mapel->id)
        ->call('setNilai', $santri->id, '150');

    expect(Nilai::query()->where('santri_id', $santri->id)->exists())->toBeFalse();
});

test('updates an existing nilai instead of duplicating', function () {
    $semester = Semester::factory()->create();
    $kelas = Kelas::factory()->create();
    $mapel = Mapel::factory()->create();
    $santri = Santri::factory()->create(['kelas_id' => $kelas->id]);

    $component = Livewire::actingAs($this->admin)->test(NilaiComponent::class)
        ->set('semesterId', $semester->id)
        ->set('kelasId', $kelas->id)
        ->set('mapelId', $mapel->id);

    $component->call('setNilai', $santri->id, '70');
    $component->call('setNilai', $santri->id, '90');

    expect(Nilai::query()->where('santri_id', $santri->id)->count())->toBe(1)
        ->and(Nilai::query()->where('santri_id', $santri->id)->first()->nilai)->toBe(90);
});
