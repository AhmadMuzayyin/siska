<?php

use App\Actions\RecordAttendanceAction;
use App\Enums\HariSekolah;
use App\Enums\StudentAttendanceStatus;
use App\Exceptions\OutsideAttendanceWindowException;
use App\Models\Absensi;
use App\Models\JadwalPelajaran;
use App\Models\Santri;
use Carbon\CarbonImmutable;

// 2026-01-05 is a Monday (Senin), 2026-01-06 is a Tuesday (Selasa).
test('records attendance when scanned within the scheduled window', function () {
    $jadwal = JadwalPelajaran::factory()->create(['hari' => HariSekolah::Senin, 'jam_mulai' => '07:00:00', 'jam_selesai' => '08:30:00']);
    $santri = Santri::factory()->create();
    $at = CarbonImmutable::create(2026, 1, 5, 7, 15, 0);

    $absensi = app(RecordAttendanceAction::class)->handle($jadwal, $santri, StudentAttendanceStatus::Hadir, $at);

    expect($absensi->status)->toBe(StudentAttendanceStatus::Hadir)
        ->and($absensi->tanggal->toDateString())->toBe('2026-01-05');
});

test('rejects attendance recorded before the scheduled window starts', function () {
    $jadwal = JadwalPelajaran::factory()->create(['hari' => HariSekolah::Senin, 'jam_mulai' => '07:00:00', 'jam_selesai' => '08:30:00']);
    $santri = Santri::factory()->create();
    $at = CarbonImmutable::create(2026, 1, 5, 6, 0, 0);

    app(RecordAttendanceAction::class)->handle($jadwal, $santri, StudentAttendanceStatus::Hadir, $at);
})->throws(OutsideAttendanceWindowException::class);

test('rejects attendance recorded after the scheduled window ends', function () {
    $jadwal = JadwalPelajaran::factory()->create(['hari' => HariSekolah::Senin, 'jam_mulai' => '07:00:00', 'jam_selesai' => '08:30:00']);
    $santri = Santri::factory()->create();
    $at = CarbonImmutable::create(2026, 1, 5, 9, 0, 0);

    app(RecordAttendanceAction::class)->handle($jadwal, $santri, StudentAttendanceStatus::Hadir, $at);
})->throws(OutsideAttendanceWindowException::class);

test('rejects attendance recorded on the wrong day of week', function () {
    $jadwal = JadwalPelajaran::factory()->create(['hari' => HariSekolah::Senin, 'jam_mulai' => '07:00:00', 'jam_selesai' => '08:30:00']);
    $santri = Santri::factory()->create();
    $tuesday = CarbonImmutable::create(2026, 1, 6, 7, 15, 0);

    app(RecordAttendanceAction::class)->handle($jadwal, $santri, StudentAttendanceStatus::Hadir, $tuesday);
})->throws(OutsideAttendanceWindowException::class);

test('accepts attendance for a lesson that wraps past midnight', function () {
    $jadwal = JadwalPelajaran::factory()->create(['hari' => HariSekolah::Senin, 'jam_mulai' => '22:00:00', 'jam_selesai' => '02:00:00']);
    $santri = Santri::factory()->create();

    // Tuesday 01:00, still inside Monday night's overnight window.
    $earlyTuesday = CarbonImmutable::create(2026, 1, 6, 1, 0, 0);

    $absensi = app(RecordAttendanceAction::class)->handle($jadwal, $santri, StudentAttendanceStatus::Hadir, $earlyTuesday);

    expect($absensi->status)->toBe(StudentAttendanceStatus::Hadir);
});

test('recording attendance twice for the same santri, lesson, and day updates instead of duplicating', function () {
    $jadwal = JadwalPelajaran::factory()->create(['hari' => HariSekolah::Senin, 'jam_mulai' => '07:00:00', 'jam_selesai' => '08:30:00']);
    $santri = Santri::factory()->create();
    $at = CarbonImmutable::create(2026, 1, 5, 7, 15, 0);

    app(RecordAttendanceAction::class)->handle($jadwal, $santri, StudentAttendanceStatus::Hadir, $at);
    app(RecordAttendanceAction::class)->handle($jadwal, $santri, StudentAttendanceStatus::Izin, $at);

    expect(Absensi::query()->count())->toBe(1)
        ->and(Absensi::query()->first()->status)->toBe(StudentAttendanceStatus::Izin);
});
