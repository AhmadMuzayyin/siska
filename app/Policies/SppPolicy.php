<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Spp;
use App\Models\User;

class SppPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Keuangan, UserRole::Guru, UserRole::KepalaMadrasah], true);
    }

    public function view(User $user, Spp $spp): bool
    {
        return Spp::query()->visibleTo($user)->whereKey($spp->id)->exists();
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Keuangan], true);
    }

    public function update(User $user, Spp $spp): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Keuangan], true);
    }

    public function delete(User $user, Spp $spp): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Keuangan], true);
    }
}
