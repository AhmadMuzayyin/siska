<?php

use App\Enums\GuruStatus;
use App\Enums\TeacherAttendanceStatus;
use App\Enums\UserRole;
use App\Livewire\Kepegawaian\GajiGuru as GajiGuruComponent;
use App\Models\AbsensiGuru;
use App\Models\GajiGuru;
use App\Models\Guru;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
    Setting::factory()->create(['payroll_cutoff_day' => 25]);
});

afterEach(function () {
    Date::setTestNow();
});

test('renders the gaji guru page for an admin', function () {
    $this->actingAs($this->admin)
        ->get(route('kepegawaian.gaji'))
        ->assertOk()
        ->assertSeeLivewire(GajiGuruComponent::class);
});

test('generates payroll for a guru after the cutoff date', function () {
    Date::setTestNow(CarbonImmutable::create(2026, 4, 26));

    $semester = Semester::factory()->active()->create();
    $guru = Guru::factory()->create(['status' => GuruStatus::Aktif]);

    AbsensiGuru::factory()->create([
        'guru_id' => $guru->id,
        'semester_id' => $semester->id,
        'status' => TeacherAttendanceStatus::Hadir,
        'tanggal' => CarbonImmutable::create(2026, 4, 5)->toDateString(),
    ]);

    Livewire::actingAs($this->admin)
        ->test(GajiGuruComponent::class)
        ->set('semesterId', $semester->id)
        ->set('bulan', 4)
        ->set('tahun', 2026)
        ->set('bisyaroh', 2_600_000)
        ->call('generate', $guru->id);

    $gaji = GajiGuru::query()->where('guru_id', $guru->id)->first();
    expect($gaji->jumlah_hadir)->toBe(1);
});

test('shows an error when generating payroll before the cutoff date', function () {
    Date::setTestNow(CarbonImmutable::create(2026, 4, 10));

    $semester = Semester::factory()->active()->create();
    $guru = Guru::factory()->create(['status' => GuruStatus::Aktif]);

    Livewire::actingAs($this->admin)
        ->test(GajiGuruComponent::class)
        ->set('semesterId', $semester->id)
        ->set('bulan', 4)
        ->set('tahun', 2026)
        ->call('generate', $guru->id);

    expect(GajiGuru::query()->where('guru_id', $guru->id)->exists())->toBeFalse();
});
