<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\AbsensiGuru;
use App\Models\User;

class AbsensiGuruPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::KepalaMadrasah], true);
    }

    public function view(User $user, AbsensiGuru $absensiGuru): bool
    {
        return AbsensiGuru::query()->visibleTo($user)->whereKey($absensiGuru->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, AbsensiGuru $absensiGuru): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, AbsensiGuru $absensiGuru): bool
    {
        return $user->role === UserRole::Admin;
    }
}
