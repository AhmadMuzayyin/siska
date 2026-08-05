<?php

namespace App\Exceptions;

use RuntimeException;

class DuplicateAttendanceException extends RuntimeException
{
    /**
     * @param  array<int, string>  $duplicateGuruNames
     */
    public function __construct(public readonly array $duplicateGuruNames)
    {
        parent::__construct(
            'Attendance has already been recorded today for: '.implode(', ', $duplicateGuruNames),
        );
    }
}
