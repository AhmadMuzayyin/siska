<?php

namespace App\Services;

use App\Enums\Predikat;

class PredikatCalculator
{
    /**
     * The single source of truth for nilai-to-predikat thresholds.
     * Ranges are continuous (no gaps): 0-58 E, 59-69 D, 70-79 C, 80-89 B, 90-100 A.
     */
    public static function calculate(int $nilai): Predikat
    {
        return match (true) {
            $nilai >= 90 => Predikat::A,
            $nilai >= 80 => Predikat::B,
            $nilai >= 70 => Predikat::C,
            $nilai >= 59 => Predikat::D,
            default => Predikat::E,
        };
    }
}
