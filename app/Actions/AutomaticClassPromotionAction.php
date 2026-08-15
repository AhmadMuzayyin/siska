<?php

namespace App\Actions;

use App\Enums\SantriStatus;
use App\Events\SantriDipromosikan;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class AutomaticClassPromotionAction
{
    /**
     * Process automatic class promotion at the end of the final semester based on grade averages vs KKM.
     *
     * Rules:
     * - Accumulate grade average for each active student.
     * - Compare average grade against KKM (average subject KKM).
     * - If average >= KKM and next class exists: Promoted to the next higher class ID in the same institution.
     * - If average >= KKM and NO next class exists (student is in highest class): Graduated (Status set to 'lulus' / Alumni).
     * - If average < KKM: Retained in the same class.
     *
     * @return array{promoted: int, graduated: int, retained: int}
     */
    public function handle(?Semester $semester = null): array
    {
        $promotedCount = 0;
        $graduatedCount = 0;
        $retainedCount = 0;

        DB::transaction(function () use ($semester, &$promotedCount, &$graduatedCount, &$retainedCount): void {
            $santris = Santri::query()
                ->where('status', 'aktif')
                ->whereNotNull('kelas_id')
                ->with(['kelas.lembaga', 'nilais.mapel'])
                ->get();

            foreach ($santris as $santri) {
                $currentKelas = $santri->kelas;

                if (! $currentKelas) {
                    continue;
                }

                // Filter grades for the specified semester if given, otherwise use all available grades
                $nilais = $semester
                    ? $santri->nilais->filter(fn ($n) => $n->semester_id === $semester->id)
                    : $santri->nilais;

                if ($nilais->isEmpty()) {
                    // Default to retention if no grades recorded
                    $retainedCount++;

                    continue;
                }

                $averageGrade = (float) $nilais->avg('nilai');
                $averageKkm = (float) ($nilais->map(fn ($n) => $n->mapel?->kkm ?? 70)->avg() ?? 70);

                if ($averageGrade >= $averageKkm) {
                    // Find next higher class in the same institution by ID order
                    $nextKelas = Kelas::query()
                        ->where('lembaga_id', $currentKelas->lembaga_id)
                        ->where('id', '>', $currentKelas->id)
                        ->orderBy('id', 'asc')
                        ->first();

                    if ($nextKelas) {
                        $santri->update(['kelas_id' => $nextKelas->id]);
                        SantriDipromosikan::dispatch($santri, $currentKelas, $nextKelas);
                        $promotedCount++;
                    } else {
                        // Already at highest class of this institution -> Graduate student (Lulus / Alumni)
                        $santri->update(['status' => SantriStatus::Lulus]);
                        $graduatedCount++;
                    }
                } else {
                    // Retained in same class
                    $retainedCount++;
                }
            }
        });

        return [
            'promoted' => $promotedCount,
            'graduated' => $graduatedCount,
            'retained' => $retainedCount,
        ];
    }
}
