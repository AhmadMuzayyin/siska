<?php

use App\Models\Mapel;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\SettingRapor;
use App\Models\User;

test('admin can access setting rapor and print student report card', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $mapel = Mapel::factory()->create(['nama' => 'Fiqih']);
    $santri = Santri::factory()->create();
    $semester = Semester::factory()->active()->create();

    $this->actingAs($admin)
        ->get(route('akademik.setting-rapor'))
        ->assertOk();

    SettingRapor::query()->create([
        'mapel_id' => $mapel->id,
        'deskripsi_a' => 'Sangat menguasai materi Fiqih.',
    ]);

    $this->actingAs($admin)
        ->get(route('akademik.rapor.print', $santri->id))
        ->assertOk()
        ->assertSee($santri->nama_lengkap);
});
