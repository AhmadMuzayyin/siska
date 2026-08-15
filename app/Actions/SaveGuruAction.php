<?php

namespace App\Actions;

use App\Enums\UserRole;
use App\Models\Guru;
use App\Models\User;
use App\Rules\IndonesianPhoneNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SaveGuruAction
{
    /**
     * Simpan data Guru (baru atau update) bersama akun User terkait dalam satu database transaction.
     *
     * @param  array{name: string, email: string, password?: string|null, alamat: string, whatsapp: string, gender: mixed, status: mixed, rfid_uid?: string|null}  $data
     */
    public function handle(array $data, ?Guru $guru = null): Guru
    {
        return DB::transaction(function () use ($data, $guru): Guru {
            if ($guru) {
                $guru->loadMissing('user');

                $userUpdates = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                ];

                if (! empty($data['password'])) {
                    $userUpdates['password'] = Hash::make($data['password']);
                }

                $guru->user->update($userUpdates);

                $guru->update([
                    'alamat' => $data['alamat'],
                    'whatsapp' => IndonesianPhoneNumber::normalize($data['whatsapp']),
                    'gender' => $data['gender'],
                    'status' => $data['status'],
                    'rfid_uid' => ! empty($data['rfid_uid']) ? $data['rfid_uid'] : null,
                ]);

                return $guru;
            }

            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => UserRole::Guru,
            ]);

            return Guru::query()->create([
                'user_id' => $user->id,
                'alamat' => $data['alamat'],
                'whatsapp' => IndonesianPhoneNumber::normalize($data['whatsapp']),
                'gender' => $data['gender'],
                'status' => $data['status'],
                'rfid_uid' => ! empty($data['rfid_uid']) ? $data['rfid_uid'] : null,
            ]);
        });
    }
}
