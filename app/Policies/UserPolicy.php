<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function view(User $user, User $model): bool
    {
        return $user->role === UserRole::Admin || $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, User $model): bool
    {
        // Editing (name/email/password) is allowed even on the protected
        // admin account; Users::save() separately blocks demoting its role.
        return $user->role === UserRole::Admin || $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        if ($this->isProtectedAdmin($model)) {
            return false;
        }

        return $user->role === UserRole::Admin;
    }

    /**
     * The account seeded first (id 1, admin role) can never be demoted or
     * deleted through normal flows, replacing v1's hardcoded email check.
     */
    public function isProtectedAdmin(User $model): bool
    {
        return $model->role === UserRole::Admin && $model->id === 1;
    }
}
