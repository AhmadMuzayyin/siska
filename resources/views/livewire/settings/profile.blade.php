<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Pengaturan Profil') }}</flux:heading>

    <x-settings.layout :heading="__('Profil Pengguna')" :subheading="__('Perbarui nama lengkap dan alamat email akun Anda')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('Nama Lengkap')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Alamat Email')" type="email" required autocomplete="email" />
            </div>

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('Simpan Perubahan') }}</flux:button>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
