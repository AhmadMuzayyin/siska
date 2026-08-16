<?php

namespace App\Policies;

use App\Models\Guru;
use App\Models\User;

class GuruPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'kepala_madrasah', 'guru']);
    }

    public function view(User $user, Guru $guru): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'kepala_madrasah'])
            || $guru->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function update(User $user, Guru $guru): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function delete(User $user, Guru $guru): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }
}
