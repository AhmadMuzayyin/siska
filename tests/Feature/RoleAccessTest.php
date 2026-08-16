<?php

use App\Enums\Gender;
use App\Enums\SantriStatus;
use App\Enums\UserRole;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Models\Lembaga;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\User;
use App\Services\LembagaService;
use Livewire\Livewire;

beforeEach(function () {
    $this->lembagaA = Lembaga::factory()->create(['nama' => 'TPQ Al-Hikmah Unit A']);
    $this->lembagaB = Lembaga::factory()->create(['nama' => 'Madin Al-Hikmah Unit B']);

    $this->operatorA = User::factory()->create([
        'role' => UserRole::Operator,
        'lembaga_id' => $this->lembagaA->id,
    ]);

    $this->admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
});

test('operator role active lembaga is automatically locked to their assigned institution', function () {
    $this->actingAs($this->operatorA);

    $lembagaId = app(LembagaService::class)->getActiveLembagaId();

    expect($lembagaId)->toBe($this->lembagaA->id);
});

test('santri can log in using student noinduk as username and initial password', function () {
    $santri = Santri::factory()->create([
        'noinduk' => 'NIS-2026-001',
        'nama_lengkap' => 'Ahmad Santri',
        'status' => SantriStatus::Aktif,
        'jenis_kelamin' => Gender::LakiLaki,
        'telepon_wali' => '08123456789',
        'lembaga_id' => $this->lembagaA->id,
    ]);

    Livewire::test(Login::class)
        ->set('email', 'NIS-2026-001')
        ->set('password', 'NIS-2026-001')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $user = User::query()->where('santri_id', $santri->id)->first();

    expect($user)->not->toBeNull()
        ->and($user->role)->toBe(UserRole::Santri)
        ->and($user->email)->toBe('NIS-2026-001');

    $this->assertAuthenticatedAs($user);
});

test('santri role dashboard displays student profile and read-only grades', function () {
    $santri = Santri::factory()->create([
        'noinduk' => 'NIS-2026-002',
        'nama_lengkap' => 'Fatimah Az-Zahra',
        'status' => SantriStatus::Aktif,
        'jenis_kelamin' => Gender::Perempuan,
        'telepon_wali' => '08123456780',
        'lembaga_id' => $this->lembagaA->id,
    ]);

    $santriUser = User::factory()->create([
        'name' => $santri->nama_lengkap,
        'email' => $santri->noinduk,
        'role' => UserRole::Santri,
        'santri_id' => $santri->id,
        'lembaga_id' => $santri->lembaga_id,
    ]);

    Nilai::factory()->create([
        'santri_id' => $santri->id,
        'nilai' => 95,
        'keterangan' => 'Sangat Memuaskan',
    ]);

    Livewire::actingAs($santriUser)
        ->test(Dashboard::class)
        ->assertStatus(200)
        ->assertSee('Portal Santri & Wali')
        ->assertSee('Fatimah Az-Zahra')
        ->assertSee('NIS-2026-002')
        ->assertSee('Nilai Mata Pelajaran (Read-Only)')
        ->assertSee('Sangat Memuaskan');
});
