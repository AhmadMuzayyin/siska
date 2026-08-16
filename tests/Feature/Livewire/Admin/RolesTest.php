<?php

use App\Enums\UserRole;
use App\Livewire\Admin\Roles;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders roles management component for admin', function () {
    Livewire::actingAs($this->admin)
        ->test(Roles::class)
        ->assertStatus(200)
        ->assertSee('Manajemen Peran & Izin')
        ->assertSee('admin')
        ->assertSee('operator')
        ->assertSee('guru');
});

test('can create a new custom role with permissions', function () {
    Livewire::actingAs($this->admin)
        ->test(Roles::class)
        ->set('name', 'bendahara_lembaga')
        ->set('selectedPermissions', ['manage-spp', 'manage-tabungan'])
        ->call('save')
        ->assertHasNoErrors();

    $role = Role::query()->where('name', 'bendahara_lembaga')->first();

    expect($role)->not->toBeNull()
        ->and($role->hasPermissionTo('manage-spp'))->toBeTrue()
        ->and($role->hasPermissionTo('manage-tabungan'))->toBeTrue();
});

test('can edit an existing role permissions', function () {
    $role = Role::create(['name' => 'pengawas_ujian', 'guard_name' => 'web']);

    Livewire::actingAs($this->admin)
        ->test(Roles::class)
        ->call('edit', $role->id)
        ->set('selectedPermissions', ['view-nilai', 'print-rapor'])
        ->call('save')
        ->assertHasNoErrors();

    $role->refresh();

    expect($role->hasPermissionTo('view-nilai'))->toBeTrue()
        ->and($role->hasPermissionTo('print-rapor'))->toBeTrue();
});

test('cannot delete protected admin role', function () {
    $adminRole = Role::query()->where('name', 'admin')->first();

    Livewire::actingAs($this->admin)
        ->test(Roles::class)
        ->call('delete', $adminRole->id);

    expect(Role::query()->where('name', 'admin')->exists())->toBeTrue();
});
