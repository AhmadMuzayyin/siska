<?php

use App\Enums\Gender;
use App\Enums\SantriStatus;
use App\Enums\UserRole;
use App\Livewire\Admin\TopBar;
use App\Models\Contact;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\TahunAkademik;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders the top bar component for authenticated users', function () {
    Livewire::actingAs($this->admin)
        ->test(TopBar::class)
        ->assertStatus(200)
        ->assertSee('notifications-flyout');
});

test('displays notification count for pending santri and unread messages', function () {
    $santri = Santri::factory()->create([
        'nama_lengkap' => 'Calon Santri Baru',
        'status' => SantriStatus::PendingApproval,
        'jenis_kelamin' => Gender::LakiLaki,
        'telepon_wali' => '081234567890',
    ]);

    $contact = Contact::factory()->create([
        'name' => 'Wali Santri Penanya',
        'is_dibaca' => false,
    ]);

    Livewire::actingAs($this->admin)
        ->test(TopBar::class)
        ->assertSee('Calon Santri Baru')
        ->assertSee('Wali Santri Penanya')
        ->call('markAsRead', 'contact', $contact->id)
        ->assertDontSee('Wali Santri Penanya')
        ->call('markAllAsRead')
        ->assertDontSee('Calon Santri Baru');
});

test('updates active semester display when semester-changed event is dispatched', function () {
    $tahun = TahunAkademik::factory()->create(['nama' => '2026/2027']);
    $semester = Semester::factory()->for($tahun)->active()->create(['tipe' => 'genap']);

    Livewire::actingAs($this->admin)
        ->test(TopBar::class)
        ->dispatch('semester-changed')
        ->assertSee('2026/2027')
        ->assertSee('Genap');
});
