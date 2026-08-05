<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function view(User $user, Subscription $subscription): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Subscription $subscription): bool
    {
        return $user->role === UserRole::Admin;
    }
}
