<?php

use App\Enums\Gender;
use App\Enums\SantriStatus;
use App\Enums\UserRole;
use App\Livewire\Admin\TopBar;
use App\Models\Contact;
use App\Models\Santri;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders the top bar component for authenticated users', function () {
    Livewire::actingAs($this->admin)
        ->test(TopBar::class)
        ->assertStatus(200)
        ->assertSee('notifications-flyout');
});

test('displays notification count for pending santri and unread messages', function () {
    Santri::factory()->create([
        'nama_lengkap' => 'Calon Santri Baru',
        'status' => SantriStatus::PendingApproval,
        'jenis_kelamin' => Gender::LakiLaki,
        'telepon_wali' => '081234567890',
    ]);

    Contact::factory()->create([
        'name' => 'Wali Santri Penanya',
    ]);

    Livewire::actingAs($this->admin)
        ->test(TopBar::class)
        ->assertSee('Calon Santri Baru')
        ->assertSee('Wali Santri Penanya');
});
