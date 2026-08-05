<?php

use App\Actions\GeneratePayrollAction;
use App\Enums\TeacherAttendanceStatus;
use App\Exceptions\PayrollCutoffNotReachedException;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\Semester;
use App\Models\Setting;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;

beforeEach(function () {
    Setting::factory()->create(['payroll_cutoff_day' => 25]);
});

afterEach(function () {
    Date::setTestNow();
});

test('calculates total gaji proportional to attendance for a normal month', function () {
    Date::setTestNow(CarbonImmutable::create(2026, 4, 26));

    $semester = Semester::factory()->create();
    $guru = Guru::factory()->create();

    foreach (range(1, 20) as $day) {
        AbsensiGuru::factory()->create([
            'guru_id' => $guru->id,
            'semester_id' => $semester->id,
            'status' => TeacherAttendanceStatus::Hadir,
            'tanggal' => CarbonImmutable::create(2026, 4, $day)->toDateString(),
        ]);
    }

    $gaji = app(GeneratePayrollAction::class)->handle($guru, $semester, 4, 2026, 2_600_000);

    expect($gaji->jumlah_hadir)->toBe(20)
        ->and($gaji->total_gaji)->toBe((int) round(2_600_000 / 26 * 20));
});

test('handles february without a cutoff-date overflow bug', function () {
    // February 2026 has 28 days; payroll_cutoff_day=25 stays valid, but this
    // guards against the v1 bug of Carbon::create($year, 2, 30) overflowing into March.
    Date::setTestNow(CarbonImmutable::create(2026, 3, 1));

    $semester = Semester::factory()->create();
    $guru = Guru::factory()->create();

    AbsensiGuru::factory()->create([
        'guru_id' => $guru->id,
        'semester_id' => $semester->id,
        'status' => TeacherAttendanceStatus::Hadir,
        'tanggal' => CarbonImmutable::create(2026, 2, 10)->toDateString(),
    ]);

    $gaji = app(GeneratePayrollAction::class)->handle($guru, $semester, 2, 2026, 2_600_000);

    expect($gaji->jumlah_hadir)->toBe(1);
});

test('rejects payroll generation before the cutoff day has passed', function () {
    Date::setTestNow(CarbonImmutable::create(2026, 4, 10));

    $semester = Semester::factory()->create();
    $guru = Guru::factory()->create();

    app(GeneratePayrollAction::class)->handle($guru, $semester, 4, 2026, 2_600_000);
})->throws(PayrollCutoffNotReachedException::class);
