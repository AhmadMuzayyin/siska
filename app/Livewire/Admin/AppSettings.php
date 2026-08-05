<?php

namespace App\Livewire\Admin;

use App\Enums\UserRole;
use App\Services\SettingService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Pengaturan Aplikasi')]
class AppSettings extends Component
{
    public string $lembaga = '';

    public string $nsm = '';

    public string $alamat = '';

    public string $email = '';

    public string $telepon = '';

    public string $meta_deskripsi = '';

    public string $meta_keyword = '';

    public int $payroll_cutoff_day = 25;

    public bool $fitur_pesan_whatsapp = false;

    public string $pesan_whatsapp = '';

    public string $google_maps_url = '';

    public function mount(SettingService $settingService): void
    {
        abort_unless(auth()->user()->role === UserRole::Admin, 403);

        $setting = $settingService->get();

        $this->lembaga = $setting->lembaga;
        $this->nsm = (string) $setting->nsm;
        $this->alamat = $setting->alamat;
        $this->email = $setting->email;
        $this->telepon = $setting->telepon;
        $this->meta_deskripsi = (string) $setting->meta_deskripsi;
        $this->meta_keyword = (string) $setting->meta_keyword;
        $this->payroll_cutoff_day = $setting->payroll_cutoff_day;
        $this->fitur_pesan_whatsapp = $setting->fitur_pesan_whatsapp;
        $this->pesan_whatsapp = (string) $setting->pesan_whatsapp;
        $this->google_maps_url = (string) $setting->google_maps_url;
    }

    public function save(SettingService $settingService): void
    {
        abort_unless(auth()->user()->role === UserRole::Admin, 403);

        $data = $this->validate([
            'lembaga' => 'required|string|max:255',
            'nsm' => 'nullable|string|max:255',
            'alamat' => 'required|string',
            'email' => 'required|email',
            'telepon' => 'required|string',
            'meta_deskripsi' => 'nullable|string',
            'meta_keyword' => 'nullable|string',
            'payroll_cutoff_day' => 'required|integer|min:1|max:31',
            'fitur_pesan_whatsapp' => 'boolean',
            'pesan_whatsapp' => 'nullable|string',
            'google_maps_url' => 'nullable|url|max:1000',
        ]);

        $settingService->update($data);

        Flux::toast(variant: 'success', text: __('Pengaturan aplikasi berhasil disimpan.'));
    }

    public function render(): View
    {
        return view('livewire.admin.app-settings');
    }
}
