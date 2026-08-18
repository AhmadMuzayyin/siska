<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Program;
use App\Models\User;

class ProgramPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Operator], true);
    }

    public function view(User $user, Program $program): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Operator], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Operator], true);
    }

    public function update(User $user, Program $program): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Operator], true);
    }

    public function delete(User $user, Program $program): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Operator], true);
    }
}
