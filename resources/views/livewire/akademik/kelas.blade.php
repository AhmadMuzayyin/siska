<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Kelas') }}</flux:heading>
            <flux:subheading>{{ __('Kelola daftar kelas, kapasitas, dan wali kelas per unit lembaga.') }}</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
            {{ __('Tambah Kelas') }}
        </flux:button>
    </div>

    <div class="flex items-center gap-3">
        <flux:input
            wire:model.live.debounce.400ms="search"
            icon="magnifying-glass"
            placeholder="{{ __('Cari nama kelas...') }}"
            class="max-w-xs"
        />
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <flux:table :paginate="$this->rows">
            <flux:table.columns>
                <flux:table.column>{{ __('Lembaga') }}</flux:table.column>
                <flux:table.column>{{ __('Kode') }}</flux:table.column>
                <flux:table.column>{{ __('Nama Kelas') }}</flux:table.column>
                <flux:table.column>{{ __('Wali Kelas') }}</flux:table.column>
                <flux:table.column align="center">{{ __('Santri') }}</flux:table.column>
                <flux:table.column align="center">{{ __('Kapasitas') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->rows as $kelas)
                    <flux:table.row wire:key="kelas-{{ $kelas->id }}">
                        <flux:table.cell>
                            <flux:badge size="sm" color="emerald" class="font-bold">
                                {{ $kelas->lembaga?->jenjang ?? 'GLOBAL' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" color="zinc" class="font-mono font-bold">
                                {{ $kelas->kode ?? 'KLS-'.$kelas->id }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell variant="strong">{{ $kelas->nama }}</flux:table.cell>
                        <flux:table.cell>
                            @if ($kelas->waliKelas?->guru?->user)
                                {{ $kelas->waliKelas->guru->user->name }}
                            @else
                                <flux:text class="text-zinc-400">{{ __('Belum ditentukan') }}</flux:text>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell align="center">
                            <flux:badge size="sm" :color="$kelas->santris_count >= $kelas->kapasitas ? 'amber' : 'zinc'">
                                {{ $kelas->santris_count }} / {{ $kelas->kapasitas }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="center">{{ $kelas->kapasitas }}</flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex justify-end gap-1">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $kelas->id }})" />
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-700 cursor-pointer"
                                    wire:click="$set('deletingId', {{ $kelas->id }})"
                                    x-on:click="$flux.modal('confirm-delete-kelas-modal').show()"
                                />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="py-10 text-center text-zinc-400">
                            {{ __('Belum ada data kelas.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:modal name="kelas-form" flyout class="md:w-96" @close="$set('editingId', null)">
        <form wire:submit="save" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? __('Edit Kelas') : __('Tambah Kelas') }}</flux:heading>
                <flux:subheading>{{ __('Pilih unit lembaga, nama kelas, dan kapasitas.') }}</flux:subheading>
            </div>

            <flux:select wire:model="lembaga_id" :label="__('Unit Lembaga')" placeholder="{{ __('Pilih Unit Lembaga') }}">
                @foreach ($this->lembagaOptions as $lembaga)
                    <flux:select.option value="{{ $lembaga->id }}">{{ $lembaga->nama }} ({{ $lembaga->jenjang }})</flux:select.option>
                @endforeach
            </flux:select>

            <flux:input wire:model="nama" :label="__('Nama Kelas')" placeholder="mis. 1A" />
            <flux:input wire:model="kapasitas" type="number" min="1" :label="__('Kapasitas')" />

            <flux:select wire:model="waliKelasGuruId" :label="__('Wali Kelas')" placeholder="{{ __('Pilih Wali Kelas') }}">
                @foreach ($this->availableGurus as $guru)
                    <flux:select.option value="{{ $guru->id }}">{{ $guru->user->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">{{ __('Simpan') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Confirm Delete Kelas Modal --}}
    <x-confirm-modal 
        name="confirm-delete-kelas-modal" 
        title="{{ __('Hapus Kelas') }}" 
        description="{{ __('Apakah Anda yakin ingin menghapus kelas ini?') }}" 
        action="delete" 
        confirmText="{{ __('Hapus Kelas') }}" 
    />
</div>
