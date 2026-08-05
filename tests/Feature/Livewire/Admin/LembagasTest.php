<?php

use App\Enums\UserRole;
use App\Livewire\Admin\Lembagas as LembagasComponent;
use App\Models\Lembaga;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => UserRole::Admin]);
});

test('admin can view lembagas page', function () {
    $this->actingAs($this->user)
        ->get(route('admin.lembagas'))
        ->assertOk()
        ->assertSeeLivewire(LembagasComponent::class);
});

test('admin can create a new lembaga', function () {
    Livewire::actingAs($this->user)
        ->test(LembagasComponent::class)
        ->set('nama', 'Madrasah Ibtidaiyah Al-Hikmah')
        ->set('kode', 'mi-alhikmah')
        ->set('jenjang', 'MI')
        ->set('nsm', '121235000001')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('lembagas', [
        'kode' => 'mi-alhikmah',
        'nama' => 'Madrasah Ibtidaiyah Al-Hikmah',
        'jenjang' => 'MI',
    ]);
});

test('admin can update a lembaga', function () {
    $lembaga = Lembaga::factory()->create([
        'kode' => 'mts-test',
        'nama' => 'MTs Lama',
        'jenjang' => 'MTS',
    ]);

    Livewire::actingAs($this->user)
        ->test(LembagasComponent::class)
        ->call('edit', $lembaga->id)
        ->set('nama', 'MTs Al-Hikmah Baru')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('lembagas', [
        'id' => $lembaga->id,
        'nama' => 'MTs Al-Hikmah Baru',
    ]);
});

test('admin can delete unused lembaga', function () {
    $lembaga = Lembaga::factory()->create([
        'kode' => 'ma-unused',
        'nama' => 'MA Unused',
        'jenjang' => 'MA',
    ]);

    Livewire::actingAs($this->user)
        ->test(LembagasComponent::class)
        ->call('delete', $lembaga->id);

    $this->assertDatabaseMissing('lembagas', [
        'id' => $lembaga->id,
    ]);
});
