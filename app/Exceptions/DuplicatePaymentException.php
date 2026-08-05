<?php

namespace App\Exceptions;

use RuntimeException;

class DuplicatePaymentException extends RuntimeException
{
    public function __construct(int $bulan, int $tahun)
    {
        parent::__construct("SPP for {$bulan}/{$tahun} has already been paid for this santri.");
    }
}
