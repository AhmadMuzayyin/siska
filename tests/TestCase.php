<?php

namespace Tests;

use App\Models\Setting;
use App\Services\ModulePublisherService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Fortify\Features;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $publisher = new ModulePublisherService;
        $modules = ['lembagas', 'akademik', 'jadwal_absensi', 'nilai', 'spp', 'absensi_guru', 'gaji_guru', 'konten'];

        foreach ($modules as $mod) {
            $publisher->publishModuleCode($mod);
        }

        $this->artisan('migrate', ['--force' => true]);

        $setting = Setting::query()->first();
        if ($setting) {
            $setting->update([
                'is_installed' => true,
                'is_multi_lembaga' => true,
                'installed_modules' => $modules,
            ]);
        } else {
            Setting::query()->create([
                'lembaga' => 'Test Institution',
                'alamat' => 'Test Address',
                'email' => 'test@siska.test',
                'telepon' => '08123456789',
                'payroll_cutoff_day' => 25,
                'is_installed' => true,
                'is_multi_lembaga' => true,
                'installed_modules' => $modules,
            ]);
        }
    }

    protected function skipUnlessFortifyHas(string $feature, ?string $message = null): void
    {
        if (! Features::enabled($feature)) {
            $this->markTestSkipped($message ?? "Fortify feature [{$feature}] is not enabled.");
        }
    }
}
