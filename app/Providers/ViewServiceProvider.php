<?php

namespace App\Providers;

use App\GalleryType;
use App\Models\Gallery;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Setting;
use App\Models\TahunAkademik;
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
        View::share('nsm', $setting?->nsm);
        View::share('title', $setting?->lembaga);
        View::share('meta_deskripsi', $setting?->meta_deskripsi);
        View::share('meta_keyword', $setting?->meta_keyword);
        View::share('logo', "storage/{$setting?->logo}");
        View::share('favicon', "storage/{$setting?->favicon}");
        View::share('alamat', $setting?->alamat);
        View::share('telepon', $setting?->telepon);
        View::share('email', $setting?->email);
        View::share('galery_types', GalleryType::values());
        View::share('kelas', Kelas::all());
        View::share('guru', Guru::all());
        View::share('galleries', Gallery::take(15)->get());
        View::share('tahunAkademik', TahunAkademik::where('is_aktif', true)->first());
    }
}
