<?php

namespace App\Policies;

use App\Models\KategoriNilaiHarian;
use App\Models\User;

class KategoriNilaiHarianPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru', 'keuangan', 'kepala_madrasah']);
    }

    public function view(User $user, KategoriNilaiHarian $kategori): bool
    {
        return $user->hasAnyRole(['admin', 'operator', 'guru', 'keuangan', 'kepala_madrasah']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function update(User $user, KategoriNilaiHarian $kategori): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function delete(User $user, KategoriNilaiHarian $kategori): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }
}
