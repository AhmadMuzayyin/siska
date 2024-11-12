<?php

namespace App\Providers;

use App\Models\Kelas;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $setting = Setting::first();
        View::share('title', $setting->lembaga);
        View::share('meta_deskripsi', $setting->meta_deskripsi);
        View::share('meta_keyword', $setting->meta_keyword);
        View::share('logo', "storage/{$setting->logo}");
        View::share('favicon', "storage/{$setting->favicon}");
        View::share('alamat', $setting->alamat);
        View::share('telepon', $setting->telepon);
        View::share('email', $setting->email);
        View::share('kelas', Kelas::all());
    }
}
