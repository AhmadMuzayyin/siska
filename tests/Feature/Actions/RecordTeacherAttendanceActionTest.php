<?php

use App\Actions\RecordTeacherAttendanceAction;
use App\Enums\TeacherAttendanceStatus;
use App\Exceptions\DuplicateAttendanceException;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\Semester;
use Carbon\CarbonImmutable;

test('records attendance for every guru in the batch', function () {
    $semester = Semester::factory()->create();
    $gurus = Guru::factory()->count(3)->create();
    $tanggal = CarbonImmutable::create(2026, 1, 5);

    $entries = $gurus->map(fn (Guru $guru) => ['guru_id' => $guru->id, 'status' => TeacherAttendanceStatus::Hadir])->all();

    app(RecordTeacherAttendanceAction::class)->handle($semester, $entries, $tanggal);

    expect(AbsensiGuru::query()->count())->toBe(3);
});

test('rejects the whole batch and lists names when a guru already has attendance that day', function () {
    $semester = Semester::factory()->create();
    $gurus = Guru::factory()->count(2)->create();
    $tanggal = CarbonImmutable::create(2026, 1, 5);

    AbsensiGuru::factory()->create([
        'guru_id' => $gurus[0]->id,
        'semester_id' => $semester->id,
        'tanggal' => $tanggal->toDateString(),
    ]);

    $entries = $gurus->map(fn (Guru $guru) => ['guru_id' => $guru->id, 'status' => TeacherAttendanceStatus::Hadir])->all();

    try {
        app(RecordTeacherAttendanceAction::class)->handle($semester, $entries, $tanggal);
        $this->fail('Expected DuplicateAttendanceException to be thrown.');
    } catch (DuplicateAttendanceException $exception) {
        expect($exception->duplicateGuruNames)->toContain($gurus[0]->user->name);
    }

    // Nothing else was inserted for the non-duplicate guru either (all-or-nothing).
    expect(AbsensiGuru::query()->count())->toBe(1);
});
