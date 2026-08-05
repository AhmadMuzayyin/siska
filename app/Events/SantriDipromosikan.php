<?php

namespace App\Events;

use App\Models\Kelas;
use App\Models\Santri;
use Illuminate\Foundation\Events\Dispatchable;

class SantriDipromosikan
{
    use Dispatchable;

    public function __construct(
        public readonly Santri $santri,
        public readonly Kelas $kelasAsal,
        public readonly Kelas $kelasTujuan,
    ) {}
}
