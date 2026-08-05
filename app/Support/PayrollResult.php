<?php

namespace App\Support;

readonly class PayrollResult
{
    public function __construct(
        public int $jumlahHadir,
        public int $totalGaji,
    ) {}
}
