<?php

namespace App\Policies;

use App\Models\KalenderAkademik;
use App\Models\User;

class KalenderAkademikPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru', 'keuangan', 'kepala_madrasah', 'santri']);
    }

    public function view(User $user, KalenderAkademik $kalender): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru', 'keuangan', 'kepala_madrasah', 'santri']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function update(User $user, KalenderAkademik $kalender): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function delete(User $user, KalenderAkademik $kalender): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }
}
