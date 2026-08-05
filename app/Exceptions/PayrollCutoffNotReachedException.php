<?php

namespace App\Exceptions;

use Carbon\CarbonImmutable;
use RuntimeException;

class PayrollCutoffNotReachedException extends RuntimeException
{
    public function __construct(CarbonImmutable $cutoffDate)
    {
        parent::__construct("Payroll cannot be generated before the cutoff date ({$cutoffDate->toDateString()}).");
    }
}
