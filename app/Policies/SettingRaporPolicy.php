<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\SettingRapor;
use App\Models\User;

class SettingRaporPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::KepalaMadrasah], true);
    }

    public function view(User $user, SettingRapor $settingRapor): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::KepalaMadrasah], true);
    }

    public function update(User $user, ?SettingRapor $settingRapor = null): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::KepalaMadrasah], true);
    }

    public function delete(User $user, ?SettingRapor $settingRapor = null): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::KepalaMadrasah], true);
    }
}
