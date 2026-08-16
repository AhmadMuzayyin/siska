<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasRole('admin') || $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasRole('admin') || $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        if ($this->isProtectedAdmin($model)) {
            return false;
        }

        return $user->hasRole('admin');
    }

    public function isProtectedAdmin(User $model): bool
    {
        return $model->hasRole('admin') && $model->id === 1;
    }
}
