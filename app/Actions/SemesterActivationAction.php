<?php

namespace App\Actions;

use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class SemesterActivationAction
{
    /**
     * Activate the given semester, deactivating ALL other semesters across
     * the entire system. Only one active semester is permitted globally.
     * Locks all semester rows so concurrent toggles can't leave more than
     * one semester active.
     */
    public function handle(Semester $semester): Semester
    {
        DB::transaction(function () use ($semester): void {
            // Lock all semesters globally to prevent concurrent activation
            Semester::query()
                ->lockForUpdate()
                ->get();

            // Deactivate every semester in the system
            Semester::query()
                ->update(['is_aktif' => false]);

            // is_aktif is deliberately excluded from Semester's Fillable list so it
            // can't be mass-assigned outside this Action; forceFill is the sanctioned
            // way for this Action itself to flip it.
            $semester->forceFill(['is_aktif' => true])->save();
        });

        return $semester->fresh();
    }
}
