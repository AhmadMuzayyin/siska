<?php

use App\Actions\SemesterActivationAction;
use App\Models\Semester;
use App\Models\TahunAkademik;

test('activating a semester deactivates every other semester in the same tahun akademik', function () {
    $tahunAkademik = TahunAkademik::factory()->create();

    $ganjil = Semester::factory()->for($tahunAkademik)->active()->create(['tipe' => 'ganjil']);
    $genap = Semester::factory()->for($tahunAkademik)->create(['tipe' => 'genap']);

    app(SemesterActivationAction::class)->handle($genap);

    expect($ganjil->fresh()->is_aktif)->toBeFalse()
        ->and($genap->fresh()->is_aktif)->toBeTrue();
});

test('activating a semester deactivates ALL other semesters globally, including those from a different tahun akademik', function () {
    $tahunA = TahunAkademik::factory()->create();
    $tahunB = TahunAkademik::factory()->create();

    $semesterA = Semester::factory()->for($tahunA)->active()->create();
    $semesterB = Semester::factory()->for($tahunB)->create();

    app(SemesterActivationAction::class)->handle($semesterB);

    // semesterA must be deactivated — only one semester can be active globally
    expect($semesterA->fresh()->is_aktif)->toBeFalse()
        ->and($semesterB->fresh()->is_aktif)->toBeTrue();
});

test('toggling activation repeatedly leaves exactly one semester active globally', function () {
    $tahunA = TahunAkademik::factory()->create();
    $tahunB = TahunAkademik::factory()->create();

    $semestersA = Semester::factory()->for($tahunA)->count(2)->create();
    $semestersB = Semester::factory()->for($tahunB)->count(2)->create();

    $allSemesters = $semestersA->merge($semestersB);

    foreach ($allSemesters as $semester) {
        app(SemesterActivationAction::class)->handle($semester);
    }

    // After all toggles, exactly ONE semester must be active across the entire system
    expect(Semester::query()->where('is_aktif', true)->count())->toBe(1);
});
