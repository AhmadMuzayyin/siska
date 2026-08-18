<?php

namespace App\Telegram\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Telegram\Bot\Commands\Command;

class StaffCommand extends Command
{
    protected string $name = 'staff';

    protected string $description = 'Listing staff termasuk guru, operator, kepala madrasah, dll';

    public function handle(): void
    {
        $staffRoles = [
            UserRole::Admin->value,
            UserRole::Guru->value,
            UserRole::Operator->value,
            UserRole::KepalaMadrasah->value,
            UserRole::Keuangan->value,
        ];

        $users = User::query()
            ->with(['guru.waliKelas.kelas'])
            ->whereIn('role', $staffRoles)
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        if ($users->isEmpty()) {
            $this->replyWithMessage([
                'text' => "👔 <b>Daftar Staf Lembaga</b>\n\n<i>Belum ada staf yang terdaftar.</i>",
                'parse_mode' => 'HTML',
            ]);

            return;
        }

        $lines = [];
        $count = 1;
        foreach ($users as $user) {
            $roleLabel = match ($user->role) {
                UserRole::Admin => 'Administrator',
                UserRole::Guru => 'Guru / Pengajar',
                UserRole::Operator => 'Operator Sekolah',
                UserRole::KepalaMadrasah => 'Kepala Madrasah',
                UserRole::Keuangan => 'Staff Keuangan',
                default => strtoupper((string) ($user->role instanceof UserRole ? $user->role->value : $user->role)),
            };

            $waliKelasName = $user->guru?->waliKelas?->kelas?->nama ?? '-';

            $lines[] = "{$count}. <b>{$user->name}</b>\n"
                ."   • Email: {$user->email}\n"
                ."   • Jabatan: <code>{$roleLabel}</code>\n"
                ."   • Wali Kelas: {$waliKelasName}";
            $count++;
        }

        $text = '👔 <b>Daftar Staf Lembaga (Total: '.count($lines).")</b>\n\n".implode("\n\n", $lines);

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
