<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\GajiGuru;
use App\Models\User;

class GajiGuruPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Keuangan, UserRole::Guru], true);
    }

    public function view(User $user, GajiGuru $gajiGuru): bool
    {
        return GajiGuru::query()->visibleTo($user)->whereKey($gajiGuru->id)->exists();
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Keuangan], true);
    }

    public function update(User $user, GajiGuru $gajiGuru): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Keuangan], true);
    }

    public function delete(User $user, GajiGuru $gajiGuru): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Keuangan], true);
    }
}
