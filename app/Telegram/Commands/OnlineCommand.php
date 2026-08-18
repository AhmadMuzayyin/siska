<?php

namespace App\Telegram\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Commands\Command;

class OnlineCommand extends Command
{
    protected string $name = 'online';

    protected string $description = 'Listing user yang sedang login / aktif';

    public function handle(): void
    {
        // Active sessions within last 30 minutes
        $threshold = Carbon::now()->subMinutes(30)->timestamp;

        $sessions = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', $threshold)
            ->orderByDesc('last_activity')
            ->get();

        if ($sessions->isEmpty()) {
            $this->replyWithMessage([
                'text' => "👥 <b>Daftar Pengguna Online</b>\n\n<i>Tidak ada pengguna yang sedang aktif dalam 30 menit terakhir.</i>",
                'parse_mode' => 'HTML',
            ]);

            return;
        }

        $userIds = $sessions->pluck('user_id')->unique()->values();
        $users = User::query()->whereIn('id', $userIds)->get()->keyBy('id');

        $lines = [];
        $count = 1;
        foreach ($sessions as $session) {
            $user = $users->get($session->user_id);
            if (! $user) {
                continue;
            }

            $lastActive = Carbon::createFromTimestamp($session->last_activity)->diffForHumans();
            $roleName = strtoupper($user->role instanceof UserRole ? $user->role->value : (string) $user->role);
            $lines[] = "{$count}. <b>{$user->name}</b>\n   • Role: <code>{$roleName}</code>\n   • Email: {$user->email}\n   • Aktif: {$lastActive} (IP: {$session->ip_address})";
            $count++;
        }

        $text = '👥 <b>Daftar Pengguna Online (Total: '.count($lines).")</b>\n\n".implode("\n\n", $lines);

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
