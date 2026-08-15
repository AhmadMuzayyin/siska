<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')->with('error', __('Kredensial Google OAuth belum dikonfigurasi pada file .env (GOOGLE_CLIENT_ID & GOOGLE_CLIENT_SECRET).'));
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     * STRICT RULE: Only users with 'guru' role are permitted to authenticate via Google OAuth.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            /** @var \Laravel\Socialite\Two\User $googleUser */
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect()->route('login')->with('error', __('Proses login Google dibatalkan atau terjadi kendala koneksi.'));
        }

        $email = $googleUser->getEmail();

        if (empty($email)) {
            return redirect()->route('login')->with('error', __('Alamat email dari akun Google tidak ditemukan.'));
        }

        $user = User::query()->where('email', $email)->first();

        // 1. Check if user exists in database
        if (! $user) {
            return redirect()->route('login')->with('error', __('Akun Guru dengan email :email tidak terdaftar dalam sistem.', ['email' => $email]));
        }

        // 2. Check if user role is Guru
        if ($user->role !== UserRole::Guru) {
            return redirect()->route('login')->with('error', __('Login dengan Akun Google khusus untuk akun ber-role Guru. Akun Anda bukan role Guru.'));
        }

        // Authenticate teacher user
        Auth::login($user, remember: true);
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
