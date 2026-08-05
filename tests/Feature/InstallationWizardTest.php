<?php

use App\Livewire\Admin\InstallWizard;
use App\Models\Setting;
use App\Models\User;
use App\Services\DatabaseConfigurator;
use Livewire\Livewire;

beforeEach(function () {
    // Reset setting installation state for test
    $setting = Setting::query()->first();
    if (! $setting) {
        Setting::query()->create([
            'lembaga' => 'Test Pesantren',
            'alamat' => 'Jl. Test',
            'email' => 'test@siska.test',
            'telepon' => '0812345678',
            'is_installed' => false,
            'is_multi_lembaga' => false,
            'installed_modules' => [],
        ]);
    } else {
        $setting->update([
            'is_installed' => false,
            'is_multi_lembaga' => false,
            'installed_modules' => [],
        ]);
    }
});

test('uninstalled app redirects requests to wizard installer route', function () {
    $this->get(route('install.wizard'))
        ->assertOk();
});

test('can test database connection using DatabaseConfigurator', function () {
    $configurator = new DatabaseConfigurator;

    // Test with active sqlite / mysql config
    $driver = config('database.default');
    $config = config("database.connections.{$driver}");

    $result = $configurator->testConnection([
        'host' => $config['host'] ?? '127.0.0.1',
        'port' => (string) ($config['port'] ?? '3306'),
        'database' => $config['database'] ?? 'siska',
        'username' => $config['username'] ?? 'root',
        'password' => $config['password'] ?? '',
    ]);

    expect($result)->toBeBool();
});

test('DatabaseConfigurator updates .env with sqlite absolute path', function () {
    $configurator = new DatabaseConfigurator;
    $configurator->saveToEnv(['driver' => 'sqlite']);

    $envContent = file_get_contents(base_path('.env'));
    expect($envContent)->toContain('DB_CONNECTION=sqlite')
        ->and($envContent)->toContain('DB_DATABASE='.database_path('database.sqlite'));
});

test('selecting gaji_guru module automatically selects absensi_guru module', function () {
    Livewire::test(InstallWizard::class)
        ->set('selected_modules', ['akademik'])
        ->set('selected_modules', ['akademik', 'gaji_guru'])
        ->assertSet('selected_modules', ['akademik', 'gaji_guru', 'absensi_guru']);
});

test('installer wizard step-by-step flow creates super admin and publishes selected modules', function () {
    Livewire::test(InstallWizard::class)
        ->set('db_tested_success', true)
        ->call('saveStep0')
        ->assertSet('step', 1)
        ->set('admin_name', 'Super Admin Test')
        ->set('admin_email', 'admin_wizard@siska.test')
        ->set('admin_password', 'password123')
        ->call('saveStep1')
        ->assertSet('step', 2)
        ->set('is_multi_lembaga', true)
        ->call('saveStep2')
        ->assertSet('step', 3)
        ->set('selected_modules', ['akademik', 'spp'])
        ->call('saveStep3')
        ->assertSet('step', 4)
        ->set('seed_demo_data', true)
        ->call('finishInstallation')
        ->assertRedirect(route('dashboard'));

    expect(User::query()->where('email', 'admin_wizard@siska.test')->exists())->toBeTrue();

    $setting = Setting::query()->first();
    expect($setting->is_installed)->toBeTrue();
    expect($setting->is_multi_lembaga)->toBeTrue();
    expect($setting->installed_modules)->toContain('akademik');
    expect($setting->installed_modules)->toContain('spp');
});

test('app reset install command sets installation status back to uninstalled', function () {
    $this->artisan('app:reset-install')
        ->assertExitCode(0);

    $setting = Setting::query()->first();
    expect($setting->is_installed)->toBeFalse();
});
