<?php

namespace App\Actions;

use App\Enums\StudentAttendanceStatus;
use App\Exceptions\OutsideAttendanceWindowException;
use App\Models\Absensi;
use App\Models\JadwalPelajaran;
use App\Models\Santri;
use Carbon\CarbonImmutable;

class RecordAttendanceAction
{
    /**
     * Record (or correct) a santri's attendance for a scheduled lesson.
     * Used by every entry point (manual Livewire form and RFID scans alike)
     * so the time-window rule and duplicate handling only live in one place.
     */
    public function handle(
        JadwalPelajaran $jadwal,
        Santri $santri,
        StudentAttendanceStatus $status,
        ?CarbonImmutable $at = null,
    ): Absensi {
        $at ??= CarbonImmutable::now();

        if (! $this->isWithinWindow($jadwal, $at)) {
            throw new OutsideAttendanceWindowException;
        }

        // A plain updateOrCreate() would match "tanggal" as a raw string, but
        // the column is stored via a date cast (full Y-m-d H:i:s format under
        // the hood) - whereDate() compares just the date portion so it finds
        // the existing row regardless of the stored format.
        $absensi = Absensi::query()
            ->where('santri_id', $santri->id)
            ->where('jadwal_pelajaran_id', $jadwal->id)
            ->whereDate('tanggal', $at->toDateString())
            ->first();

        if ($absensi !== null) {
            $absensi->update(['status' => $status]);

            return $absensi;
        }

        return Absensi::query()->create([
            'semester_id' => $jadwal->semester_id,
            'santri_id' => $santri->id,
            'jadwal_pelajaran_id' => $jadwal->id,
            'tanggal' => $at->toDateString(),
            'status' => $status,
        ]);
    }

    private function isWithinWindow(JadwalPelajaran $jadwal, CarbonImmutable $at): bool
    {
        $scheduledDay = $jadwal->hari->carbonDayOfWeek();

        if ($at->dayOfWeek === $scheduledDay) {
            [$start, $end] = $this->windowFor($jadwal, $at);

            if ($at->between($start, $end)) {
                return true;
            }
        }

        $yesterday = $at->subDay();

        if ($yesterday->dayOfWeek === $scheduledDay) {
            [$start, $end] = $this->windowFor($jadwal, $yesterday);

            if ($at->between($start, $end)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array{0: CarbonImmutable, 1: CarbonImmutable}
     */
    private function windowFor(JadwalPelajaran $jadwal, CarbonImmutable $referenceDate): array
    {
        $start = CarbonImmutable::parse($referenceDate->toDateString().' '.$jadwal->jam_mulai);
        $end = CarbonImmutable::parse($referenceDate->toDateString().' '.$jadwal->jam_selesai);

        if ($end->lessThanOrEqualTo($start)) {
            $end = $end->addDay();
        }

        return [$start, $end];
    }
}
