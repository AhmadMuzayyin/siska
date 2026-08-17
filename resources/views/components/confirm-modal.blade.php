@props([
    'name',
    'title' => __('Konfirmasi Tindakan'),
    'description' => __('Apakah Anda yakin ingin melanjutkan tindakan ini?'),
    'action' => '',
    'variant' => 'danger',
    'confirmText' => __('Ya, Lanjutkan'),
])

<flux:modal :name="$name" class="md:w-96 space-y-6">
    <div class="space-y-2">
        <flux:heading size="lg">{{ $title }}</flux:heading>
        <flux:subheading>{{ $description }}</flux:subheading>
    </div>

    <div class="flex justify-end gap-2">
        <flux:modal.close>
            <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
        </flux:modal.close>

        <flux:button 
            variant="filled" 
            wire:click="{{ $action }}" 
            x-on:click="$flux.modal('{{ $name }}').close()"
            class="{{ $variant === 'danger' ? 'bg-rose-600! hover:bg-rose-700! text-white! font-bold' : 'bg-amber-600! hover:bg-amber-700! text-white! font-bold' }}"
        >
            {{ $confirmText }}
        </flux:button>
    </div>
</flux:modal>
