<?php

namespace App\Policies;

use App\Models\Kelas;
use App\Models\User;

class KelasPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru', 'keuangan', 'kepala_madrasah']);
    }

    public function view(User $user, Kelas $kelas): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru', 'keuangan', 'kepala_madrasah']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function update(User $user, Kelas $kelas): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function delete(User $user, Kelas $kelas): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }
}
