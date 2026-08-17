<?php

namespace App\Exceptions;

use Carbon\CarbonImmutable;
use RuntimeException;

class PayrollCutoffNotReachedException extends RuntimeException
{
    public function __construct(CarbonImmutable $cutoffDate)
    {
        parent::__construct(__('Perhitungan gaji belum dapat diproses sebelum tanggal cutoff / tutup buku (:date).', ['date' => $cutoffDate->format('d/m/Y')]));
    }
}
