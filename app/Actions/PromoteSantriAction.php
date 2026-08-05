<?php

namespace App\Actions;

use App\Events\SantriDipromosikan;
use App\Models\Kelas;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PromoteSantriAction
{
    /**
     * @param  Collection<int, Santri>  $santris
     */
    public function handle(Collection $santris, Kelas $kelasTujuan): void
    {
        $santris->load('kelas');

        DB::transaction(function () use ($santris, $kelasTujuan): void {
            foreach ($santris as $santri) {
                $kelasAsal = $santri->kelas;

                $santri->update(['kelas_id' => $kelasTujuan->id]);

                SantriDipromosikan::dispatch($santri, $kelasAsal, $kelasTujuan);
            }
        });
    }
}
