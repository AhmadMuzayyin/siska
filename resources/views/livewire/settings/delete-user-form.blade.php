<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('Hapus Akun') }}</flux:heading>
        <flux:subheading>{{ __('Hapus akun Anda beserta seluruh data terkait secara permanen') }}</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('Hapus Akun') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form method="POST" wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Setelah akun dihapus, seluruh data dan hak akses terkait akan dihapus secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi penghapusan akun.') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="password" :label="__('Kata Sandi')" type="password" viewable />

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Batal') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit">{{ __('Ya, Hapus Akun') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
