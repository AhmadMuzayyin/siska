<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    private const CACHE_KEY = 'setting.singleton';

    public function get(): Setting
    {
        $setting = Cache::get(self::CACHE_KEY);

        if (! $setting instanceof Setting) {
            Cache::forget(self::CACHE_KEY);
            $setting = Setting::query()->first() ?? Setting::query()->firstOrCreate([], [
                'lembaga' => 'MDTA ARROQY',
                'nsm' => '121235000001',
                'alamat' => 'Jl. Pendidikan No. 123, Kecamatan Kota, Kabupaten Nusantara',
                'email' => 'info@arroqy.test',
                'telepon' => '081234567890',
                'meta_deskripsi' => 'Sistem Informasi Akademik Terpadu MDTA ARROQY.',
                'meta_keyword' => 'SISKA, MDTA ARROQY, Madrasah Diniyah, Al-Qur\'an, Diniyah',
                'payroll_cutoff_day' => 25,
                'fitur_pesan_whatsapp' => true,
                'pesan_whatsapp' => 'Assalamu\'alaikum, ada yang bisa kami bantu mengenai MDTA ARROQY?',
                'is_input_nilai_open' => true,
                'is_ppdb_open' => true,
                'google_maps_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3950.0!2d112.7!3d-7.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMTUnMDAuMCJTIDExMsKwNDInMDAuMCJF!5e0!3m2!1sen!2sid!4v1700000000000',
            ]);
            Cache::forever(self::CACHE_KEY, $setting);
        }

        return $setting;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(array $data): Setting
    {
        Cache::forget(self::CACHE_KEY);
        $setting = Setting::query()->first() ?? $this->get();
        $setting->update($data);

        Cache::forget(self::CACHE_KEY);

        return $setting->fresh();
    }
}
