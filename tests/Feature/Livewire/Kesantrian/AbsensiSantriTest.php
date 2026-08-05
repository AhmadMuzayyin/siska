<?php

use App\Enums\HariSekolah;
use App\Enums\UserRole;
use App\Livewire\Kesantrian\AbsensiSantri;
use App\Models\Absensi;
use App\Models\JadwalPelajaran;
use App\Models\Santri;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

afterEach(function () {
    Date::setTestNow();
});

test('renders the absensi santri page for an admin', function () {
    $this->actingAs($this->admin)
        ->get(route('kesantrian.absensi'))
        ->assertOk()
        ->assertSeeLivewire(AbsensiSantri::class);
});

test('records attendance for a santri on the selected jadwal and date', function () {
    // 2026-01-05 is a Monday (Senin).
    Date::setTestNow(CarbonImmutable::create(2026, 1, 5, 7, 15, 0));

    $jadwal = JadwalPelajaran::factory()->create([
        'hari' => HariSekolah::Senin,
        'jam_mulai' => '07:00:00',
        'jam_selesai' => '08:30:00',
    ]);
    $santri = Santri::factory()->create(['kelas_id' => $jadwal->kelas_id]);

    Livewire::actingAs($this->admin)
        ->test(AbsensiSantri::class)
        ->set('jadwalId', $jadwal->id)
        ->set('tanggal', '2026-01-05')
        ->call('setStatus', $santri->id, 'sakit');

    $absensi = Absensi::query()->where('santri_id', $santri->id)->where('jadwal_pelajaran_id', $jadwal->id)->first();

    expect($absensi)->not->toBeNull()
        ->and($absensi->status->value)->toBe('sakit');
});
