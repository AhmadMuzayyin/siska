<?php

namespace App\Services;

class KalenderAkademikService
{
    /**
     * Check whether grade entry is allowed based solely on admin master toggle.
     */
    public function canInputNilai(?string $date = null, ?int $lembagaId = null): bool
    {
        $setting = app(SettingService::class)->get();

        return (bool) ($setting->is_input_nilai_open ?? true);
    }
}
