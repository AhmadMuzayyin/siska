<?php

use App\Enums\UserRole;
use App\Livewire\Admin\Users as UsersComponent;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders the users page for an admin', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.users'))
        ->assertOk()
        ->assertSeeLivewire(UsersComponent::class);
});

test('creates a new user account', function () {
    Livewire::actingAs($this->admin)
        ->test(UsersComponent::class)
        ->set('name', 'Bendahara Satu')
        ->set('email', 'bendahara@siska.test')
        ->set('password', 'password123')
        ->set('role', 'keuangan')
        ->call('save')
        ->assertHasNoErrors();

    $user = User::query()->where('email', 'bendahara@siska.test')->first();
    expect($user->role)->toBe(UserRole::Keuangan);
});

test('cannot change the protected admin account role', function () {
    // $this->admin from beforeEach is the very first user created (id 1),
    // making it the protected admin account. Act as a second admin instead.
    expect($this->admin->id)->toBe(1);
    $actor = User::factory()->create(['role' => UserRole::Admin]);

    Livewire::actingAs($actor)
        ->test(UsersComponent::class)
        ->call('edit', $this->admin->id)
        ->set('role', 'guru')
        ->call('save');

    expect($this->admin->fresh()->role)->toBe(UserRole::Admin);
});

test('cannot delete the protected admin account', function () {
    expect($this->admin->id)->toBe(1);
    $actor = User::factory()->create(['role' => UserRole::Admin]);

    Livewire::actingAs($actor)
        ->test(UsersComponent::class)
        ->call('delete', $this->admin->id);

    expect(User::query()->whereKey($this->admin->id)->exists())->toBeTrue();
});
