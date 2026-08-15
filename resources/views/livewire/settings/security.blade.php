<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Security settings') }}</flux:heading>

    {{-- Update Password --}}
    <x-settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                :label="__('Current password')"
                type="password"
                required
                autocomplete="current-password"
                viewable
            />
            <flux:input
                wire:model="password"
                :label="__('New password')"
                type="password"
                required
                autocomplete="new-password"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />
            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit" data-test="update-password-button">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </x-settings.layout>

    @if (\Laravel\Fortify\Features::canManageTwoFactorAuthentication())
    {{-- Two-Factor Authentication Panel --}}
    <x-settings.layout
        :heading="__('Two-Factor Authentication')"
        :subheading="__('Add extra security using a time-based one-time password (TOTP) from an authenticator app.')"
    >
        @if (auth()->user()->two_factor_confirmed_at)
            {{-- 2FA sudah aktif dan terkonfirmasi --}}
            <div class="mt-6 space-y-4">
                <flux:badge variant="solid" color="green" class="inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Two-factor authentication is enabled.') }}
                </flux:badge>

                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Your account is protected by an authenticator app.') }}
                </p>

                {{-- Recovery codes --}}
                <details class="group">
                    <summary class="cursor-pointer text-sm text-primary-600 dark:text-primary-400 hover:underline">
                        {{ __('Show recovery codes') }}
                    </summary>
                    <div class="mt-3 p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg font-mono text-xs space-y-1">
                        @foreach ((array) auth()->user()->recoveryCodes() as $code)
                            <div>{{ $code }}</div>
                        @endforeach
                    </div>
                </details>

                {{-- Disable 2FA --}}
                <form method="POST" action="/user/two-factor-authentication">
                    @csrf
                    @method('DELETE')
                    <flux:button variant="danger" type="submit" data-test="disable-2fa-button"
                        onclick="return confirm('{{ __('Apakah Anda yakin ingin menonaktifkan Two-Factor Authentication?') }}')">
                        {{ __('Disable Two-Factor Authentication') }}
                    </flux:button>
                </form>
            </div>

        @elseif (session('status') === 'two-factor-authentication-enabled')
            {{-- 2FA baru diaktifkan, perlu konfirmasi --}}
            <div class="mt-6 space-y-4">
                <flux:callout variant="warning" icon="exclamation-triangle">
                    {{ __('Please finish configuring two-factor authentication below.') }}
                </flux:callout>

                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Scan the QR code below with your authenticator app (e.g., Google Authenticator, Authy), then enter the code to confirm.') }}
                </p>

                <div class="flex justify-start">
                    {!! auth()->user()->twoFactorQrCodeSvg() !!}
                </div>

                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ __('Setup key:') }}
                    <span class="font-mono">{{ decrypt(auth()->user()->two_factor_secret) }}</span>
                </p>

                {{-- Recovery codes --}}
                <div class="p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                    <p class="text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('Save these recovery codes in a safe place:') }}</p>
                    <div class="font-mono text-xs space-y-1">
                        @foreach ((array) auth()->user()->recoveryCodes() as $code)
                            <div>{{ $code }}</div>
                        @endforeach
                    </div>
                </div>

                {{-- Confirm 2FA --}}
                <form method="POST" action="/user/confirmed-two-factor-authentication" class="flex gap-3 items-end">
                    @csrf
                    <flux:input
                        name="code"
                        :label="__('Confirm with authentication code')"
                        type="text"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        :placeholder="__('6-digit code')"
                        required
                    />
                    <flux:button variant="primary" type="submit" data-test="confirm-2fa-button">
                        {{ __('Confirm') }}
                    </flux:button>
                </form>
            </div>

        @else
            {{-- 2FA belum aktif --}}
            <div class="mt-6 space-y-4">
                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Two-factor authentication is not enabled for your account. Enable it to add an extra layer of security.') }}
                </p>

                <form method="POST" action="/user/two-factor-authentication">
                    @csrf
                    <flux:button variant="filled" type="submit" data-test="enable-2fa-button">
                        {{ __('Enable Two-Factor Authentication') }}
                    </flux:button>
                </form>
            </div>
        @endif
    </x-settings.layout>
    @endif

</section>
