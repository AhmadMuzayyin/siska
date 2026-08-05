<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Lembaga;
use App\Models\User;

class LembagaPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::Keuangan, UserRole::KepalaMadrasah], true);
    }

    public function view(User $user, Lembaga $lembaga): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::Keuangan, UserRole::KepalaMadrasah], true);
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Lembaga $lembaga): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Lembaga $lembaga): bool
    {
        return $user->role === UserRole::Admin;
    }
}
