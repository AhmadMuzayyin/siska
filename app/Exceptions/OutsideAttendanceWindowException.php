<?php

namespace App\Exceptions;

use RuntimeException;

class OutsideAttendanceWindowException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Attendance can only be recorded within the scheduled lesson\'s time window.');
    }
}
