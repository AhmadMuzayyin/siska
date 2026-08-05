<?php

namespace App\Actions;

use App\Exceptions\DuplicatePaymentException;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\Spp;
use Carbon\CarbonImmutable;
use Illuminate\Database\QueryException;

class RecordSppPaymentAction
{
    public function handle(Santri $santri, Semester $semester, int $nominal, ?CarbonImmutable $tanggal = null): Spp
    {
        $tanggal ??= CarbonImmutable::now();
        $bulan = (int) $tanggal->format('n');
        $tahun = (int) $tanggal->format('Y');

        if ($this->alreadyPaid($santri, $semester, $bulan, $tahun)) {
            throw new DuplicatePaymentException($bulan, $tahun);
        }

        try {
            return Spp::query()->create([
                'semester_id' => $semester->id,
                'santri_id' => $santri->id,
                'tanggal' => $tanggal->toDateString(),
                'nominal' => $nominal,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'is_paid' => true,
                'paid_at' => $tanggal,
            ]);
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23000') {
                throw new DuplicatePaymentException($bulan, $tahun);
            }

            throw $exception;
        }
    }

    private function alreadyPaid(Santri $santri, Semester $semester, int $bulan, int $tahun): bool
    {
        return Spp::query()
            ->where('santri_id', $santri->id)
            ->where('semester_id', $semester->id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->exists();
    }
}
