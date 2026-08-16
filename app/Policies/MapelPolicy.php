<?php

namespace App\Policies;

use App\Models\Mapel;
use App\Models\User;

class MapelPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru', 'keuangan', 'kepala_madrasah']);
    }

    public function view(User $user, Mapel $mapel): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru', 'keuangan', 'kepala_madrasah']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function update(User $user, Mapel $mapel): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function delete(User $user, Mapel $mapel): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }
}
