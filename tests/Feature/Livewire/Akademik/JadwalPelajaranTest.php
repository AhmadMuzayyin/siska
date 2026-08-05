<?php

use App\Enums\UserRole;
use App\Livewire\Akademik\JadwalPelajaran as JadwalPelajaranComponent;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\Mapel;
use App\Models\Semester;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders the jadwal pelajaran page for an admin', function () {
    $this->actingAs($this->admin)
        ->get(route('akademik.jadwal-pelajaran'))
        ->assertOk()
        ->assertSeeLivewire(JadwalPelajaranComponent::class);
});

test('creates a new jadwal pelajaran', function () {
    $semester = Semester::factory()->create();
    $kelas = Kelas::factory()->create();
    $mapel = Mapel::factory()->create();
    $guru = Guru::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(JadwalPelajaranComponent::class)
        ->set('semester_id', $semester->id)
        ->set('kelas_id', $kelas->id)
        ->set('mapel_id', $mapel->id)
        ->set('guru_id', $guru->id)
        ->set('hari', 'senin')
        ->set('jam_mulai', '07:00')
        ->set('jam_selesai', '08:30')
        ->call('save')
        ->assertHasNoErrors();

    expect(JadwalPelajaran::query()->where('kelas_id', $kelas->id)->exists())->toBeTrue();
});

test('rejects a clashing schedule for the same kelas, hari, and jam', function () {
    $semester = Semester::factory()->create();
    $kelas = Kelas::factory()->create();
    $mapel = Mapel::factory()->create();
    $guru = Guru::factory()->create();

    JadwalPelajaran::factory()->create([
        'semester_id' => $semester->id,
        'kelas_id' => $kelas->id,
        'hari' => 'senin',
        'jam_mulai' => '07:00:00',
        'jam_selesai' => '08:30:00',
    ]);

    Livewire::actingAs($this->admin)
        ->test(JadwalPelajaranComponent::class)
        ->set('semester_id', $semester->id)
        ->set('kelas_id', $kelas->id)
        ->set('mapel_id', $mapel->id)
        ->set('guru_id', $guru->id)
        ->set('hari', 'senin')
        ->set('jam_mulai', '07:00')
        ->set('jam_selesai', '08:00')
        ->call('save');

    expect(JadwalPelajaran::query()->where('kelas_id', $kelas->id)->count())->toBe(1);
});

test('rejects a clashing schedule for the same teacher on overlapping time across different classes or lembagas', function () {
    $semester = Semester::factory()->create();
    $lembaga1 = Lembaga::factory()->create(['nama' => 'MI']);
    $lembaga2 = Lembaga::factory()->create(['nama' => 'MTs']);
    $kelas1 = Kelas::factory()->create(['lembaga_id' => $lembaga1->id, 'nama' => '1A']);
    $kelas2 = Kelas::factory()->create(['lembaga_id' => $lembaga2->id, 'nama' => '7A']);
    $mapel1 = Mapel::factory()->create();
    $mapel2 = Mapel::factory()->create();
    $guru = Guru::factory()->create();

    // Schedule 1: Guru teaches Class 1A (MI) on Monday 07:00-08:30
    JadwalPelajaran::factory()->create([
        'semester_id' => $semester->id,
        'kelas_id' => $kelas1->id,
        'mapel_id' => $mapel1->id,
        'guru_id' => $guru->id,
        'hari' => 'senin',
        'jam_mulai' => '07:00:00',
        'jam_selesai' => '08:30:00',
    ]);

    // Schedule 2 attempt: Try to schedule the SAME Guru for Class 7A (MTs) on Monday 08:00-09:00 (overlaps by 30 mins!)
    Livewire::actingAs($this->admin)
        ->test(JadwalPelajaranComponent::class)
        ->set('semester_id', $semester->id)
        ->set('kelas_id', $kelas2->id)
        ->set('mapel_id', $mapel2->id)
        ->set('guru_id', $guru->id)
        ->set('hari', 'senin')
        ->set('jam_mulai', '08:00')
        ->set('jam_selesai', '09:00')
        ->call('save');

    // Should NOT create the second schedule due to teacher conflict
    expect(JadwalPelajaran::query()->where('guru_id', $guru->id)->count())->toBe(1);
});

test('prevents deleting a jadwal that already has absensi data', function () {
    $jadwal = JadwalPelajaran::factory()->create();
    Absensi::factory()->create(['jadwal_pelajaran_id' => $jadwal->id]);

    Livewire::actingAs($this->admin)
        ->test(JadwalPelajaranComponent::class)
        ->call('delete', $jadwal->id);

    expect(JadwalPelajaran::query()->whereKey($jadwal->id)->exists())->toBeTrue();
});
