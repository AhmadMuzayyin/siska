<?php

use App\Actions\EnrollSantriAction;
use App\Exceptions\KelasPenuhException;
use App\Models\Kelas;
use App\Models\Santri;

test('enrolls a santri when the kelas still has capacity', function () {
    $kelas = Kelas::factory()->create(['kapasitas' => 2]);

    $data = Santri::factory()->make(['kelas_id' => $kelas->id])->toArray();

    $santri = app(EnrollSantriAction::class)->handle($data);

    expect($santri->exists)->toBeTrue()
        ->and($kelas->santris()->count())->toBe(1);
});

test('rejects enrollment once the kelas is at full capacity', function () {
    $kelas = Kelas::factory()->create(['kapasitas' => 1]);
    Santri::factory()->create(['kelas_id' => $kelas->id]);

    $data = Santri::factory()->make(['kelas_id' => $kelas->id])->toArray();

    app(EnrollSantriAction::class)->handle($data);
})->throws(KelasPenuhException::class);
