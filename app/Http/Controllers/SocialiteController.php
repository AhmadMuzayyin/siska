<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
            'profile_photo_path' => $userGoogle->avatar,
        ]);
        Auth::login($user);
        return redirect()->route('filament.admin.pages.dashboard');
    }
}
