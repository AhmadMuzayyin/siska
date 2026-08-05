<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Nilai;
use App\Models\User;

class NilaiPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::KepalaMadrasah], true);
    }

    public function view(User $user, Nilai $nilai): bool
    {
        return Nilai::query()->visibleTo($user)->whereKey($nilai->id)->exists();
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru], true);
    }

    public function update(User $user, Nilai $nilai): bool
    {
        return $user->role === UserRole::Admin
            || ($user->role === UserRole::Guru && Nilai::query()->visibleTo($user)->whereKey($nilai->id)->exists());
    }

    public function delete(User $user, Nilai $nilai): bool
    {
        return $user->role === UserRole::Admin;
    }
}
