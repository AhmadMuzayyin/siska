<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Mapel;
use App\Models\User;

class MapelPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::Keuangan, UserRole::KepalaMadrasah], true);
    }

    public function view(User $user, Mapel $mapel): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::Keuangan, UserRole::KepalaMadrasah], true);
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Mapel $mapel): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Mapel $mapel): bool
    {
        return $user->role === UserRole::Admin;
    }
}
