<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Absensi;
use App\Models\User;

class AbsensiPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::KepalaMadrasah], true);
    }

    public function view(User $user, Absensi $absensi): bool
    {
        return Absensi::query()->visibleTo($user)->whereKey($absensi->id)->exists();
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru], true);
    }

    public function update(User $user, Absensi $absensi): bool
    {
        return $user->role === UserRole::Admin
            || ($user->role === UserRole::Guru && Absensi::query()->visibleTo($user)->whereKey($absensi->id)->exists());
    }

    public function delete(User $user, Absensi $absensi): bool
    {
        return $user->role === UserRole::Admin;
    }
}
