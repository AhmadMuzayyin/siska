<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Guru;
use App\Models\User;

class GuruPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::KepalaMadrasah], true);
    }

    public function view(User $user, Guru $guru): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::KepalaMadrasah], true)
            || $guru->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Guru $guru): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Guru $guru): bool
    {
        return $user->role === UserRole::Admin;
    }
}
