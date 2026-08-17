<div class="flex flex-col gap-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Pesan Masuk') }}</flux:heading>
            <flux:subheading>{{ __('Pesan dari formulir kontak website.') }}</flux:subheading>
        </div>

        <div class="w-full sm:w-auto">
            <flux:input wire:model.live.debounce.250ms="search" placeholder="{{ __('Cari pengirim/subjek...') }}" icon="magnifying-glass" class="w-full sm:w-64" />
        </div>
    </div>

    <div class="flex flex-col gap-3">
        @forelse ($this->rows as $contact)
            <div wire:key="contact-{{ $contact->id }}" class="rounded-xl border border-zinc-200 bg-white p-4 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex flex-wrap items-start justify-between gap-2">
                    <div>
                        <flux:heading size="sm">{{ $contact->subject }}</flux:heading>
                        <flux:text class="text-zinc-500">{{ $contact->name }} &middot; {{ $contact->email }}</flux:text>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:text class="text-xs text-zinc-400">{{ $contact->created_at->format('d M Y H:i') }}</flux:text>
                        <flux:button
                            size="sm"
                            variant="ghost"
                            icon="trash"
                            class="text-red-600 hover:text-red-700 cursor-pointer"
                            wire:click="$set('deletingId', {{ $contact->id }})"
                            x-on:click="$flux.modal('confirm-delete-contact-modal').show()"
                        />
                    </div>
                </div>
                <flux:text class="mt-2 text-xs text-zinc-600 dark:text-zinc-300 leading-relaxed">{{ $contact->message }}</flux:text>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-zinc-300 p-10 text-center text-zinc-400 dark:border-zinc-700">
                {{ __('Belum ada pesan ditemukan.') }}
            </div>
        @endforelse
    </div>

    {{ $this->rows->links() }}

    {{-- Confirm Delete Contact Modal --}}
    <x-confirm-modal 
        name="confirm-delete-contact-modal" 
        title="{{ __('Hapus Pesan Masuk') }}" 
        description="{{ __('Apakah Anda yakin ingin menghapus pesan kontak masuk ini?') }}" 
        action="delete" 
        confirmText="{{ __('Hapus Pesan') }}" 
    />
</div>
