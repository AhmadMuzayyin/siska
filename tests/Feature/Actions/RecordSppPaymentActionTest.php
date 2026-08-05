<?php

use App\Actions\RecordSppPaymentAction;
use App\Exceptions\DuplicatePaymentException;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\Spp;
use Carbon\CarbonImmutable;

test('records a spp payment with bulan and tahun derived from tanggal', function () {
    $santri = Santri::factory()->create();
    $semester = Semester::factory()->create();
    $tanggal = CarbonImmutable::create(2026, 3, 10);

    $spp = app(RecordSppPaymentAction::class)->handle($santri, $semester, 150_000, $tanggal);

    expect($spp->bulan)->toBe(3)
        ->and($spp->tahun)->toBe(2026)
        ->and($spp->is_paid)->toBeTrue();
});

test('rejects a second payment for the same santri, semester, month, and year', function () {
    $santri = Santri::factory()->create();
    $semester = Semester::factory()->create();
    $tanggal = CarbonImmutable::create(2026, 3, 10);

    app(RecordSppPaymentAction::class)->handle($santri, $semester, 150_000, $tanggal);

    expect(fn () => app(RecordSppPaymentAction::class)->handle($santri, $semester, 150_000, $tanggal->addDays(5)))
        ->toThrow(DuplicatePaymentException::class);

    expect(Spp::query()->count())->toBe(1);
});
