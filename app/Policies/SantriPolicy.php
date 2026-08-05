<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Santri;
use App\Models\User;

class SantriPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Keuangan, UserRole::Guru, UserRole::KepalaMadrasah], true);
    }

    public function view(User $user, Santri $santri): bool
    {
        return Santri::query()->visibleTo($user)->whereKey($santri->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Santri $santri): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Santri $santri): bool
    {
        return $user->role === UserRole::Admin;
    }
}
