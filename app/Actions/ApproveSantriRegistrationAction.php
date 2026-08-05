<?php

namespace App\Actions;

use App\Enums\SantriStatus;
use App\Exceptions\KelasPenuhException;
use App\Models\Santri;
use Illuminate\Support\Facades\DB;

class ApproveSantriRegistrationAction
{
    /**
     * Approve a publicly submitted santri registration, moving it from
     * PendingApproval to Aktif. Capacity is re-checked here since the kelas
     * may have filled up between the public submission and admin approval.
     */
    public function handle(Santri $santri): Santri
    {
        return DB::transaction(function () use ($santri): Santri {
            $kelas = $santri->kelas;

            if ($kelas->santris()->where('status', SantriStatus::Aktif)->count() >= $kelas->kapasitas) {
                throw new KelasPenuhException($kelas);
            }

            $santri->update(['status' => SantriStatus::Aktif]);

            return $santri->fresh();
        });
    }
}
