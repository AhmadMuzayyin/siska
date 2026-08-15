<?php

namespace App\Livewire\Settings;

use App\Enums\UserRole;
use App\Models\Setting;
use App\Services\SettingService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Appearance settings')]
class Appearance extends Component
{
    public string $landing_theme = 'default';

    public function mount(SettingService $settingService): void
    {
        $setting = $settingService->get();
        $this->landing_theme = (string) ($setting->landing_theme ?: 'default');
    }

    public function updatedLandingTheme(string $value, SettingService $settingService): void
    {
        $this->saveLandingTheme($settingService);
    }

    public function saveLandingTheme(SettingService $settingService): void
    {
        if (auth()->user()?->role !== UserRole::Admin) {
            return;
        }

        $validated = $this->validate([
            'landing_theme' => 'required|string|in:default,pixigon',
        ]);

        $settingService->update($validated);

        Flux::toast(variant: 'success', text: __('Tema landing page berhasil diperbarui.'));
    }

    public function render(): View
    {
        $setting = Setting::query()->first();

        return view('livewire.settings.appearance', [
            'setting' => $setting,
            'isAdmin' => auth()->user()?->role === UserRole::Admin,
        ]);
    }
}
