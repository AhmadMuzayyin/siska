<?php

namespace App\Policies;

use App\Models\Spp;
use App\Models\User;

class SppPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'keuangan', 'guru', 'kepala_madrasah', 'santri']);
    }

    public function view(User $user, Spp $spp): bool
    {
        if ($user->hasRole('santri')) {
            return $user->santri_id === $spp->santri_id;
        }

        return Spp::query()->visibleTo($user)->whereKey($spp->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'keuangan']);
    }

    public function update(User $user, Spp $spp): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'keuangan']);
    }

    public function delete(User $user, Spp $spp): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'keuangan']);
    }
}
