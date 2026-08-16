<div class="flex flex-col gap-6">
    {{-- Header Page --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" class="text-2xl font-bold">{{ __('Manajemen Peran & Izin Akses') }}</flux:heading>
            <flux:subheading class="mt-1">
                {{ __('Kelola daftar peran pengguna (Spatie Roles) dan berikan hak akses permission secara spesifik.') }}
            </flux:subheading>
        </div>

        <div>
            <flux:button variant="primary" icon="plus" wire:click="create">
                {{ __('Tambah Peran Baru') }}
            </flux:button>
        </div>
    </div>

    {{-- Filter Search & Stats --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-between items-center bg-white dark:bg-zinc-900 p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-2xs">
        <div class="w-full sm:w-80">
            <flux:input 
                wire:model.live.debounce.300ms="search" 
                icon="magnifying-glass" 
                placeholder="Cari nama peran..." 
                clearable 
            />
        </div>
        <div class="text-xs text-zinc-500">
            Total Peran: <span class="font-bold text-zinc-900 dark:text-white">{{ $this->rolesList->count() }}</span> &bull;
            Total Permission: <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $this->allPermissions->count() }}</span>
        </div>
    </div>

    {{-- Roles Grid / Table --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($this->rolesList as $role)
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs flex flex-col justify-between dark:border-zinc-800 dark:bg-zinc-900 space-y-4">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-900 dark:bg-purple-950 dark:text-purple-300' : ($role->name === 'operator' ? 'bg-emerald-100 text-emerald-900 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200') }}">
                            {{ $role->name }}
                        </span>
                        <span class="text-xs text-zinc-400 font-medium">
                            {{ $role->users_count }} Pengguna
                        </span>
                    </div>

                    <div class="pt-1">
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-white">
                            {{ Str::headline($role->name) }}
                        </h4>
                        <p class="text-xs text-zinc-500 mt-0.5">
                            {{ $role->permissions->count() }} Permission Diberikan
                        </p>
                    </div>

                    {{-- Sample Permissions Badges --}}
                    <div class="flex flex-wrap gap-1.5 pt-2 max-h-28 overflow-hidden">
                        @forelse ($role->permissions->take(6) as $perm)
                            <span class="px-2 py-0.5 rounded-md bg-zinc-100 text-zinc-700 text-[10px] font-mono dark:bg-zinc-800 dark:text-zinc-300">
                                {{ $perm->name }}
                            </span>
                        @empty
                            <span class="text-xs text-zinc-400 italic">{{ __('Belum ada permission.') }}</span>
                        @endforelse

                        @if ($role->permissions->count() > 6)
                            <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold dark:bg-emerald-950 dark:text-emerald-300">
                                +{{ $role->permissions->count() - 6 }} Lainnya
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                    <flux:button variant="subtle" size="xs" icon="pencil-square" wire:click="edit({{ $role->id }})">
                        {{ __('Edit & Assign Permission') }}
                    </flux:button>

                    @if ($role->name !== 'admin')
                        <flux:button 
                            variant="ghost" 
                            size="xs" 
                            icon="trash" 
                            class="text-rose-600 hover:text-rose-700" 
                            wire:click="delete({{ $role->id }})"
                            wire:confirm="Yakin ingin menghapus peran '{{ $role->name }}'?"
                        />
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Slide-Over Flyout Modal Form --}}
    <flux:modal name="role-form" flyout class="space-y-6 max-w-xl">
        <div>
            <flux:heading size="lg" class="font-bold">
                {{ $editingId ? __('Edit Peran & Permission') : __('Tambah Peran Baru') }}
            </flux:heading>
            <flux:subheading class="mt-1">
                {{ __('Tentukan nama peran dan pilih hak akses permission yang diizinkan.') }}
            </flux:subheading>
        </div>

        <form wire:submit="save" class="space-y-6">
            {{-- Input Role Name --}}
            <flux:field>
                <flux:label>{{ __('Nama Peran (Role Name)') }} <span class="text-rose-500">*</span></flux:label>
                <flux:input 
                    wire:model="name" 
                    placeholder="misal: bendahara_lembaga, supervisor, pengawas" 
                    required 
                />
                <flux:error name="name" />
            </flux:field>

            {{-- Quick Selection Buttons --}}
            <div class="flex items-center justify-between border-t border-b border-zinc-200 dark:border-zinc-800 py-3">
                <span class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider">
                    {{ __('Pilih Hak Akses (Permissions)') }}
                </span>
                <div class="flex gap-2">
                    <button type="button" wire:click="selectAllPermissions" class="text-xs font-bold text-emerald-600 hover:underline cursor-pointer">
                        {{ __('Pilih Semua') }}
                    </button>
                    <span class="text-zinc-300">&bull;</span>
                    <button type="button" wire:click="deselectAllPermissions" class="text-xs font-bold text-rose-600 hover:underline cursor-pointer">
                        {{ __('Hapus Semua') }}
                    </button>
                </div>
            </div>

            {{-- Permissions Checkbox Group --}}
            <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    @foreach ($this->allPermissions as $perm)
                        <label class="p-3 rounded-xl border border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-800/60 flex items-start gap-3 cursor-pointer hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20 transition">
                            <input 
                                type="checkbox" 
                                value="{{ $perm->name }}" 
                                wire:model="selectedPermissions"
                                class="rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500 mt-0.5 size-4"
                            />
                            <div class="space-y-0.5">
                                <span class="text-xs font-bold text-zinc-900 dark:text-white block font-mono">
                                    {{ $perm->name }}
                                </span>
                                <span class="text-[11px] text-zinc-500 block">
                                    {{ Str::headline($perm->name) }}
                                </span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="primary" type="submit">
                    {{ __('Simpan Peran & Permission') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
