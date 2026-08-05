<?php

namespace App\Actions;

use App\Enums\TeacherAttendanceStatus;
use App\Exceptions\DuplicateAttendanceException;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\Semester;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class RecordTeacherAttendanceAction
{
    /**
     * Record attendance for a batch of gurus on a single day. All-or-nothing:
     * if any guru already has a row for that day, nothing is inserted and a
     * DuplicateAttendanceException listing the offending names is thrown.
     *
     * @param  array<int, array{guru_id: int, status: TeacherAttendanceStatus}>  $entries
     */
    public function handle(Semester $semester, array $entries, ?CarbonImmutable $tanggal = null): void
    {
        $tanggal ??= CarbonImmutable::now();
        $guruIds = array_column($entries, 'guru_id');

        $duplicateGuruIds = AbsensiGuru::query()
            ->whereIn('guru_id', $guruIds)
            ->whereDate('tanggal', $tanggal->toDateString())
            ->pluck('guru_id');

        if ($duplicateGuruIds->isNotEmpty()) {
            $names = Guru::query()
                ->whereIn('id', $duplicateGuruIds)
                ->with('user')
                ->get()
                ->map(fn (Guru $guru): string => $guru->user->name)
                ->all();

            throw new DuplicateAttendanceException($names);
        }

        DB::transaction(function () use ($semester, $entries, $tanggal): void {
            foreach ($entries as $entry) {
                AbsensiGuru::query()->create([
                    'semester_id' => $semester->id,
                    'guru_id' => $entry['guru_id'],
                    'status' => $entry['status'],
                    'tanggal' => $tanggal->toDateString(),
                ]);
            }
        });
    }
}
