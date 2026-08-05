<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\TahunAkademik;
use App\Models\User;

class TahunAkademikPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::Keuangan, UserRole::KepalaMadrasah], true);
    }

    public function view(User $user, TahunAkademik $tahunAkademik): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::Keuangan, UserRole::KepalaMadrasah], true);
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, TahunAkademik $tahunAkademik): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, TahunAkademik $tahunAkademik): bool
    {
        return $user->role === UserRole::Admin;
    }
}
