<?php

namespace App\Enums;

enum StudentAttendanceStatus: string
{
    case Hadir = 'hadir';
    case Izin = 'izin';
    case Sakit = 'sakit';
    case Alpa = 'alpa';
}
