<?php

use App\Enums\UserRole;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\User;
use App\Models\WaliKelas;

test('a guru cannot view a santri belonging to another guru\'s kelas', function () {
    $kelasA = Kelas::factory()->create();
    $kelasB = Kelas::factory()->create();

    $guruA = Guru::factory()->create(['user_id' => User::factory()->create(['role' => UserRole::Guru])]);
    $guruB = Guru::factory()->create(['user_id' => User::factory()->create(['role' => UserRole::Guru])]);

    WaliKelas::factory()->create(['kelas_id' => $kelasA->id, 'guru_id' => $guruA->id]);
    WaliKelas::factory()->create(['kelas_id' => $kelasB->id, 'guru_id' => $guruB->id]);

    $santriKelasB = Santri::factory()->create(['kelas_id' => $kelasB->id]);

    expect($guruA->user->can('view', $santriKelasB))->toBeFalse()
        ->and($guruB->user->can('view', $santriKelasB))->toBeTrue();
});

test('admin can view any santri regardless of wali kelas assignment', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $santri = Santri::factory()->create();

    expect($admin->can('view', $santri))->toBeTrue();
});
