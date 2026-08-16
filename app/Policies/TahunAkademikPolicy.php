<?php

namespace App\Policies;

use App\Models\TahunAkademik;
use App\Models\User;

class TahunAkademikPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru', 'keuangan', 'kepala_madrasah']);
    }

    public function view(User $user, TahunAkademik $tahunAkademik): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru', 'keuangan', 'kepala_madrasah']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function update(User $user, TahunAkademik $tahunAkademik): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function delete(User $user, TahunAkademik $tahunAkademik): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }
}
