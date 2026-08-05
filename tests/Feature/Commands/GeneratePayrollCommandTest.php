<?php

use App\Enums\GuruStatus;
use App\Models\GajiGuru;
use App\Models\Guru;
use App\Models\Semester;
use App\Models\Setting;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;

afterEach(function () {
    Date::setTestNow();
});

test('generates payroll for every active guru', function () {
    Date::setTestNow(CarbonImmutable::create(2026, 4, 26));
    Setting::factory()->create(['payroll_cutoff_day' => 25]);
    Semester::factory()->active()->create();

    Guru::factory()->count(2)->create(['status' => GuruStatus::Aktif]);
    Guru::factory()->create(['status' => GuruStatus::TidakAktif]);

    $this->artisan('payroll:generate', ['bulan' => 4, 'tahun' => 2026, '--bisyaroh' => 2_600_000])
        ->assertSuccessful();

    expect(GajiGuru::query()->count())->toBe(2);
});
