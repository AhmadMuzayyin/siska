<?php

use App\Models\Santri;
use App\Models\Tabungan;
use App\Models\User;

test('authenticated admin can record deposit and withdrawal tabungan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $santri = Santri::factory()->create();

    $this->actingAs($admin)
        ->get(route('keuangan.tabungan'))
        ->assertOk();

    // Deposit 100,000
    Tabungan::query()->create([
        'santri_id' => $santri->id,
        'tipe' => 'setor',
        'nominal' => 100000,
        'saldo_akhir' => 100000,
        'tanggal' => now()->toDateString(),
        'user_id' => $admin->id,
    ]);

    // Withdraw 30,000 -> balance 70,000
    Tabungan::query()->create([
        'santri_id' => $santri->id,
        'tipe' => 'tarik',
        'nominal' => 30000,
        'saldo_akhir' => 70000,
        'tanggal' => now()->toDateString(),
        'user_id' => $admin->id,
    ]);

    expect(Tabungan::query()->where('santri_id', $santri->id)->count())->toBe(2)
        ->and(Tabungan::query()->where('santri_id', $santri->id)->latest('id')->first()->saldo_akhir)->toBe(70000);
});
