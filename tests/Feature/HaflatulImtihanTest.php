<?php

use App\Models\HaflatulImtihan;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\User;

test('authenticated admin can record haflatul imtihan payment', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $santri = Santri::factory()->create();
    $semester = Semester::factory()->active()->create();

    $this->actingAs($admin)
        ->get(route('keuangan.haflatul-imtihan'))
        ->assertOk();

    HaflatulImtihan::query()->create([
        'santri_id' => $santri->id,
        'semester_id' => $semester->id,
        'nominal' => 250000,
        'tanggal' => now()->toDateString(),
        'metode_pembayaran' => 'cash',
    ]);

    expect(HaflatulImtihan::query()->where('santri_id', $santri->id)->count())->toBe(1);
});
