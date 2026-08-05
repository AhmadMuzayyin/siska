<?php

namespace App\Actions;

use App\Enums\HariSekolah;
use App\Enums\StudentAttendanceStatus;
use App\Enums\TeacherAttendanceStatus;
use App\Exceptions\DuplicateAttendanceException;
use App\Exceptions\OutsideAttendanceWindowException;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Santri;
use App\Services\SemesterService;
use App\Support\RfidScanResult;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;

class RfidScanAction
{
    /**
     * How long an unrecognized card's UID is cached so an admin can attach
     * it to a santri/guru shortly after the physical scan.
     */
    private const REGISTRATION_CACHE_MINUTES = 2;

    public function __construct(
        private readonly RecordAttendanceAction $recordAttendanceAction,
        private readonly RecordTeacherAttendanceAction $recordTeacherAttendanceAction,
        private readonly SemesterService $semesterService,
    ) {}

    /**
     * Single entry point for every RFID reader: tries to match the UID to a
     * santri, then a guru, then falls back to a short-lived registration
     * cache so an admin screen can pick it up. Delegates the actual
     * attendance write to the same actions used by manual entry (no
     * RFID-specific attendance logic).
     */
    public function handle(string $rfidUid): RfidScanResult
    {
        if ($santri = Santri::query()->where('rfid_uid', $rfidUid)->first()) {
            return $this->recordSantriAttendance($santri);
        }

        if ($guru = Guru::query()->where('rfid_uid', $rfidUid)->first()) {
            return $this->recordGuruAttendance($guru);
        }

        Cache::put('register_rfid_pending_uid', $rfidUid, now()->addMinutes(self::REGISTRATION_CACHE_MINUTES));

        return RfidScanResult::unregistered('Card not recognized. UID cached for 2 minutes for registration.');
    }

    private function recordSantriAttendance(Santri $santri): RfidScanResult
    {
        $now = CarbonImmutable::now();
        $jadwal = $this->currentJadwalFor($santri, $now);

        if ($jadwal === null) {
            return RfidScanResult::failed('No active lesson schedule right now for this santri\'s kelas.');
        }

        try {
            $this->recordAttendanceAction->handle($jadwal, $santri, StudentAttendanceStatus::Hadir, $now);
        } catch (OutsideAttendanceWindowException $exception) {
            return RfidScanResult::failed($exception->getMessage());
        }

        return RfidScanResult::recorded("Attendance recorded for {$santri->nama_lengkap}.");
    }

    private function recordGuruAttendance(Guru $guru): RfidScanResult
    {
        $semester = $this->semesterService->current();

        if ($semester === null) {
            return RfidScanResult::failed('No active semester; teacher attendance cannot be recorded.');
        }

        try {
            $this->recordTeacherAttendanceAction->handle($semester, [
                ['guru_id' => $guru->id, 'status' => TeacherAttendanceStatus::Hadir],
            ]);
        } catch (DuplicateAttendanceException $exception) {
            return RfidScanResult::failed($exception->getMessage());
        }

        return RfidScanResult::recorded("Attendance recorded for {$guru->user->name}.");
    }

    private function currentJadwalFor(Santri $santri, CarbonImmutable $now): ?JadwalPelajaran
    {
        $today = HariSekolah::fromCarbonDayOfWeek($now->dayOfWeek);
        $nowTime = $now->format('H:i:s');

        $todaysJadwal = JadwalPelajaran::query()
            ->where('kelas_id', $santri->kelas_id)
            ->where('hari', $today)
            ->get()
            ->first(fn (JadwalPelajaran $jadwal): bool => $jadwal->jam_selesai > $jadwal->jam_mulai
                ? $nowTime >= $jadwal->jam_mulai && $nowTime <= $jadwal->jam_selesai
                : $nowTime >= $jadwal->jam_mulai);

        if ($todaysJadwal !== null) {
            return $todaysJadwal;
        }

        $yesterday = HariSekolah::fromCarbonDayOfWeek($now->subDay()->dayOfWeek);

        return JadwalPelajaran::query()
            ->where('kelas_id', $santri->kelas_id)
            ->where('hari', $yesterday)
            ->get()
            ->first(fn (JadwalPelajaran $jadwal): bool => $jadwal->jam_selesai <= $jadwal->jam_mulai && $nowTime <= $jadwal->jam_selesai);
    }
}
