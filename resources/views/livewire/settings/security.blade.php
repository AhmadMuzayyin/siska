<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Pengaturan Keamanan') }}</flux:heading>

    {{-- Update Password --}}
    <x-settings.layout :heading="__('Perbarui Kata Sandi')" :subheading="__('Pastikan akun Anda menggunakan kata sandi yang kuat dan aman.')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                :label="__('Kata Sandi Saat Ini')"
                type="password"
                required
                autocomplete="current-password"
                viewable
            />
            <flux:input
                wire:model="password"
                :label="__('Kata Sandi Baru')"
                type="password"
                required
                autocomplete="new-password"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />
            <flux:input
                wire:model="password_confirmation"
                :label="__('Konfirmasi Kata Sandi Baru')"
                type="password"
                required
                autocomplete="new-password"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit" data-test="update-password-button">{{ __('Simpan Kata Sandi') }}</flux:button>
            </div>
        </form>
    </x-settings.layout>

    @if (\Laravel\Fortify\Features::canManageTwoFactorAuthentication())
    {{-- Two-Factor Authentication Panel --}}
    <x-settings.layout
        :heading="__('Autentikasi Dua Faktor (2FA)')"
        :subheading="__('Tingkatkan keamanan akun menggunakan one-time password (TOTP) dari aplikasi autentikator.')"
    >
        @if (auth()->user()->two_factor_confirmed_at)
            {{-- 2FA sudah aktif dan terkonfirmasi --}}
            <div class="mt-6 space-y-4">
                <flux:badge variant="solid" color="green" class="inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Autentikasi dua faktor aktif.') }}
                </flux:badge>

                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Akun Anda dilindungi oleh aplikasi autentikator.') }}
                </p>

                {{-- Recovery codes --}}
                <details class="group">
                    <summary class="cursor-pointer text-sm text-emerald-600 dark:text-emerald-400 hover:underline">
                        {{ __('Tampilkan kode pemulihan (Recovery Codes)') }}
                    </summary>
                    <div class="mt-3 p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg font-mono text-xs space-y-1">
                        @foreach ((array) auth()->user()->recoveryCodes() as $code)
                            <div>{{ $code }}</div>
                        @endforeach
                    </div>
                </details>

                {{-- Disable 2FA --}}
                <form id="disable-2fa-form" method="POST" action="/user/two-factor-authentication">
                    @csrf
                    @method('DELETE')
                    <flux:button variant="danger" type="button" data-test="disable-2fa-button" x-on:click="$flux.modal('confirm-disable-2fa-modal').show()">
                        {{ __('Nonaktifkan Two-Factor Authentication') }}
                    </flux:button>
                </form>
            </div>

        @elseif (session('status') === 'two-factor-authentication-enabled')
            {{-- 2FA baru diaktifkan, perlu konfirmasi --}}
            <div class="mt-6 space-y-4">
                <flux:callout variant="warning" icon="exclamation-triangle">
                    {{ __('Harap selesaikan konfigurasi autentikasi dua faktor di bawah ini.') }}
                </flux:callout>

                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Pindai kode QR di bawah ini dengan aplikasi autentikator Anda (misal Google Authenticator, Authy), lalu masukkan kode untuk konfirmasi.') }}
                </p>

                <div class="flex justify-start">
                    {!! auth()->user()->twoFactorQrCodeSvg() !!}
                </div>

                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ __('Kunci Pengaturan (Setup Key):') }}
                    <span class="font-mono">{{ decrypt(auth()->user()->two_factor_secret) }}</span>
                </p>

                {{-- Recovery codes --}}
                <div class="p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                    <p class="text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('Simpan kode pemulihan ini di tempat yang aman:') }}</p>
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
                        :label="__('Konfirmasi dengan kode autentikasi')"
                        type="text"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        :placeholder="__('6 digit kode')"
                        required
                    />
                    <flux:button variant="primary" type="submit" data-test="confirm-2fa-button">
                        {{ __('Konfirmasi') }}
                    </flux:button>
                </form>
            </div>

        @else
            {{-- 2FA belum aktif --}}
            <div class="mt-6 space-y-4">
                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Autentikasi dua faktor belum aktif pada akun Anda. Aktifkan untuk menambahkan lapisan keamanan ekstra.') }}
                </p>

                <form method="POST" action="/user/two-factor-authentication">
                    @csrf
                    <flux:button variant="filled" type="submit" data-test="enable-2fa-button" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                        {{ __('Aktifkan Two-Factor Authentication') }}
                    </flux:button>
                </form>
            </div>
        @endif
    </x-settings.layout>
    @endif

    {{-- Confirm Disable 2FA Modal --}}
    <flux:modal name="confirm-disable-2fa-modal" class="md:w-96 space-y-6">
        <div class="space-y-2">
            <flux:heading size="lg">{{ __('Nonaktifkan 2FA') }}</flux:heading>
            <flux:subheading>{{ __('Apakah Anda yakin ingin menonaktifkan Two-Factor Authentication?') }}</flux:subheading>
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
            </flux:modal.close>

            <flux:button 
                variant="filled" 
                onclick="document.getElementById('disable-2fa-form').submit()"
                class="bg-rose-600! hover:bg-rose-700! text-white! font-bold"
            >
                {{ __('Ya, Nonaktifkan') }}
            </flux:button>
        </div>
    </flux:modal>

</section>
