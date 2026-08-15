<?php

namespace App\Livewire\Settings;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Enums\UserRole;
use App\Models\Setting;
use App\Services\SettingService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Pengaturan')]
class Index extends Component
{
    use PasswordValidationRules;
    use ProfileValidationRules;
    use WithFileUploads;

    #[Url(as: 'tab')]
    public string $tab = 'general';

    // 1. General & Lembaga Settings (Admin)
    public string $lembaga = '';

    public string $nsm = '';

    public string $alamat = '';

    public string $email_lembaga = '';

    public string $telepon = '';

    public string $meta_deskripsi = '';

    public string $meta_keyword = '';

    public int $payroll_cutoff_day = 25;

    public bool $fitur_pesan_whatsapp = false;

    public string $pesan_whatsapp = '';

    public string $google_maps_url = '';

    public mixed $logo_upload = null;

    public mixed $favicon_upload = null;

    // 2. Appearance Settings
    public string $landing_theme = 'default';

    // 3. Profile Settings
    public string $name = '';

    public string $email = '';

    // 4. Security Settings
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(SettingService $settingService): void
    {
        $user = Auth::user();
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
        }

        $setting = $settingService->get();
        $this->lembaga = $setting->lembaga ?? '';
        $this->nsm = (string) ($setting->nsm ?? '');
        $this->alamat = $setting->alamat ?? '';
        $this->email_lembaga = $setting->email ?? '';
        $this->telepon = $setting->telepon ?? '';
        $this->meta_deskripsi = (string) ($setting->meta_deskripsi ?? '');
        $this->meta_keyword = (string) ($setting->meta_keyword ?? '');
        $this->payroll_cutoff_day = (int) ($setting->payroll_cutoff_day ?: 25);
        $this->fitur_pesan_whatsapp = (bool) $setting->fitur_pesan_whatsapp;
        $this->pesan_whatsapp = (string) ($setting->pesan_whatsapp ?? '');
        $this->google_maps_url = (string) ($setting->google_maps_url ?? '');
        $this->landing_theme = (string) ($setting->landing_theme ?: 'default');

        // Set default active tab
        if ($user?->role !== UserRole::Admin && in_array($this->tab, ['general', 'appearance'], true)) {
            $this->tab = 'profile';
        }
    }

    public function saveGeneral(SettingService $settingService): void
    {
        if (Auth::user()?->role !== UserRole::Admin) {
            abort(403);
        }

        $validated = $this->validate([
            'lembaga' => 'required|string|max:255',
            'nsm' => 'nullable|string|max:255',
            'alamat' => 'required|string',
            'email_lembaga' => 'required|email',
            'telepon' => 'required|string',
            'meta_deskripsi' => 'nullable|string',
            'meta_keyword' => 'nullable|string',
            'payroll_cutoff_day' => 'required|integer|min:1|max:31',
            'fitur_pesan_whatsapp' => 'boolean',
            'pesan_whatsapp' => 'nullable|string',
            'google_maps_url' => 'nullable|url|max:1000',
            'logo_upload' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'favicon_upload' => 'nullable|file|mimes:png,jpg,jpeg,svg,ico|max:1024',
        ]);

        $setting = $settingService->get();
        $logoPath = $setting->logo;
        $faviconPath = $setting->favicon;

        if ($this->logo_upload) {
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            $logoPath = $this->logo_upload->store('logos', 'public');
            $this->logo_upload = null;
        }

        if ($this->favicon_upload) {
            if ($faviconPath && Storage::disk('public')->exists($faviconPath)) {
                Storage::disk('public')->delete($faviconPath);
            }
            $faviconPath = $this->favicon_upload->store('favicons', 'public');
            $this->favicon_upload = null;
        }

        $settingService->update([
            'lembaga' => $validated['lembaga'],
            'nsm' => $validated['nsm'],
            'alamat' => $validated['alamat'],
            'email' => $validated['email_lembaga'],
            'telepon' => $validated['telepon'],
            'meta_deskripsi' => $validated['meta_deskripsi'],
            'meta_keyword' => $validated['meta_keyword'],
            'payroll_cutoff_day' => $validated['payroll_cutoff_day'],
            'fitur_pesan_whatsapp' => $validated['fitur_pesan_whatsapp'],
            'pesan_whatsapp' => $validated['pesan_whatsapp'],
            'google_maps_url' => $validated['google_maps_url'],
            'logo' => $logoPath,
            'favicon' => $faviconPath,
        ]);

        Flux::toast(variant: 'success', text: __('Pengaturan lembaga & aplikasi berhasil disimpan.'));
    }

    public function removeLogo(SettingService $settingService): void
    {
        if (Auth::user()?->role !== UserRole::Admin) {
            abort(403);
        }

        $setting = $settingService->get();
        if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
            Storage::disk('public')->delete($setting->logo);
        }

        $settingService->update(['logo' => null]);
        $this->logo_upload = null;

        Flux::toast(variant: 'success', text: __('Logo lembaga berhasil dihapus.'));
    }

    public function removeFavicon(SettingService $settingService): void
    {
        if (Auth::user()?->role !== UserRole::Admin) {
            abort(403);
        }

        $setting = $settingService->get();
        if ($setting->favicon && Storage::disk('public')->exists($setting->favicon)) {
            Storage::disk('public')->delete($setting->favicon);
        }

        $settingService->update(['favicon' => null]);
        $this->favicon_upload = null;

        Flux::toast(variant: 'success', text: __('Favicon lembaga berhasil dihapus.'));
    }

    public function updatedLandingTheme(string $value, SettingService $settingService): void
    {
        $this->saveLandingTheme($settingService);
    }

    public function saveLandingTheme(SettingService $settingService): void
    {
        if (Auth::user()?->role !== UserRole::Admin) {
            abort(403);
        }

        $validated = $this->validate([
            'landing_theme' => 'required|string|in:default,pixigon',
        ]);

        $settingService->update($validated);

        Flux::toast(variant: 'success', text: __('Tema website landing page berhasil diperbarui.'));
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Flux::toast(variant: 'success', text: __('Profil berhasil diperbarui.'));
    }

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        Flux::toast(variant: 'success', text: __('Kata sandi berhasil diperbarui.'));
    }

    public function render(): View
    {
        $setting = Setting::query()->first();

        return view('livewire.settings.index', [
            'setting' => $setting,
            'isAdmin' => Auth::user()?->role === UserRole::Admin,
        ]);
    }
}
