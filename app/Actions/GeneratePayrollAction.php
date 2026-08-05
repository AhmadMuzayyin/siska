<?php

namespace App\Actions;

use App\Models\GajiGuru;
use App\Models\Guru;
use App\Models\Semester;
use App\Services\PayrollCalculator;

class GeneratePayrollAction
{
    public function __construct(
        private readonly PayrollCalculator $calculator,
    ) {}

    public function handle(Guru $guru, Semester $semester, int $bulan, int $tahun, int $bisyaroh): GajiGuru
    {
        $result = $this->calculator->calculate($guru, $semester, $bulan, $tahun, $bisyaroh);

        return GajiGuru::query()->updateOrCreate(
            [
                'guru_id' => $guru->id,
                'semester_id' => $semester->id,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ],
            [
                'bisyaroh' => $bisyaroh,
                'jumlah_hadir' => $result->jumlahHadir,
                'total_gaji' => $result->totalGaji,
            ],
        );
    }
}
