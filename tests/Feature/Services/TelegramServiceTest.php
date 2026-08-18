<?php

use App\Enums\GuruStatus;
use App\Enums\SantriStatus;
use App\Enums\UserRole;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\TahunAkademik;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config()->set('telegram.bots.mybot.token', '123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11');
    config()->set('services.telegram.bot_token', '123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11');
    config()->set('services.telegram.chat_id', '987654321');
});

test('sends new guru registration notification with inline confirmation button', function () {
    Http::fake([
        'https://api.telegram.org/*' => Http::response([
            'ok' => true,
            'result' => [
                'message_id' => 101,
                'chat' => ['id' => 987654321],
                'text' => 'PENDAFTARAN GURU BARU',
            ],
        ], 200),
    ]);

    $user = User::factory()->create([
        'name' => 'Ust. Zaid',
        'email' => 'zaid@gmail.com',
        'role' => UserRole::Guru,
    ]);

    $guru = Guru::factory()->create([
        'user_id' => $user->id,
        'status' => GuruStatus::TidakAktif,
    ]);

    $service = app(TelegramService::class);
    $result = $service->sendNewGuruNotification($user, $guru);

    expect($result)->not->toBeNull();
    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'sendMessage')
            && str_contains($request['text'], 'PENDAFTARAN GURU BARU')
            && str_contains($request['text'], 'Ust. Zaid')
            && isset($request['reply_markup']);
    });
});

test('sends new santri registration notification', function () {
    Http::fake([
        'https://api.telegram.org/*' => Http::response([
            'ok' => true,
            'result' => ['message_id' => 102],
        ], 200),
    ]);

    $santri = Santri::factory()->create([
        'nama_lengkap' => 'Muhammad Ali',
        'status' => SantriStatus::PendingApproval,
        'telepon_wali' => '081234567890',
    ]);

    $service = app(TelegramService::class);
    $result = $service->sendNewSantriNotification($santri);

    expect($result)->not->toBeNull();
    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'sendMessage')
            && str_contains($request['text'], 'PENDAFTARAN CALON SANTRI BARU')
            && str_contains($request['text'], 'Muhammad Ali');
    });
});

test('sends grade input notification when teacher saves grades', function () {
    Http::fake([
        'https://api.telegram.org/*' => Http::response([
            'ok' => true,
            'result' => ['message_id' => 103],
        ], 200),
    ]);

    $guruUser = User::factory()->create([
        'name' => 'Ust. Mahmud',
        'role' => UserRole::Guru,
    ]);

    $tahun = TahunAkademik::factory()->create(['nama' => '2026/2027']);
    $semester = Semester::factory()->for($tahun)->active()->create(['tipe' => 'ganjil']);
    $mapel = Mapel::factory()->create(['nama' => 'Fiqih']);
    $santri = Santri::factory()->create(['nama_lengkap' => 'Ahmad Fulan']);

    $service = app(TelegramService::class);
    $result = $service->sendGradeInputNotification($guruUser, $mapel, $santri, $semester, 95);

    expect($result)->not->toBeNull();
    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'sendMessage')
            && str_contains($request['text'], 'INPUT NILAI SANTRI OLEH GURU')
            && str_contains($request['text'], 'Ust. Mahmud')
            && str_contains($request['text'], 'Fiqih')
            && str_contains($request['text'], '95');
    });
});

test('sends user login notification for non-admin users only', function () {
    Http::fake([
        'https://api.telegram.org/*' => Http::response([
            'ok' => true,
            'result' => ['message_id' => 104],
        ], 200),
    ]);

    $service = app(TelegramService::class);

    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $adminResult = $service->sendUserLoginNotification($admin, '127.0.0.1');
    expect($adminResult)->toBeNull();

    $guru = User::factory()->create([
        'name' => 'Ust. Umar',
        'email' => 'umar@guru.test',
        'role' => UserRole::Guru,
    ]);
    $guruResult = $service->sendUserLoginNotification($guru, '192.168.1.50');
    expect($guruResult)->not->toBeNull();

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'sendMessage')
            && str_contains($request['text'], 'NOTIFIKASI LOGIN PENGGUNA')
            && str_contains($request['text'], 'Ust. Umar')
            && str_contains($request['text'], '192.168.1.50');
    });
});

test('login event automatically triggers SendTelegramLoginNotification listener for guru', function () {
    Http::fake([
        'https://api.telegram.org/*' => Http::response([
            'ok' => true,
            'result' => ['message_id' => 105],
        ], 200),
    ]);

    $guru = User::factory()->create([
        'name' => 'Ust. Fauzan',
        'email' => 'fauzan@guru.test',
        'role' => UserRole::Guru,
    ]);

    event(new Login('web', $guru, false));

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'sendMessage')
            && str_contains($request['text'], 'Ust. Fauzan');
    });
});

test('can answer callback query and edit message text', function () {
    Http::fake([
        'https://api.telegram.org/*' => Http::response([
            'ok' => true,
            'result' => true,
        ], 200),
    ]);

    $service = app(TelegramService::class);

    $answered = $service->answerCallbackQuery('cb_123', 'Berhasil', true);
    expect($answered)->toBeTrue();

    $edited = $service->editMessageText(987654321, 55, 'Teks Baru');
    expect($edited)->not->toBeNull();
});
