<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Galeri') }}</flux:heading>
            <flux:subheading>{{ __('Kelola foto kegiatan, wisata, dan bimbingan pesantren.') }}</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
            {{ __('Tambah Foto') }}
        </flux:button>
    </div>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <flux:select wire:model.live="typeFilter" class="max-w-48" placeholder="{{ __('Pilih Kategori') }}">
            <flux:select.option value="">{{ __('Semua Kategori') }}</flux:select.option>
            @foreach ($this->types as $type)
                <flux:select.option value="{{ $type->value }}">{{ ucfirst($type->value) }}</flux:select.option>
            @endforeach
        </flux:select>

        <div class="w-full sm:w-auto">
            <flux:input wire:model.live.debounce.250ms="search" placeholder="{{ __('Cari foto...') }}" icon="magnifying-glass" class="w-full sm:w-64" />
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($this->rows as $gallery)
            <div wire:key="gallery-{{ $gallery->id }}" class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                <div class="aspect-video w-full overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                    <img src="{{ $gallery->image }}" alt="{{ $gallery->title }}" class="h-full w-full object-cover" />
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between gap-2">
                        <flux:badge size="sm" color="zinc">{{ ucfirst($gallery->type->value) }}</flux:badge>
                        <div class="flex gap-1">
                            <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $gallery->id }})" />
                            <flux:button
                                size="sm"
                                variant="ghost"
                                icon="trash"
                                class="text-red-600 hover:text-red-700 cursor-pointer"
                                wire:click="$set('deletingId', {{ $gallery->id }})"
                                x-on:click="$flux.modal('confirm-delete-gallery-modal').show()"
                            />
                        </div>
                    </div>
                    <flux:heading size="sm" class="mt-2">{{ $gallery->title }}</flux:heading>
                    @if ($gallery->description)
                        <flux:subheading size="sm" class="line-clamp-2 mt-1">{{ $gallery->description }}</flux:subheading>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-10 text-center text-zinc-400">
                {{ __('Belum ada foto di galeri.') }}
            </div>
        @endforelse
    </div>

    {{ $this->rows->links() }}

    <flux:modal name="gallery-form" flyout class="md:w-96" @close="$set('editingId', null)">
        <form wire:submit="save" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? __('Edit Foto') : __('Tambah Foto') }}</flux:heading>
            </div>

            <flux:select wire:model="type" :label="__('Kategori')" placeholder="{{ __('Pilih Kategori') }}">
                @foreach ($this->types as $type)
                    <flux:select.option value="{{ $type->value }}">{{ ucfirst($type->value) }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:input wire:model="title" :label="__('Judul')" />
            <flux:input wire:model="image" :label="__('URL Gambar')" placeholder="https://..." />
            <flux:textarea wire:model="description" :label="__('Deskripsi (opsional)')" rows="2" />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">{{ __('Simpan') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Confirm Delete Gallery Modal --}}
    <x-confirm-modal 
        name="confirm-delete-gallery-modal" 
        title="{{ __('Hapus Foto Galeri') }}" 
        description="{{ __('Apakah Anda yakin ingin menghapus foto galeri ini?') }}" 
        action="delete" 
        confirmText="{{ __('Hapus Foto') }}" 
    />
</div>
