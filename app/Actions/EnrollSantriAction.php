<?php

namespace App\Actions;

use App\Exceptions\KelasPenuhException;
use App\Models\Kelas;
use App\Models\Santri;
use Illuminate\Support\Facades\DB;

class EnrollSantriAction
{
    /**
     * @param  array<string, mixed>  $data  validated against Santri::validationRules()
     */
    public function handle(array $data): Santri
    {
        return DB::transaction(function () use ($data): Santri {
            $kelas = Kelas::query()->findOrFail((int) $data['kelas_id']);

            if ($kelas->santris()->count() >= $kelas->kapasitas) {
                throw new KelasPenuhException($kelas);
            }

            return Santri::query()->create($data);
        });
    }
}
