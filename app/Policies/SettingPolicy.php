<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Setting;
use App\Models\User;

class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function view(User $user, Setting $setting): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Setting $setting): bool
    {
        return $user->role === UserRole::Admin;
    }
}
