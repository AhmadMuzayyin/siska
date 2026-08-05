<?php

namespace App\Enums;

enum SantriStatus: string
{
    case PendingApproval = 'pending_approval';
    case Aktif = 'aktif';
    case Lulus = 'lulus';
    case Pindah = 'pindah';
    case Keluar = 'keluar';
}
