<?php

use App\Enums\UserRole;
use App\Livewire\Konten\Programs;
use App\Models\Lembaga;
use App\Models\Program;
use App\Models\User;
use Livewire\Livewire;

describe('Program Pendidikan Feature', function () {
    beforeEach(function () {
        $this->admin = User::factory()->create(['role' => UserRole::Admin]);
        $this->operator = User::factory()->create(['role' => UserRole::Operator]);
        $this->guru = User::factory()->create(['role' => UserRole::Guru]);
    });

    it('allows admin and operator to access programs CRUD page', function () {
        $this->actingAs($this->admin)
            ->get(route('konten.programs'))
            ->assertStatus(200);

        $this->actingAs($this->operator)
            ->get(route('konten.programs'))
            ->assertStatus(200);
    });

    it('denies non-admin and non-operator users from accessing programs CRUD page', function () {
        $this->actingAs($this->guru)
            ->get(route('konten.programs'))
            ->assertStatus(403);
    });

    it('allows admin to create a new program with dynamic materi unggulan and image url', function () {
        $lembaga = Lembaga::factory()->create();

        Livewire::actingAs($this->admin)
            ->test(Programs::class)
            ->set('nama_program', 'Tahfidzul Qur\'an Intensif')
            ->set('kategori_badge', 'PROGRAM UNGGULAN')
            ->set('lembaga_id', $lembaga->id)
            ->set('deskripsi_singkat', 'Program bimbingan intensif hafalan Al-Qur\'an.')
            ->set('gambar_url', 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=800')
            ->set('icon', 'sparkles')
            ->set('urutan', 1)
            ->set('is_active', true)
            ->set('materi_unggulan', [
                ['judul' => 'Tahfidz Juz 30', 'deskripsi' => 'Setoran harian dan mutabaah'],
                ['judul' => 'Tajwid Bersanad', 'deskripsi' => 'Makharijul huruf'],
            ])
            ->call('save')
            ->assertHasNoErrors();

        expect(Program::where('nama_program', 'Tahfidzul Qur\'an Intensif')->exists())->toBeTrue();

        $program = Program::where('nama_program', 'Tahfidzul Qur\'an Intensif')->first();
        expect($program->kategori_badge)->toBe('PROGRAM UNGGULAN');
        expect($program->materi_unggulan)->toHaveCount(2);
        expect($program->materi_unggulan[0]['judul'])->toBe('Tahfidz Juz 30');
    });

    it('allows admin to edit an existing program', function () {
        $program = Program::factory()->create([
            'nama_program' => 'Program Lama',
            'deskripsi_singkat' => 'Deskripsi lama',
        ]);

        Livewire::actingAs($this->admin)
            ->test(Programs::class)
            ->call('edit', $program->id)
            ->set('nama_program', 'Program Diperbarui')
            ->set('deskripsi_singkat', 'Deskripsi baru')
            ->call('save')
            ->assertHasNoErrors();

        expect($program->fresh()->nama_program)->toBe('Program Diperbarui');
        expect($program->fresh()->deskripsi_singkat)->toBe('Deskripsi baru');
    });

    it('allows admin to toggle active status and delete a program', function () {
        $program = Program::factory()->create(['is_active' => true]);

        Livewire::actingAs($this->admin)
            ->test(Programs::class)
            ->call('toggleActive', $program->id);

        expect($program->fresh()->is_active)->toBeFalse();

        Livewire::actingAs($this->admin)
            ->test(Programs::class)
            ->set('deletingId', $program->id)
            ->call('delete');

        expect(Program::find($program->id))->toBeNull();
    });

    it('renders active programs on public /program route', function () {
        $program = Program::factory()->create([
            'nama_program' => 'Program Unggulan Tilawati',
            'is_active' => true,
        ]);

        $this->get('/program')
            ->assertStatus(200)
            ->assertSee('Program Unggulan Tilawati');
    });
});
