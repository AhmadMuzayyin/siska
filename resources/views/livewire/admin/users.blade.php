<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Manajemen Pengguna') }}</flux:heading>
            <flux:subheading>{{ __('Kelola akun admin, keuangan, dan kepala madrasah.') }}</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
            {{ __('Tambah Pengguna') }}
        </flux:button>
    </div>

    <flux:input
        wire:model.live.debounce.400ms="search"
        icon="magnifying-glass"
        placeholder="{{ __('Cari nama atau email...') }}"
        class="max-w-xs"
    />

    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <flux:table :paginate="$this->rows">
            <flux:table.columns>
                <flux:table.column>{{ __('Nama') }}</flux:table.column>
                <flux:table.column>{{ __('Email') }}</flux:table.column>
                <flux:table.column>{{ __('Peran') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->rows as $user)
                    <flux:table.row wire:key="user-{{ $user->id }}">
                        <flux:table.cell variant="strong">{{ $user->name }}</flux:table.cell>
                        <flux:table.cell>{{ $user->email }}</flux:table.cell>
                        <flux:table.cell class="py-0">
                            <flux:badge size="sm" color="zinc">{{ ucfirst(str_replace('_', ' ', $user->role->value)) }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex justify-end gap-1">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $user->id }})" />
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-700"
                                    wire:click="delete({{ $user->id }})"
                                    wire:confirm="{{ __('Yakin ingin menghapus pengguna ini?') }}"
                                />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="py-10 text-center text-zinc-400">
                            {{ __('Belum ada data pengguna.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:modal name="user-form" flyout class="md:w-96" @close="$set('editingId', null)">
        <form wire:submit="save" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? __('Edit Pengguna') : __('Tambah Pengguna') }}</flux:heading>
                <flux:subheading>{{ __('Akun guru sebaiknya dikelola lewat menu Data Guru.') }}</flux:subheading>
            </div>

            <flux:input wire:model="name" :label="__('Nama')" />
            <flux:input wire:model="email" type="email" :label="__('Email')" />
            <flux:input
                wire:model="password"
                type="password"
                :label="$editingId ? __('Password (kosongkan jika tidak ganti)') : __('Password')"
            />

            <flux:select wire:model="role" :label="__('Peran')">
                @foreach ($this->roles as $roleOption)
                    <flux:select.option value="{{ $roleOption->value }}">{{ ucfirst(str_replace('_', ' ', $roleOption->value)) }}</flux:select.option>
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
</div>
