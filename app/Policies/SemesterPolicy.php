<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Semester;
use App\Models\User;

class SemesterPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::Keuangan, UserRole::KepalaMadrasah], true);
    }

    public function view(User $user, Semester $semester): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::Keuangan, UserRole::KepalaMadrasah], true);
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Semester $semester): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Semester $semester): bool
    {
        return $user->role === UserRole::Admin;
    }
}
