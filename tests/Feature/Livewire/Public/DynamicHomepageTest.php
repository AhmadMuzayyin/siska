<?php

use App\Livewire\Public\Home as HomeComponent;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\Santri;
use App\Models\User;
use Livewire\Livewire;

test('public homepage renders database records dynamically', function () {
    $lembaga1 = Lembaga::factory()->create([
        'nama' => 'Madrasah Ibtidaiyah Al-Hikmah',
        'jenjang' => 'MI',
        'kepala_lembaga' => 'Ustadz Ahmad, M.Pd.',
        'is_active' => true,
    ]);

    $lembaga2 = Lembaga::factory()->create([
        'nama' => 'Madrasah Tsanawiyah Al-Hikmah',
        'jenjang' => 'MTS',
        'kepala_lembaga' => 'Ustadz Mansur, S.Pd.',
        'is_active' => true,
    ]);

    $kelas = Kelas::factory()->create(['lembaga_id' => $lembaga1->id]);
    $santri = Santri::factory()->create(['lembaga_id' => $lembaga1->id, 'kelas_id' => $kelas->id, 'status' => 'aktif']);

    $guruUser = User::factory()->create(['name' => 'Ustadz Huda']);
    $guru = Guru::factory()->create(['user_id' => $guruUser->id, 'status' => 'aktif']);

    Livewire::test(HomeComponent::class)
        ->assertSee('Madrasah Ibtidaiyah Al-Hikmah')
        ->assertSee('Madrasah Tsanawiyah Al-Hikmah')
        ->assertSee('Ustadz Ahmad, M.Pd.')
        ->assertSee('Ustadz Mansur, S.Pd.');
});
