<?php

use App\Enums\GuruStatus;
use App\Enums\UserRole;
use App\Livewire\Kepegawaian\Guru as GuruComponent;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\User;
use App\Models\WaliKelas;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders the guru page for an admin', function () {
    $this->actingAs($this->admin)
        ->get(route('kepegawaian.guru'))
        ->assertOk()
        ->assertSeeLivewire(GuruComponent::class);
});

test('creates a guru together with its user account', function () {
    Livewire::actingAs($this->admin)
        ->test(GuruComponent::class)
        ->set('name', 'Ust. Fulan')
        ->set('email', 'fulan@siska.test')
        ->set('password', 'password123')
        ->set('alamat', 'Jl. Contoh No. 1')
        ->set('whatsapp', '081234567890')
        ->set('gender', 'laki_laki')
        ->set('status', 'aktif')
        ->call('save')
        ->assertHasNoErrors();

    $user = User::query()->where('email', 'fulan@siska.test')->first();
    expect($user)->not->toBeNull()
        ->and($user->role)->toBe(UserRole::Guru)
        ->and(Guru::query()->where('user_id', $user->id)->exists())->toBeTrue();
});

test('rejects an invalid whatsapp number', function () {
    Livewire::actingAs($this->admin)
        ->test(GuruComponent::class)
        ->set('name', 'Ust. Fulan')
        ->set('email', 'fulan2@siska.test')
        ->set('password', 'password123')
        ->set('alamat', 'Jl. Contoh No. 1')
        ->set('whatsapp', 'bukan-nomor')
        ->set('gender', 'laki_laki')
        ->set('status', 'aktif')
        ->call('save')
        ->assertHasErrors(['whatsapp']);
});

test('updates a guru without changing the password when left blank', function () {
    $guru = Guru::factory()->create();
    $originalPassword = $guru->user->password;

    Livewire::actingAs($this->admin)
        ->test(GuruComponent::class)
        ->call('edit', $guru->id)
        ->set('name', 'Nama Baru')
        ->set('password', '')
        ->call('save')
        ->assertHasNoErrors();

    expect($guru->user->fresh()->name)->toBe('Nama Baru')
        ->and($guru->user->fresh()->password)->toBe($originalPassword);
});

test('prevents deleting a guru who is still a wali kelas', function () {
    $guru = Guru::factory()->create();
    $kelas = Kelas::factory()->create();
    WaliKelas::factory()->create(['guru_id' => $guru->id, 'kelas_id' => $kelas->id]);

    Livewire::actingAs($this->admin)
        ->test(GuruComponent::class)
        ->call('delete', $guru->id);

    expect(Guru::query()->whereKey($guru->id)->exists())->toBeTrue();
});

test('prevents deleting a guru who still has a teaching schedule', function () {
    $guru = Guru::factory()->create();
    JadwalPelajaran::factory()->create(['guru_id' => $guru->id]);

    Livewire::actingAs($this->admin)
        ->test(GuruComponent::class)
        ->call('delete', $guru->id);

    expect(Guru::query()->whereKey($guru->id)->exists())->toBeTrue();
});

test('deletes a guru with no dependencies', function () {
    $guru = Guru::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(GuruComponent::class)
        ->call('delete', $guru->id);

    expect(Guru::query()->whereKey($guru->id)->exists())->toBeFalse();
});

test('admin can toggle and activate guru status', function () {
    $guru = Guru::factory()->create(['status' => GuruStatus::TidakAktif]);

    Livewire::actingAs($this->admin)
        ->test(GuruComponent::class)
        ->call('toggleStatus', $guru->id)
        ->assertHasNoErrors();

    expect($guru->fresh()->status)->toBe(GuruStatus::Aktif);

    Livewire::actingAs($this->admin)
        ->test(GuruComponent::class)
        ->call('toggleStatus', $guru->id)
        ->assertHasNoErrors();

    expect($guru->fresh()->status)->toBe(GuruStatus::TidakAktif);
});
