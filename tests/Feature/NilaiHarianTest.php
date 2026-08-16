<?php

use App\Enums\Gender;
use App\Enums\SantriStatus;
use App\Enums\UserRole;
use App\Livewire\Akademik\KategoriNilaiHarian;
use App\Livewire\Kesantrian\NilaiHarian;
use App\Models\KategoriNilaiHarian as KategoriModel;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\NilaiHarian as NilaiHarianModel;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);

    $this->lembaga = Lembaga::factory()->create(['nama' => 'Madrasah Diniyah']);
    $this->admin = User::factory()->create(['role' => UserRole::Admin, 'lembaga_id' => $this->lembaga->id]);
    $this->semester = Semester::factory()->active()->create();
    $this->kelas = Kelas::factory()->create(['lembaga_id' => $this->lembaga->id]);
});

test('can create dynamic daily grade category with weight', function () {
    $this->actingAs($this->admin);

    Livewire::test(KategoriNilaiHarian::class)
        ->set('nama', 'Sikap & Kesopanan')
        ->set('bobot', 25)
        ->set('is_wajib', true)
        ->set('keterangan', 'Penilaian akhlaq harian santri')
        ->call('save')
        ->assertHasNoErrors();

    $kategori = KategoriModel::query()->where('nama', 'Sikap & Kesopanan')->first();

    expect($kategori)->not->toBeNull()
        ->and($kategori->bobot)->toBe(25)
        ->and($kategori->is_wajib)->toBeTrue();
});

test('can batch enter daily student scores for class and category', function () {
    $kategori = KategoriModel::factory()->create([
        'nama' => 'Kedisiplinan',
        'bobot' => 20,
        'lembaga_id' => $this->lembaga->id,
    ]);

    $santri = Santri::factory()->create([
        'nama_lengkap' => 'Muhammad Zaky',
        'noinduk' => 'NIS-888',
        'status' => SantriStatus::Aktif,
        'jenis_kelamin' => Gender::LakiLaki,
        'telepon_wali' => '08123456789',
        'kelas_id' => $this->kelas->id,
        'lembaga_id' => $this->lembaga->id,
    ]);

    $this->actingAs($this->admin);

    Livewire::test(NilaiHarian::class)
        ->set('kelas_id', $this->kelas->id)
        ->set('kategori_nilai_harian_id', $kategori->id)
        ->set('semester_id', $this->semester->id)
        ->set('tanggal', '2026-08-16')
        ->set('scores.'.$santri->id, [
            'nilai' => 90,
            'catatan' => 'Sangat disiplin mengikuti pengajian',
        ])
        ->call('save')
        ->assertHasNoErrors();

    $scoreRecord = NilaiHarianModel::query()
        ->where('santri_id', $santri->id)
        ->where('kategori_nilai_harian_id', $kategori->id)
        ->first();

    expect($scoreRecord)->not->toBeNull()
        ->and($scoreRecord->nilai)->toBe(90)
        ->and($scoreRecord->catatan)->toBe('Sangat disiplin mengikuti pengajian');
});
