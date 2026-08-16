<?php

namespace App\Policies;

use App\Models\Nilai;
use App\Models\User;

class NilaiPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru', 'kepala_madrasah', 'santri']);
    }

    public function view(User $user, Nilai $nilai): bool
    {
        if ($user->hasRole('santri')) {
            return $user->santri_id === $nilai->santri_id;
        }

        return Nilai::query()->visibleTo($user)->whereKey($nilai->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru']);
    }

    public function update(User $user, Nilai $nilai): bool
    {
        return $user->hasAnyRole(['admin', 'operator'])
            || ($user->hasRole('guru') && Nilai::query()->visibleTo($user)->whereKey($nilai->id)->exists());
    }

    public function delete(User $user, Nilai $nilai): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }
}
