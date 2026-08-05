<?php

namespace App\Enums;

use Carbon\CarbonInterface;

enum HariSekolah: string
{
    case Senin = 'senin';
    case Selasa = 'selasa';
    case Rabu = 'rabu';
    case Kamis = 'kamis';
    case Jumat = 'jumat';
    case Sabtu = 'sabtu';
    case Minggu = 'minggu';

    /**
     * Map to Carbon's day-of-week integer (0 = Sunday ... 6 = Saturday).
     */
    public function carbonDayOfWeek(): int
    {
        return match ($this) {
            self::Minggu => CarbonInterface::SUNDAY,
            self::Senin => CarbonInterface::MONDAY,
            self::Selasa => CarbonInterface::TUESDAY,
            self::Rabu => CarbonInterface::WEDNESDAY,
            self::Kamis => CarbonInterface::THURSDAY,
            self::Jumat => CarbonInterface::FRIDAY,
            self::Sabtu => CarbonInterface::SATURDAY,
        };
    }

    /**
     * Reverse of carbonDayOfWeek(): map Carbon's day-of-week integer back to a case.
     */
    public static function fromCarbonDayOfWeek(int $dayOfWeek): self
    {
        return match ($dayOfWeek) {
            CarbonInterface::SUNDAY => self::Minggu,
            CarbonInterface::MONDAY => self::Senin,
            CarbonInterface::TUESDAY => self::Selasa,
            CarbonInterface::WEDNESDAY => self::Rabu,
            CarbonInterface::THURSDAY => self::Kamis,
            CarbonInterface::FRIDAY => self::Jumat,
            CarbonInterface::SATURDAY => self::Sabtu,
            default => throw new \ValueError("Invalid Carbon day-of-week: {$dayOfWeek}"),
        };
    }
}
