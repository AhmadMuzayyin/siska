<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Manajemen Lembaga / Unit Pendidikan') }}</flux:heading>
            <flux:subheading>{{ __('Kelola daftar lembaga dinamis (PAUD, TPQ, MDTA, MI, MTs, MA, dll.) dalam yayasan/pesantren.') }}</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
            {{ __('Tambah Lembaga') }}
        </flux:button>
    </div>

    <div class="flex items-center gap-3">
        <flux:input
            wire:model.live.debounce.400ms="search"
            icon="magnifying-glass"
            placeholder="{{ __('Cari nama, kode, atau jenjang...') }}"
            class="max-w-xs"
        />
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <flux:table :paginate="$this->rows">
            <flux:table.columns>
                <flux:table.column>{{ __('Jenjang / Kode') }}</flux:table.column>
                <flux:table.column>{{ __('Nama Lembaga') }}</flux:table.column>
                <flux:table.column>{{ __('Kepala / NSM') }}</flux:table.column>
                <flux:table.column align="center">{{ __('Data Terikat') }}</flux:table.column>
                <flux:table.column align="center">{{ __('Status') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->rows as $item)
                    <flux:table.row wire:key="lembaga-{{ $item->id }}">
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:badge size="sm" color="emerald" class="font-bold">{{ $item->jenjang }}</flux:badge>
                                <span class="font-mono text-xs text-zinc-500">({{ $item->kode }})</span>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell variant="strong">
                            {{ $item->nama }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="text-xs">
                                <div>{{ $item->kepala_lembaga ?? '—' }}</div>
                                <div class="text-zinc-400">NSM: {{ $item->nsm ?? '—' }}</div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell align="center">
                            <div class="flex items-center justify-center gap-1.5 text-xs">
                                <flux:badge size="sm" color="zinc" title="Jumlah Kelas">{{ $item->kelas_count }} Kelas</flux:badge>
                                <flux:badge size="sm" color="zinc" title="Jumlah Santri">{{ $item->santris_count }} Santri</flux:badge>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell align="center">
                            <flux:badge size="sm" :color="$item->is_active ? 'green' : 'zinc'">
                                {{ $item->is_active ? __('Aktif') : __('Non-aktif') }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex justify-end gap-1">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $item->id }})" />
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-700"
                                    wire:click="delete({{ $item->id }})"
                                    wire:confirm="{{ __('Yakin ingin menghapus unit lembaga ini?') }}"
                                />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="py-10 text-center text-zinc-400">
                            {{ __('Belum ada data lembaga.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:modal name="lembaga-form" flyout class="md:w-[32rem]" @close="$set('editingId', null)">
        <form wire:submit="save" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? __('Edit Unit Lembaga') : __('Tambah Unit Lembaga Baru') }}</flux:heading>
                <flux:subheading>{{ __('Isi detail identitas unit pendidikan di bawah ini.') }}</flux:subheading>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:select wire:model="jenjang" :label="__('Jenjang Pendidikan')" placeholder="{{ __('Pilih Jenjang Pendidikan') }}">
                    @foreach ($this->defaultJenjangs as $j)
                        <flux:select.option value="{{ $j }}">{{ $j }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="kode" :label="__('Kode Unik (Slug)')" placeholder="mis. mi, mts, ma" />
            </div>

            <flux:input wire:model="nama" :label="__('Nama Lembaga')" placeholder="mis. Madrasah Ibtidaiyah (MI) Al-Hikmah" />

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="nsm" :label="__('NSM / NPSN (opsional)')" placeholder="121235..." />
                <flux:input wire:model="kepala_lembaga" :label="__('Kepala Lembaga')" placeholder="Nama Kepala Sekolah" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="telepon" :label="__('Telepon / WA')" placeholder="08xxxxxxxxxx" />
                <flux:input wire:model="urutan" type="number" min="1" :label="__('Urutan Tampilan')" />
            </div>

            <flux:textarea wire:model="alamat" :label="__('Alamat Unit (opsional)')" rows="2" />

            <div class="flex items-center gap-2">
                <flux:checkbox wire:model="is_active" :label="__('Status Aktif (Ditampilkan pada sistem & PPDB Online)')" />
            </div>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">{{ __('Simpan') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
