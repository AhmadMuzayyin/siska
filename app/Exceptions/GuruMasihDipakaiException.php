<?php

namespace App\Exceptions;

use RuntimeException;

class GuruMasihDipakaiException extends RuntimeException
{
    public function __construct(string $reason)
    {
        parent::__construct($reason);
    }
}
