<?php

namespace App\Telegram\Commands;

use App\Enums\GuruStatus;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Nilai;
use App\Services\SemesterService;
use Telegram\Bot\Commands\Command;

class AkademikCommand extends Command
{
    protected string $name = 'akademik';

    protected string $description = 'Listing guru yang sudah input nilai dan yang belum input nilai';

    public function handle(): void
    {
        $activeSemester = app(SemesterService::class)->current();
        $semesterName = $activeSemester ? "{$activeSemester->tahunAkademik?->nama} (".ucfirst($activeSemester->tipe?->value ?? '').')' : 'Semester Aktif';

        $gurus = Guru::query()
            ->with('user')
            ->where('status', GuruStatus::Aktif)
            ->get();

        if ($gurus->isEmpty()) {
            $this->replyWithMessage([
                'text' => "📚 <b>Status Input Nilai Guru ({$semesterName})</b>\n\n<i>Belum ada data guru aktif dalam sistem.</i>",
                'parse_mode' => 'HTML',
            ]);

            return;
        }

        $lines = [];
        $count = 1;
        foreach ($gurus as $guru) {
            $guruName = $guru->user?->name ?? "Guru #{$guru->id}";

            // Check mapels taught by guru in current semester
            $mapelIds = JadwalPelajaran::query()
                ->when($activeSemester, fn ($q) => $q->where('semester_id', $activeSemester->id))
                ->where('guru_id', $guru->id)
                ->pluck('mapel_id')
                ->unique();

            $hasInput = false;
            if ($activeSemester && $mapelIds->isNotEmpty()) {
                $hasInput = Nilai::query()
                    ->where('semester_id', $activeSemester->id)
                    ->whereIn('mapel_id', $mapelIds)
                    ->exists();
            } elseif ($activeSemester) {
                $hasInput = Nilai::query()
                    ->where('semester_id', $activeSemester->id)
                    ->exists();
            }

            $statusBadge = $hasInput ? '✅ Sudah Input' : '⏳ Belum Input';
            $lines[] = "{$count}. {$guruName} - {$statusBadge}";
            $count++;
        }

        $text = "📚 <b>Status Input Nilai Guru</b>\n<i>Semester: {$semesterName}</i>\n\n".implode("\n", $lines);

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
