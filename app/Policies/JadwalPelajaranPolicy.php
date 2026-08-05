<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\JadwalPelajaran;
use App\Models\User;

class JadwalPelajaranPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::KepalaMadrasah], true);
    }

    public function view(User $user, JadwalPelajaran $jadwalPelajaran): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Guru, UserRole::KepalaMadrasah], true);
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, JadwalPelajaran $jadwalPelajaran): bool
    {
        return $user->role === UserRole::Admin;
    }
}
