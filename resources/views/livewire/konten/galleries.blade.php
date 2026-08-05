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
        <flux:select wire:model.live="typeFilter" class="max-w-48" placeholder="{{ __('Semua kategori') }}">
            @foreach ($this->types as $type)
                <flux:select.option value="{{ $type->value }}">{{ ucfirst($type->value) }}</flux:select.option>
            @endforeach
        </flux:select>

        <div class="w-full sm:w-auto">
            <flux:input wire:model.live.debounce.250ms="search" placeholder="{{ __('Cari foto...') }}" icon="magnifying-glass" class="w-full sm:w-64" />
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
        @forelse ($this->rows as $gallery)
            <div wire:key="gallery-{{ $gallery->id }}" class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                <div class="aspect-video w-full bg-zinc-100 bg-cover bg-center dark:bg-zinc-800" style="background-image: url('{{ $gallery->image }}')"></div>
                <div class="flex flex-col gap-2 p-3">
                    <flux:badge size="sm" color="zinc" class="w-fit">{{ ucfirst($gallery->type->value) }}</flux:badge>
                    <flux:text class="truncate font-medium">{{ $gallery->title }}</flux:text>
                    <div class="flex justify-end gap-1">
                        <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $gallery->id }})" />
                        <flux:button
                            size="sm"
                            variant="ghost"
                            icon="trash"
                            class="text-red-600 hover:text-red-700"
                            wire:click="delete({{ $gallery->id }})"
                            wire:confirm="{{ __('Yakin ingin menghapus foto ini?') }}"
                        />
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-xl border border-dashed border-zinc-300 p-10 text-center text-zinc-400 dark:border-zinc-700">
                {{ __('Belum ada foto ditemukan.') }}
            </div>
        @endforelse
    </div>

    {{ $this->rows->links() }}

    <flux:modal name="gallery-form" class="md:w-96" @close="$set('editingId', null)">
        <form wire:submit="save" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? __('Edit Foto') : __('Tambah Foto') }}</flux:heading>
            </div>

            <flux:select wire:model="type" :label="__('Kategori')">
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
</div>
