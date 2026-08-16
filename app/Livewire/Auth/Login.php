<?php

namespace App\Livewire\Auth;

use App\Enums\UserRole;
use App\Models\Santri;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Masuk Portal')]
#[Layout('layouts.auth.empty')]
class Login extends Component
{
    #[Validate('required|string')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public bool $showPassword = false;

    public string $mode = 'login';

    public function mount(): void
    {
        if (request()->query('tab') === 'register') {
            $this->mode = 'register';
        }
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'email.required' => 'Email atau Nomor Induk (NIS) wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ];
    }

    public function login(): mixed
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $input = trim($this->email);

        // 1. Attempt standard email/password authentication
        if (Auth::attempt(['email' => $input, 'password' => $this->password], $this->remember)) {
            RateLimiter::clear($this->throttleKey());
            Session::regenerate();

            return redirect()->intended(route('dashboard', absolute: false));
        }

        // 2. Attempt Santri / Wali NIS (noinduk) authentication
        $santri = Santri::query()->where('noinduk', $input)->first();

        if ($santri) {
            $user = User::query()->where('santri_id', $santri->id)->first()
                ?? User::query()->where('email', $santri->noinduk)->first();

            if (! $user) {
                // Initialize default Santri account with password = noinduk
                $user = User::query()->create([
                    'name' => $santri->nama_lengkap,
                    'email' => $santri->noinduk,
                    'password' => Hash::make($santri->noinduk),
                    'role' => UserRole::Santri,
                    'santri_id' => $santri->id,
                    'lembaga_id' => $santri->lembaga_id,
                ]);
            }

            if (Auth::attempt(['email' => $user->email, 'password' => $this->password], $this->remember)) {
                RateLimiter::clear($this->throttleKey());
                Session::regenerate();

                return redirect()->intended(route('dashboard', absolute: false));
            }
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('Email / Nomor Induk (NIS) atau kata sandi tidak cocok.'),
        ]);
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    public function render(): View
    {
        $setting = Setting::query()->first();

        return view('livewire.auth.login', [
            'setting' => $setting,
            'theme' => $setting?->landing_theme ?? 'default',
            'lembagaName' => $setting?->lembaga ?? config('app.name'),
        ]);
    }
}
