<?php

use App\Enums\SantriStatus;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\Santri;

test('public registration creates a santri pending admin approval', function () {
    $lembaga = Lembaga::factory()->create();
    $kelas = Kelas::factory()->create(['lembaga_id' => $lembaga->id]);

    $payload = Santri::factory()->make(['lembaga_id' => $lembaga->id, 'kelas_id' => $kelas->id])->toArray();
    unset($payload['status']);

    $response = $this->post(route('santri.register'), $payload);

    $response->assertRedirect()->assertSessionHasNoErrors();
    expect(Santri::query()->where('noinduk', $payload['noinduk'])->first()->status)
        ->toBe(SantriStatus::PendingApproval);
});
