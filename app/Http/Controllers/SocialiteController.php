<?php

namespace App\Http\Controllers;

use App\Models\ConnectedAccount;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function handleGoogleCallback()
    {
        $userGoogle = Socialite::driver('google')->user();
        $user = User::updateOrCreate([
            'email' => $userGoogle->email,
        ], [
            'name' => $userGoogle->name,
            'username' => $userGoogle->name,
            'email' => $userGoogle->email,
            'email_verified_at' => now(),
        ]);
        ConnectedAccount::create([
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_user_id' => $userGoogle->id,
            'name' => $userGoogle->name,
            'nickname' => $userGoogle->name,
            'email' => $userGoogle->email,
            'phone' => $userGoogle->phone,
            'avatar' => $userGoogle->avatar,
            'token' => $userGoogle->token,
            'refresh_token' => $userGoogle->refreshToken,
            'expires_at' => date('Y-m-d H:i:s', strtotime(now()->addHour(1))),
        ]);
        if ($user->is_verified) {
            Auth::login($user);

            return redirect()->route('filament.admin.pages.dashboard');
        } else {
            Notification::make()
                ->title('Silahkan hubungi admin untuk melakukan verifikasi')
                ->success()
                ->send();

            return redirect()->to('admin/login');
        }
    }
}
