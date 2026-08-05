<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Contact;
use App\Models\User;

class ContactPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function view(User $user, Contact $contact): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Contact $contact): bool
    {
        return $user->role === UserRole::Admin;
    }
}
