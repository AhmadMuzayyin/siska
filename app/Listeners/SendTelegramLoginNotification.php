<?php

namespace App\Listeners;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Auth\Events\Login;

class SendTelegramLoginNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected TelegramService $telegramService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        // Only send notification for non-admin roles
        if (! $user || $user->role === UserRole::Admin) {
            return;
        }

        $this->telegramService->sendUserLoginNotification(
            $user,
            request()->ip(),
            request()->userAgent()
        );
    }
}
