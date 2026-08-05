<?php

use App\Enums\UserRole;
use App\Livewire\Akademik\Mapel as MapelComponent;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders the mapel page for an admin', function () {
    $this->actingAs($this->admin)
        ->get(route('akademik.mapel'))
        ->assertOk()
        ->assertSeeLivewire(MapelComponent::class);
});

test('creates a new mapel', function () {
    Livewire::actingAs($this->admin)
        ->test(MapelComponent::class)
        ->set('nama', 'Nahwu')
        ->set('kkm', 70)
        ->call('save')
        ->assertHasNoErrors();

    expect(Mapel::query()->where('nama', 'Nahwu')->first()?->kkm)->toBe(70);
});

test('prevents deleting a mapel that already has nilai recorded', function () {
    $mapel = Mapel::factory()->create();
    Nilai::factory()->create(['mapel_id' => $mapel->id]);

    Livewire::actingAs($this->admin)
        ->test(MapelComponent::class)
        ->call('delete', $mapel->id);

    expect(Mapel::query()->whereKey($mapel->id)->exists())->toBeTrue();
});
