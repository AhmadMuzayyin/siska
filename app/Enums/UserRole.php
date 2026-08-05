<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Keuangan = 'keuangan';
    case Guru = 'guru';
    case KepalaMadrasah = 'kepala_madrasah';
}
