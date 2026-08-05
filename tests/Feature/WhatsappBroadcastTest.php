<?php

use App\Enums\UserRole;
use App\Livewire\Admin\WhatsappBroadcast;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\User;
use App\Services\FonnteService;
use Livewire\Livewire;

test('admin can view whatsapp broadcast page', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->get(route('admin.whatsapp'))
        ->assertOk()
        ->assertSee('WhatsApp Broadcast');
});

test('non-admin user cannot view whatsapp broadcast page', function () {
    $user = User::factory()->create(['role' => UserRole::Guru]);

    $this->actingAs($user)
        ->get(route('admin.whatsapp'))
        ->assertForbidden();
});

test('fonnte service replaces placeholders correctly', function () {
    $fonnteService = new FonnteService;

    $template = "Assalamu'alaikum {{ nama }}, kelas {{ kelas }} di {{ lembaga }} - {{ tanggal }}";

    $data = [
        'nama' => 'Ahmad',
        'kelas' => 'Jilid 1',
        'lembaga' => 'TPQ Al-Hikmah',
        'tanggal' => '28 Juli 2026',
    ];

    $result = $fonnteService->replacePlaceholders($template, $data);

    expect($result)->toBe("Assalamu'alaikum Ahmad, kelas Jilid 1 di TPQ Al-Hikmah - 28 Juli 2026");
});

test('admin can prepare whatsapp broadcast payloads', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $kelas = Kelas::factory()->create(['nama' => 'Jilid 2']);
    Santri::factory()->create([
        'nama_lengkap' => 'Budi Santoso',
        'telepon_wali' => '081234567890',
        'kelas_id' => $kelas->id,
    ]);

    Livewire::actingAs($admin)
        ->test(WhatsappBroadcast::class)
        ->set('targetCategory', 'semua_santri')
        ->set('messageTemplate', 'Halo {{ nama }} dari {{ kelas }}')
        ->call('prepareBroadcast')
        ->assertHasNoErrors()
        ->assertSet('isReadyToSend', true);
});
