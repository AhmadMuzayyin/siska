<?php

namespace App\Http\Controllers\Auth;

use App\Enums\AccountType;
use App\Enums\Gender;
use App\Enums\GuruStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
     * STRICT RULE:
     * 1. If user doesn't exist, automatically register Guru with status 'tidak_aktif' (requires admin confirmation).
     * 2. If user exists, must have Guru role and active status to authenticate.
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

        // 1. If user does not exist in database, automatically register as Guru with 'tidak_aktif' status
        if (! $user) {
            [$newUser, $newGuru] = DB::transaction(function () use ($googleUser, $email) {
                $newUser = User::query()->create([
                    'name' => $googleUser->getName() ?: 'Guru',
                    'email' => $email,
                    'password' => Hash::make(Str::random(32)),
                    'role' => UserRole::Guru,
                    'account_type' => AccountType::Google,
                    'email_verified_at' => now(),
                ]);

                $newGuru = Guru::query()->create([
                    'user_id' => $newUser->id,
                    'alamat' => '-',
                    'whatsapp' => '-',
                    'gender' => Gender::LakiLaki,
                    'status' => GuruStatus::TidakAktif,
                ]);

                return [$newUser, $newGuru];
            });

            // Kirim notifikasi Telegram ke Admin dengan tombol konfirmasi
            app(TelegramService::class)->sendNewGuruNotification($newUser, $newGuru);

            return redirect()->route('login')->with('warning', __('Pendaftaran akun Guru (:email) melalui Google berhasil. Akun Anda saat ini berstatus non-aktif dan memerlukan konfirmasi/persetujuan dari Administrator sebelum dapat masuk.', ['email' => $email]));
        }

        // 2. Check if user role is Guru
        if ($user->role !== UserRole::Guru) {
            return redirect()->route('login')->with('error', __('Login dengan Akun Google khusus untuk akun ber-role Guru. Akun Anda bukan role Guru.'));
        }

        // 3. Check if Guru record exists and is active
        $guru = $user->guru;
        if (! $guru || $guru->status !== GuruStatus::Aktif) {
            return redirect()->route('login')->with('warning', __('Akun Guru Anda (:email) belum diaktifkan oleh Administrator. Silakan hubungi Admin untuk konfirmasi pengaktifan akun.', ['email' => $email]));
        }

        // 4. Authenticate active teacher user
        Auth::login($user, remember: true);
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
