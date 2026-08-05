<?php

use App\Enums\HariSekolah;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Santri;
use App\Models\Semester;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;

beforeEach(function () {
    Config::set('services.rfid.device_key', 'test-device-key');
});

afterEach(function () {
    Date::setTestNow();
});

test('rejects a scan without a valid device key', function () {
    $this->postJson(route('rfid.scan'), ['rfid_uid' => 'ABC123'])
        ->assertStatus(401);
});

test('caches an unrecognized rfid uid for later registration', function () {
    $this->postJson(route('rfid.scan'), ['rfid_uid' => 'UNKNOWN-UID'], ['X-Device-Key' => 'test-device-key'])
        ->assertOk()
        ->assertJson(['status' => 'unregistered']);

    expect(Cache::get('register_rfid_pending_uid'))->toBe('UNKNOWN-UID');
});

test('records santri attendance when the card matches a known rfid_uid within the lesson window', function () {
    // 2026-01-05 is a Monday (Senin).
    Date::setTestNow(CarbonImmutable::create(2026, 1, 5, 7, 15, 0));

    $santri = Santri::factory()->create(['rfid_uid' => 'SANTRI-UID']);
    JadwalPelajaran::factory()->create([
        'kelas_id' => $santri->kelas_id,
        'hari' => HariSekolah::Senin,
        'jam_mulai' => '07:00:00',
        'jam_selesai' => '08:30:00',
    ]);

    $this->postJson(route('rfid.scan'), ['rfid_uid' => 'SANTRI-UID'], ['X-Device-Key' => 'test-device-key'])
        ->assertOk()
        ->assertJson(['status' => 'recorded']);
});

test('records guru attendance when the card matches a known rfid_uid', function () {
    Semester::factory()->active()->create();
    $guru = Guru::factory()->create(['rfid_uid' => 'GURU-UID']);

    $this->postJson(route('rfid.scan'), ['rfid_uid' => 'GURU-UID'], ['X-Device-Key' => 'test-device-key'])
        ->assertOk()
        ->assertJson(['status' => 'recorded']);

    expect(AbsensiGuru::query()->where('guru_id', $guru->id)->exists())->toBeTrue();
});
