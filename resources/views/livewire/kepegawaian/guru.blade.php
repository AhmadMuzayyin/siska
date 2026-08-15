<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Data Guru') }}</flux:heading>
            <flux:subheading>{{ __('Kelola akun dan profil guru.') }}</flux:subheading>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('export.excel', 'guru') }}" class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                <flux:icon name="arrow-down-tray" class="size-4" /> {{ __('Export Excel') }}
            </a>
            <a href="{{ route('export.pdf', 'guru') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700">
                <flux:icon name="printer" class="size-4" /> {{ __('Export PDF') }}
            </a>
            <flux:modal.trigger name="import-guru-modal">
                <flux:button variant="filled" icon="arrow-up-tray" class="bg-blue-600! hover:bg-blue-700! text-white! font-bold">
                    {{ __('Import Excel') }}
                </flux:button>
            </flux:modal.trigger>
            <flux:button variant="primary" icon="plus" wire:click="create" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                {{ __('Tambah Guru') }}
            </flux:button>
        </div>
    </div>

    <flux:input
        wire:model.live.debounce.400ms="search"
        icon="magnifying-glass"
        placeholder="{{ __('Cari nama guru...') }}"
        class="max-w-xs"
    />

    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <flux:table :paginate="$this->rows">
            <flux:table.columns>
                <flux:table.column>{{ __('Nama') }}</flux:table.column>
                <flux:table.column>{{ __('Email') }}</flux:table.column>
                <flux:table.column>{{ __('WhatsApp') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->rows as $guru)
                    <flux:table.row wire:key="guru-{{ $guru->id }}">
                        <flux:table.cell variant="strong">{{ $guru->user->name }}</flux:table.cell>
                        <flux:table.cell>{{ $guru->user->email }}</flux:table.cell>
                        <flux:table.cell>{{ $guru->whatsapp }}</flux:table.cell>
                        <flux:table.cell class="py-0">
                            <flux:badge size="sm" :color="$guru->status->value === 'aktif' ? 'green' : 'zinc'">
                                {{ $guru->status->value === 'aktif' ? __('Aktif') : __('Tidak Aktif') }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex justify-end gap-1">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $guru->id }})" />
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-700"
                                    wire:click="delete({{ $guru->id }})"
                                    wire:confirm="{{ __('Yakin ingin menghapus guru ini?') }}"
                                />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="py-10 text-center text-zinc-400">
                            {{ __('Belum ada data guru.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:modal name="guru-form" flyout class="md:w-[28rem]" @close="$set('editingId', null)">
        <form wire:submit="save" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? __('Edit Guru') : __('Tambah Guru') }}</flux:heading>
                <flux:subheading>{{ __('Data akun dan profil guru.') }}</flux:subheading>
            </div>

            <flux:input wire:model="name" :label="__('Nama Lengkap')" />
            <flux:input wire:model="email" type="email" :label="__('Email')" />
            <flux:input
                wire:model="password"
                type="password"
                :label="$editingId ? __('Password (kosongkan jika tidak ganti)') : __('Password')"
            />

            <div class="grid grid-cols-2 gap-4">
                <flux:select wire:model="gender" :label="__('Jenis Kelamin')" placeholder="{{ __('Pilih Jenis Kelamin') }}">
                    @foreach ($this->genders as $gender)
                        <flux:select.option value="{{ $gender->value }}">
                            {{ $gender->value === 'laki_laki' ? __('Laki-laki') : __('Perempuan') }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select wire:model="status" :label="__('Status')" placeholder="{{ __('Pilih Status') }}">
                    @foreach ($this->statuses as $statusOption)
                        <flux:select.option value="{{ $statusOption->value }}">
                            {{ $statusOption->value === 'aktif' ? __('Aktif') : __('Tidak Aktif') }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <flux:input wire:model="whatsapp" :label="__('Nomor WhatsApp')" placeholder="08xxxxxxxxxx" />
            <flux:textarea wire:model="alamat" :label="__('Alamat')" rows="2" />
            <flux:input wire:model="rfid_uid" :label="__('RFID UID (opsional)')" />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">{{ __('Simpan') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="import-guru-modal" flyout class="md:w-96">
        <form action="{{ route('import.excel', 'guru') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
            @csrf
            <div>
                <flux:heading size="lg">{{ __('Import Data Guru') }}</flux:heading>
                <flux:subheading>{{ __('Unduh template spreadsheet dan unggah berkas .xlsx / .csv yang telah diisi.') }}</flux:subheading>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50 flex flex-col gap-2">
                <flux:text class="text-xs font-semibold text-zinc-600 dark:text-zinc-400">
                    {{ __('Unduh format berkas template:') }}
                </flux:text>
                <a href="{{ route('import.template', 'guru') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-700">
                    <flux:icon name="arrow-down-tray" class="size-4" />
                    {{ __('Download Template Excel') }}
                </a>
            </div>

            <flux:input type="file" name="file" accept=".xlsx,.xls,.csv" required :label="__('Pilih Berkas Excel (.xlsx / .csv)')" />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                    {{ __('Import Data') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
