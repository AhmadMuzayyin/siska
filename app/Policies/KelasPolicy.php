<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Kelas;
use App\Models\User;

class KelasPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::Keuangan, UserRole::KepalaMadrasah], true);
    }

    public function view(User $user, Kelas $kelas): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::Keuangan, UserRole::KepalaMadrasah], true);
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Kelas $kelas): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Kelas $kelas): bool
    {
        return $user->role === UserRole::Admin;
    }
}
