<?php

use App\Actions\ApproveSantriRegistrationAction;
use App\Enums\SantriStatus;
use App\Exceptions\KelasPenuhException;
use App\Models\Kelas;
use App\Models\Santri;

test('approves a pending registration and marks it aktif', function () {
    $kelas = Kelas::factory()->create(['kapasitas' => 5]);
    $santri = Santri::factory()->pendingApproval()->create(['kelas_id' => $kelas->id]);

    $approved = app(ApproveSantriRegistrationAction::class)->handle($santri);

    expect($approved->status)->toBe(SantriStatus::Aktif);
});

test('rejects approval if the kelas filled up while the registration was pending', function () {
    $kelas = Kelas::factory()->create(['kapasitas' => 1]);
    Santri::factory()->create(['kelas_id' => $kelas->id, 'status' => SantriStatus::Aktif]);
    $pending = Santri::factory()->pendingApproval()->create(['kelas_id' => $kelas->id]);

    app(ApproveSantriRegistrationAction::class)->handle($pending);
})->throws(KelasPenuhException::class);
