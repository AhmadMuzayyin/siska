<?php

namespace App\Policies;

use App\Models\Santri;
use App\Models\User;

class SantriPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'keuangan', 'guru', 'kepala_madrasah', 'santri']);
    }

    public function view(User $user, Santri $santri): bool
    {
        if ($user->hasRole('santri')) {
            return $user->santri_id === $santri->id;
        }

        return Santri::query()->visibleTo($user)->whereKey($santri->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function update(User $user, Santri $santri): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function delete(User $user, Santri $santri): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }
}
