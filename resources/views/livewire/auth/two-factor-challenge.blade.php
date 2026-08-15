<x-layouts::auth :title="__('Two-Factor Authentication')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Two-Factor Authentication')"
            :description="__('Please enter your authentication code to continue.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('two-factor.login') }}" class="flex flex-col gap-6">
            @csrf

            {{-- Kode TOTP dari aplikasi authenticator --}}
            <div id="two-factor-code-wrapper" class="flex flex-col gap-4">
                <flux:input
                    name="code"
                    id="two-factor-code"
                    :label="__('Authentication Code')"
                    type="text"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    :placeholder="__('6-digit code')"
                    autofocus
                />
            </div>

            {{-- Recovery code (toggle) --}}
            <div id="recovery-code-wrapper" class="hidden flex-col gap-4">
                <flux:input
                    name="recovery_code"
                    id="recovery-code"
                    :label="__('Recovery Code')"
                    type="text"
                    autocomplete="one-time-code"
                    :placeholder="__('Enter recovery code')"
                />
            </div>

            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Verify') }}
            </flux:button>

            <div class="text-sm text-center">
                <button
                    type="button"
                    id="toggle-recovery"
                    class="text-zinc-600 dark:text-zinc-400 hover:underline"
                    onclick="
                        const codeWrapper = document.getElementById('two-factor-code-wrapper');
                        const recoveryWrapper = document.getElementById('recovery-code-wrapper');
                        const isRecovery = !recoveryWrapper.classList.contains('hidden');
                        codeWrapper.classList.toggle('hidden', isRecovery);
                        recoveryWrapper.classList.toggle('hidden', !isRecovery);
                        this.textContent = isRecovery ? '{{ __('Use a recovery code instead') }}' : '{{ __('Use an authentication code instead') }}';
                    "
                >
                    {{ __('Use a recovery code instead') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts::auth>
