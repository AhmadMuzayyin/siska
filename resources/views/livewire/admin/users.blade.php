<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Manajemen Pengguna') }}</flux:heading>
            <flux:subheading>{{ __('Kelola akun admin, operator, keuangan, dan kepala madrasah.') }}</flux:subheading>
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
                <flux:table.column>{{ __('Unit Lembaga') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->rows as $user)
                    <flux:table.row wire:key="user-{{ $user->id }}">
                        <flux:table.cell variant="strong">{{ $user->name }}</flux:table.cell>
                        <flux:table.cell>{{ $user->email }}</flux:table.cell>
                        <flux:table.cell class="py-0">
                            <flux:badge size="sm" color="{{ $user->role->value === 'operator' ? 'emerald' : 'zinc' }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role->value)) }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($user->lembaga)
                                <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400">
                                    {{ $user->lembaga->nama }}
                                </span>
                            @else
                                <span class="text-xs text-zinc-400 font-italic">{{ __('Global (Semua)') }}</span>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex justify-end gap-1">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $user->id }})" />
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-700 cursor-pointer"
                                    wire:click="$set('deletingId', {{ $user->id }})"
                                    x-on:click="$flux.modal('confirm-delete-user-modal').show()"
                                />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="py-10 text-center text-zinc-400">
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

            <flux:input wire:model="name" :label="__('Nama')" required />
            <flux:input wire:model="email" type="email" :label="__('Email')" required />
            <flux:input
                wire:model="password"
                type="password"
                :label="$editingId ? __('Password (kosongkan jika tidak ganti)') : __('Password')"
            />

            <flux:select wire:model.live="role" :label="__('Peran')" placeholder="{{ __('Pilih Peran') }}">
                @foreach ($this->roles as $roleOption)
                    <flux:select.option value="{{ $roleOption->value }}">{{ ucfirst(str_replace('_', ' ', $roleOption->value)) }}</flux:select.option>
                @endforeach
            </flux:select>

            <div x-show="$wire.role === 'operator'" class="space-y-1">
                <flux:select wire:model="lembaga_id" :label="__('Unit Lembaga (Wajib untuk Operator)')" placeholder="{{ __('Pilih Unit Lembaga') }}">
                    @foreach ($this->lembagasOptions as $l)
                        <flux:select.option value="{{ $l->id }}">{{ $l->nama }} ({{ $l->jenjang }})</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="lembaga_id" />
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">{{ __('Simpan') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Confirm Delete User Modal --}}
    <x-confirm-modal 
        name="confirm-delete-user-modal" 
        title="{{ __('Hapus Pengguna') }}" 
        description="{{ __('Apakah Anda yakin ingin menghapus pengguna ini?') }}" 
        action="delete" 
        confirmText="{{ __('Hapus Pengguna') }}" 
    />
</div>
