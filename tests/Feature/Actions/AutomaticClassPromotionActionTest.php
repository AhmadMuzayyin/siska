<?php

use App\Actions\AutomaticClassPromotionAction;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\TahunAkademik;

test('promotes student to next class if average grade meets KKM and retains student if below KKM', function () {
    $lembaga = Lembaga::factory()->create();
    $kelas1 = Kelas::factory()->for($lembaga)->create(['nama' => 'Kelas 1']);
    $kelas2 = Kelas::factory()->for($lembaga)->create(['nama' => 'Kelas 2']);

    $tahun = TahunAkademik::factory()->for($lembaga)->create();
    $semester = Semester::factory()->for($tahun)->create(['tipe' => 'genap']);
    $mapel = Mapel::factory()->for($lembaga)->create(['kkm' => 70]);

    // Student A meets KKM (grade 80 >= 70) -> Should promote to Kelas 2
    $santriA = Santri::factory()->create(['kelas_id' => $kelas1->id, 'status' => 'aktif']);
    Nilai::factory()->create([
        'santri_id' => $santriA->id,
        'semester_id' => $semester->id,
        'mapel_id' => $mapel->id,
        'nilai' => 80,
    ]);

    // Student B fails KKM (grade 50 < 70) -> Should remain in Kelas 1
    $santriB = Santri::factory()->create(['kelas_id' => $kelas1->id, 'status' => 'aktif']);
    Nilai::factory()->create([
        'santri_id' => $santriB->id,
        'semester_id' => $semester->id,
        'mapel_id' => $mapel->id,
        'nilai' => 50,
    ]);

    $action = app(AutomaticClassPromotionAction::class);
    $result = $action->handle($semester);

    expect($result['promoted'])->toBe(1)
        ->and($result['retained'])->toBe(1)
        ->and($santriA->fresh()->kelas_id)->toBe($kelas2->id)
        ->and($santriB->fresh()->kelas_id)->toBe($kelas1->id);
});

test('graduates student (status lulus) if student is in highest class and meets KKM', function () {
    $lembaga = Lembaga::factory()->create();
    $kelasTop = Kelas::factory()->for($lembaga)->create(['nama' => 'Kelas Akhir 6']);

    $tahun = TahunAkademik::factory()->for($lembaga)->create();
    $semester = Semester::factory()->for($tahun)->create(['tipe' => 'genap']);
    $mapel = Mapel::factory()->for($lembaga)->create(['kkm' => 70]);

    $santriTop = Santri::factory()->create(['kelas_id' => $kelasTop->id, 'status' => 'aktif']);
    Nilai::factory()->create([
        'santri_id' => $santriTop->id,
        'semester_id' => $semester->id,
        'mapel_id' => $mapel->id,
        'nilai' => 85,
    ]);

    $action = app(AutomaticClassPromotionAction::class);
    $result = $action->handle($semester);

    expect($result['graduated'])->toBe(1)
        ->and($santriTop->fresh()->status->value)->toBe('lulus');
});
