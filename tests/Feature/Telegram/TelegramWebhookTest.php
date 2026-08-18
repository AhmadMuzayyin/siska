<?php

use App\Enums\GuruStatus;
use App\Enums\UserRole;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\TahunAkademik;
use App\Models\User;
use App\Models\WaliKelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config()->set('telegram.bots.mybot.token', '123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11');
    config()->set('services.telegram.bot_token', '123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11');
    config()->set('services.telegram.chat_id', '987654321');
});

test('handles /start command from telegram webhook', function () {
    Http::fake([
        'https://api.telegram.org/*' => Http::response(['ok' => true, 'result' => ['message_id' => 1]], 200),
    ]);

    $payload = [
        'update_id' => 10001,
        'message' => [
            'message_id' => 1,
            'from' => ['id' => 987654321, 'first_name' => 'Admin'],
            'chat' => ['id' => 987654321, 'type' => 'private'],
            'date' => time(),
            'text' => '/start',
        ],
    ];

    $response = $this->postJson(route('telegram.webhook'), $payload);
    $response->assertOk()
        ->assertJson(['status' => 'ok']);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'sendMessage')
            && str_contains($request['text'], 'Selamat datang di SISKA Admin Telegram Bot');
    });
});

test('handles /online command listing active logged in users', function () {
    Http::fake([
        'https://api.telegram.org/*' => Http::response(['ok' => true, 'result' => ['message_id' => 2]], 200),
    ]);

    $user = User::factory()->create([
        'name' => 'Ustadz Online',
        'email' => 'online@guru.test',
        'role' => UserRole::Guru,
    ]);

    DB::table('sessions')->insert([
        'id' => 'test_session_id_123',
        'user_id' => $user->id,
        'ip_address' => '10.0.0.1',
        'user_agent' => 'Mozilla/5.0',
        'payload' => 'dummy',
        'last_activity' => Carbon::now()->timestamp,
    ]);

    $payload = [
        'update_id' => 10002,
        'message' => [
            'message_id' => 2,
            'from' => ['id' => 987654321, 'first_name' => 'Admin'],
            'chat' => ['id' => 987654321, 'type' => 'private'],
            'date' => time(),
            'text' => '/online',
        ],
    ];

    $response = $this->postJson(route('telegram.webhook'), $payload);
    $response->assertOk();

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'sendMessage')
            && str_contains($request['text'], 'Daftar Pengguna Online')
            && str_contains($request['text'], 'Ustadz Online')
            && str_contains($request['text'], '10.0.0.1');
    });
});

test('handles /akademik command listing teachers grade submission status', function () {
    Http::fake([
        'https://api.telegram.org/*' => Http::response(['ok' => true, 'result' => ['message_id' => 3]], 200),
    ]);

    $tahun = TahunAkademik::factory()->create(['nama' => '2026/2027']);
    $semester = Semester::factory()->for($tahun)->active()->create(['tipe' => 'ganjil']);

    $guruUser = User::factory()->create(['name' => 'Ust. Subhi', 'role' => UserRole::Guru]);
    $guru = Guru::factory()->create(['user_id' => $guruUser->id, 'status' => GuruStatus::Aktif]);

    $mapel = Mapel::factory()->create();
    $santri = Santri::factory()->create();

    Nilai::factory()->create([
        'semester_id' => $semester->id,
        'mapel_id' => $mapel->id,
        'santri_id' => $santri->id,
        'nilai' => 88,
    ]);

    $payload = [
        'update_id' => 10003,
        'message' => [
            'message_id' => 3,
            'from' => ['id' => 987654321, 'first_name' => 'Admin'],
            'chat' => ['id' => 987654321, 'type' => 'private'],
            'date' => time(),
            'text' => '/akademik',
        ],
    ];

    $response = $this->postJson(route('telegram.webhook'), $payload);
    $response->assertOk();

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'sendMessage')
            && str_contains($request['text'], 'Status Input Nilai Guru')
            && str_contains($request['text'], 'Ust. Subhi');
    });
});

test('handles /staff command listing staff with roles and wali kelas', function () {
    Http::fake([
        'https://api.telegram.org/*' => Http::response(['ok' => true, 'result' => ['message_id' => 4]], 200),
    ]);

    $guruUser = User::factory()->create(['name' => 'Ust. Hasan', 'email' => 'hasan@guru.test', 'role' => UserRole::Guru]);
    $guru = Guru::factory()->create(['user_id' => $guruUser->id]);
    $kelas = Kelas::factory()->create(['nama' => '7A']);
    WaliKelas::factory()->create(['guru_id' => $guru->id, 'kelas_id' => $kelas->id]);

    $payload = [
        'update_id' => 10004,
        'message' => [
            'message_id' => 4,
            'from' => ['id' => 987654321, 'first_name' => 'Admin'],
            'chat' => ['id' => 987654321, 'type' => 'private'],
            'date' => time(),
            'text' => '/staff',
        ],
    ];

    $response = $this->postJson(route('telegram.webhook'), $payload);
    $response->assertOk();

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'sendMessage')
            && str_contains($request['text'], 'Daftar Staf Lembaga')
            && str_contains($request['text'], 'Ust. Hasan')
            && str_contains($request['text'], 'Wali Kelas: 7A');
    });
});

test('handles confirm_guru callback query activating the teacher', function () {
    Http::fake([
        'https://api.telegram.org/*' => Http::response(['ok' => true, 'result' => true], 200),
    ]);

    $user = User::factory()->create(['name' => 'Ust. Calon Guru', 'email' => 'calon@guru.test', 'role' => UserRole::Guru]);
    $guru = Guru::factory()->create(['user_id' => $user->id, 'status' => GuruStatus::TidakAktif, 'notification_read_at' => null]);

    $payload = [
        'update_id' => 10005,
        'callback_query' => [
            'id' => 'cb_query_999',
            'from' => ['id' => 987654321, 'first_name' => 'Admin'],
            'message' => [
                'message_id' => 55,
                'chat' => ['id' => 987654321, 'type' => 'private'],
                'text' => 'PENDAFTARAN GURU BARU',
            ],
            'data' => 'confirm_guru:'.$guru->id,
        ],
    ];

    $response = $this->postJson(route('telegram.webhook'), $payload);
    $response->assertOk()
        ->assertJson(['status' => 'ok']);

    expect($guru->fresh()->status)->toBe(GuruStatus::Aktif);
    expect($guru->fresh()->notification_read_at)->not->toBeNull();

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'answerCallbackQuery')
            || str_contains($request->url(), 'editMessageText');
    });
});
