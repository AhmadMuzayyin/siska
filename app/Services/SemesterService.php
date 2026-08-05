<?php

namespace App\Services;

use App\Models\Semester;

class SemesterService
{
    public function current(): ?Semester
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        if ($activeLembagaId) {
            $lembagaSemester = Semester::query()
                ->active()
                ->whereHas('tahunAkademik', fn ($q) => $q->where('lembaga_id', $activeLembagaId))
                ->first();

            if ($lembagaSemester) {
                return $lembagaSemester;
            }
        }

        // Fallback to active semester with global (null) or any active semester
        return Semester::query()
            ->active()
            ->whereHas('tahunAkademik', fn ($q) => $q->whereNull('lembaga_id'))
            ->first() ?? Semester::query()->active()->first();
    }
}
