<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Tabungan;
use App\Models\User;

class TabunganPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Keuangan, UserRole::Guru, UserRole::KepalaMadrasah], true);
    }

    public function view(User $user, Tabungan $tabungan): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Keuangan], true);
    }

    public function update(User $user, Tabungan $tabungan): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Keuangan], true);
    }

    public function delete(User $user, Tabungan $tabungan): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Keuangan], true);
    }
}
