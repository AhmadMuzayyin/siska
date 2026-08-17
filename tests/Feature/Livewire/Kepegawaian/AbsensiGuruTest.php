<?php

use App\Enums\GuruStatus;
use App\Enums\HariSekolah;
use App\Enums\UserRole;
use App\Livewire\Kepegawaian\AbsensiGuru;
use App\Models\AbsensiGuru as AbsensiGuruModel;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Semester;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders the absensi guru page for an admin', function () {
    $this->actingAs($this->admin)
        ->get(route('kepegawaian.absensi'))
        ->assertOk()
        ->assertSeeLivewire(AbsensiGuru::class);
});

test('records attendance for a guru who has a schedule on the selected date', function () {
    $semester = Semester::factory()->active()->create();
    $guru = Guru::factory()->create(['status' => GuruStatus::Aktif]);
    // 2026-01-05 is a Monday (Senin)
    JadwalPelajaran::factory()->create([
        'guru_id' => $guru->id,
        'semester_id' => $semester->id,
        'hari' => HariSekolah::Senin,
    ]);

    Livewire::actingAs($this->admin)
        ->test(AbsensiGuru::class)
        ->set('semesterId', $semester->id)
        ->set('tanggal', '2026-01-05')
        ->call('setStatus', $guru->id, 'hadir');

    expect(AbsensiGuruModel::query()->where('guru_id', $guru->id)->where('status', 'hadir')->exists())->toBeTrue();
});

test('does not record attendance for a guru without a schedule on that day', function () {
    $semester = Semester::factory()->active()->create();
    $guru = Guru::factory()->create(['status' => GuruStatus::Aktif]);
    // No schedule on 2026-01-05 (Senin)

    Livewire::actingAs($this->admin)
        ->test(AbsensiGuru::class)
        ->set('semesterId', $semester->id)
        ->set('tanggal', '2026-01-05')
        ->call('setStatus', $guru->id, 'hadir');

    expect(AbsensiGuruModel::query()->where('guru_id', $guru->id)->exists())->toBeFalse();
});

test('shows a friendly error when attendance for that day already exists', function () {
    $semester = Semester::factory()->active()->create();
    $guru = Guru::factory()->create(['status' => GuruStatus::Aktif]);
    JadwalPelajaran::factory()->create([
        'guru_id' => $guru->id,
        'semester_id' => $semester->id,
        'hari' => HariSekolah::Senin,
    ]);
    AbsensiGuruModel::factory()->create(['guru_id' => $guru->id, 'semester_id' => $semester->id, 'tanggal' => '2026-01-05']);

    Livewire::actingAs($this->admin)
        ->test(AbsensiGuru::class)
        ->set('semesterId', $semester->id)
        ->set('tanggal', '2026-01-05')
        ->call('setStatus', $guru->id, 'izin');

    expect(AbsensiGuruModel::query()->where('guru_id', $guru->id)->count())->toBe(1);
});
