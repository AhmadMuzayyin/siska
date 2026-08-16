<?php

use App\Enums\UserRole;
use App\Livewire\Akademik\KalenderAkademik;
use App\Models\KalenderAkademik as KalenderModel;
use App\Models\Semester;
use App\Models\TahunAkademik;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders the kalender akademik component', function () {
    Livewire::actingAs($this->admin)
        ->test(KalenderAkademik::class)
        ->assertStatus(200)
        ->assertSee('Kalender Akademik / Pendidikan');
});

test('shows warning when no active semester is available', function () {
    Semester::query()->update(['is_aktif' => false]);

    Livewire::actingAs($this->admin)
        ->test(KalenderAkademik::class)
        ->assertSee('Semester Aktif Belum Diatur');
});

test('allows admin to create, edit, and delete academic calendar events', function () {
    $tahun = TahunAkademik::factory()->create(['nama' => '2026/2027']);
    $semester = Semester::factory()->for($tahun)->active()->create(['tipe' => 'ganjil']);

    Livewire::actingAs($this->admin)
        ->test(KalenderAkademik::class)
        ->set('judul', 'Munaqasyah Santri')
        ->set('tipe', 'ujian')
        ->set('mulai', '2026-08-20')
        ->set('selesai', '2026-08-22')
        ->set('warna', '#6366f1')
        ->set('ikon', 'academic-cap')
        ->set('deskripsi', 'Ujian munaqasyah hafalan Al-Qur\'an santri')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('kalender_akademiks', [
        'semester_id' => $semester->id,
        'judul' => 'Munaqasyah Santri',
        'tipe' => 'ujian',
    ]);

    $event = KalenderModel::query()->where('judul', 'Munaqasyah Santri')->first();

    Livewire::actingAs($this->admin)
        ->test(KalenderAkademik::class)
        ->call('edit', $event->id)
        ->set('judul', 'Munaqasyah Santri Revisi')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('kalender_akademiks', [
        'id' => $event->id,
        'judul' => 'Munaqasyah Santri Revisi',
    ]);

    Livewire::actingAs($this->admin)
        ->test(KalenderAkademik::class)
        ->call('delete', $event->id);

    $this->assertDatabaseMissing('kalender_akademiks', [
        'id' => $event->id,
    ]);
});
