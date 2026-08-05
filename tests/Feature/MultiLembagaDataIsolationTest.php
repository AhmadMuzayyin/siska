<?php

use App\Enums\UserRole;
use App\Livewire\Akademik\JadwalPelajaran as JadwalPelajaranComponent;
use App\Livewire\Akademik\Kelas as KelasComponent;
use App\Livewire\Akademik\Mapel as MapelComponent;
use App\Livewire\Kesantrian\AbsensiSantri as AbsensiSantriComponent;
use App\Livewire\Kesantrian\Nilai as NilaiComponent;
use App\Livewire\Kesantrian\Santri as SantriComponent;
use App\Livewire\Keuangan\Spp as SppComponent;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\Mapel;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\Spp;
use App\Models\User;
use App\Services\LembagaService;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->semester = Semester::factory()->create(['is_aktif' => true]);

    $this->lembagaA = Lembaga::factory()->create(['nama' => 'Madrasah Ibtidaiyah', 'kode' => 'mi']);
    $this->lembagaB = Lembaga::factory()->create(['nama' => 'Madrasah Tsanawiyah', 'kode' => 'mts']);

    $this->kelasA = Kelas::factory()->create(['lembaga_id' => $this->lembagaA->id, 'nama' => '1A']);
    $this->kelasB = Kelas::factory()->create(['lembaga_id' => $this->lembagaB->id, 'nama' => '7A']);

    $this->mapelA = Mapel::factory()->create(['lembaga_id' => $this->lembagaA->id, 'nama' => 'Fiqih MI']);
    $this->mapelB = Mapel::factory()->create(['lembaga_id' => $this->lembagaB->id, 'nama' => 'Fiqih MTs']);

    $this->santriA = Santri::factory()->create(['lembaga_id' => $this->lembagaA->id, 'kelas_id' => $this->kelasA->id, 'nama_lengkap' => 'Santri MI']);
    $this->santriB = Santri::factory()->create(['lembaga_id' => $this->lembagaB->id, 'kelas_id' => $this->kelasB->id, 'nama_lengkap' => 'Santri MTs']);
});

test('kelas component strictly filters rows by active lembaga', function () {
    app(LembagaService::class)->setActiveLembagaId($this->lembagaA->id);

    Livewire::actingAs($this->admin)
        ->test(KelasComponent::class)
        ->assertSee('1A')
        ->assertDontSee('7A');
});

test('mapel component strictly filters rows by active lembaga', function () {
    app(LembagaService::class)->setActiveLembagaId($this->lembagaA->id);

    Livewire::actingAs($this->admin)
        ->test(MapelComponent::class)
        ->assertSee('Fiqih MI')
        ->assertDontSee('Fiqih MTs');
});

test('santri component strictly filters rows by active lembaga', function () {
    app(LembagaService::class)->setActiveLembagaId($this->lembagaA->id);

    Livewire::actingAs($this->admin)
        ->test(SantriComponent::class)
        ->assertSee('Santri MI')
        ->assertDontSee('Santri MTs');
});

test('jadwal pelajaran component options and rows are strictly filtered by active lembaga', function () {
    JadwalPelajaran::factory()->create(['semester_id' => $this->semester->id, 'kelas_id' => $this->kelasA->id, 'mapel_id' => $this->mapelA->id]);
    JadwalPelajaran::factory()->create(['semester_id' => $this->semester->id, 'kelas_id' => $this->kelasB->id, 'mapel_id' => $this->mapelB->id]);

    app(LembagaService::class)->setActiveLembagaId($this->lembagaA->id);

    Livewire::actingAs($this->admin)
        ->test(JadwalPelajaranComponent::class)
        ->assertSee('Fiqih MI')
        ->assertDontSee('Fiqih MTs');
});

test('absensi santri component options are strictly filtered by active lembaga', function () {
    JadwalPelajaran::factory()->create(['semester_id' => $this->semester->id, 'kelas_id' => $this->kelasA->id, 'mapel_id' => $this->mapelA->id]);
    JadwalPelajaran::factory()->create(['semester_id' => $this->semester->id, 'kelas_id' => $this->kelasB->id, 'mapel_id' => $this->mapelB->id]);

    app(LembagaService::class)->setActiveLembagaId($this->lembagaA->id);

    Livewire::actingAs($this->admin)
        ->test(AbsensiSantriComponent::class)
        ->assertSee('Fiqih MI')
        ->assertDontSee('Fiqih MTs');
});

test('nilai component options are strictly filtered by active lembaga', function () {
    app(LembagaService::class)->setActiveLembagaId($this->lembagaA->id);

    Livewire::actingAs($this->admin)
        ->test(NilaiComponent::class)
        ->assertSee('Fiqih MI')
        ->assertDontSee('Fiqih MTs')
        ->assertSee('1A')
        ->assertDontSee('7A');
});

test('spp component options and rows are strictly filtered by active lembaga', function () {
    Spp::factory()->create(['santri_id' => $this->santriA->id, 'semester_id' => $this->semester->id, 'nominal' => 100000]);
    Spp::factory()->create(['santri_id' => $this->santriB->id, 'semester_id' => $this->semester->id, 'nominal' => 200000]);

    app(LembagaService::class)->setActiveLembagaId($this->lembagaA->id);

    Livewire::actingAs($this->admin)
        ->test(SppComponent::class)
        ->assertSee('Santri MI')
        ->assertDontSee('Santri MTs');
});
