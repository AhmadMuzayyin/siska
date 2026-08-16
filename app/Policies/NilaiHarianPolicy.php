<?php

namespace App\Policies;

use App\Models\NilaiHarian;
use App\Models\User;

class NilaiHarianPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru', 'kepala_madrasah', 'santri']);
    }

    public function view(User $user, NilaiHarian $nilaiHarian): bool
    {
        if ($user->hasRole('santri')) {
            return $user->santri_id === $nilaiHarian->santri_id;
        }

        return NilaiHarian::query()->visibleTo($user)->whereKey($nilaiHarian->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru']);
    }

    public function update(User $user, NilaiHarian $nilaiHarian): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru']);
    }

    public function delete(User $user, NilaiHarian $nilaiHarian): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }
}
