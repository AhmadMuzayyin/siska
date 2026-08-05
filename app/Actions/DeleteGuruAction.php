<?php

namespace App\Actions;

use App\Exceptions\GuruMasihDipakaiException;
use App\Models\Guru;
use Illuminate\Support\Facades\DB;

class DeleteGuruAction
{
    /**
     * Delete a guru, refusing when they're still a wali kelas or still
     * teaching a scheduled lesson, so those references don't dangle.
     */
    public function handle(Guru $guru): void
    {
        $guru->loadCount(['jadwalPelajarans']);
        $guru->load('waliKelas.kelas');

        if ($guru->waliKelas !== null) {
            throw new GuruMasihDipakaiException(
                "Guru masih menjadi wali kelas \"{$guru->waliKelas->kelas->nama}\". Tunjuk wali kelas pengganti terlebih dahulu.",
            );
        }

        if ($guru->jadwal_pelajarans_count > 0) {
            throw new GuruMasihDipakaiException(
                'Guru masih memiliki jadwal pelajaran aktif. Hapus atau alihkan jadwalnya terlebih dahulu.',
            );
        }

        DB::transaction(function () use ($guru): void {
            $user = $guru->user;
            $guru->delete();
            $user->delete();
        });
    }
}
