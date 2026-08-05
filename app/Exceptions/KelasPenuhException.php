<?php

namespace App\Exceptions;

use App\Models\Kelas;
use RuntimeException;

class KelasPenuhException extends RuntimeException
{
    public function __construct(Kelas $kelas)
    {
        parent::__construct("Kelas \"{$kelas->nama}\" is already at full capacity ({$kelas->kapasitas}).");
    }
}
