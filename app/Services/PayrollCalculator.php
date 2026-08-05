<?php

namespace App\Services;

use App\Enums\TeacherAttendanceStatus;
use App\Exceptions\PayrollCutoffNotReachedException;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\Semester;
use App\Support\PayrollResult;
use Carbon\CarbonImmutable;

class PayrollCalculator
{
    /**
     * Standard working days used to prorate bisyaroh per day attended.
     */
    private const WORKING_DAYS_PER_MONTH = 26;

    public function __construct(
        private readonly SettingService $settingService,
    ) {}

    public function calculate(Guru $guru, Semester $semester, int $bulan, int $tahun, int $bisyaroh): PayrollResult
    {
        $this->assertCutoffReached($bulan, $tahun);

        $jumlahHadir = AbsensiGuru::query()
            ->where('guru_id', $guru->id)
            ->where('semester_id', $semester->id)
            ->where('status', TeacherAttendanceStatus::Hadir)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->count();

        $totalGaji = (int) round($bisyaroh / self::WORKING_DAYS_PER_MONTH * $jumlahHadir);

        return new PayrollResult($jumlahHadir, $totalGaji);
    }

    private function assertCutoffReached(int $bulan, int $tahun): void
    {
        $firstOfMonth = CarbonImmutable::create($tahun, $bulan, 1);
        $cutoffDay = min($this->settingService->get()->payroll_cutoff_day, $firstOfMonth->daysInMonth);
        $cutoffDate = $firstOfMonth->setDay($cutoffDay)->endOfDay();

        if (CarbonImmutable::now()->lessThan($cutoffDate)) {
            throw new PayrollCutoffNotReachedException($cutoffDate);
        }
    }
}
