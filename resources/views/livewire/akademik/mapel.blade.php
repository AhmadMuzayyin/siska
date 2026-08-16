<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Mata Pelajaran') }}</flux:heading>
            <flux:subheading>{{ __('Kelola daftar mata pelajaran beserta kitab dan KKM per unit lembaga.') }}</flux:subheading>
        </div>

        <div class="flex items-center gap-2">
            <flux:modal.trigger name="import-mapel-modal">
                <flux:button variant="filled" icon="arrow-up-tray" class="bg-blue-600! hover:bg-blue-700! text-white! font-bold">
                    {{ __('Import Excel') }}
                </flux:button>
            </flux:modal.trigger>
            <flux:button variant="primary" icon="plus" wire:click="create" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                {{ __('Tambah Mapel') }}
            </flux:button>
        </div>
    </div>

    <flux:input
        wire:model.live.debounce.400ms="search"
        icon="magnifying-glass"
        placeholder="{{ __('Cari nama mapel...') }}"
        class="max-w-xs"
    />

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <flux:table :paginate="$this->rows">
            <flux:table.columns>
                <flux:table.column>{{ __('Lembaga') }}</flux:table.column>
                <flux:table.column>{{ __('Kode') }}</flux:table.column>
                <flux:table.column>{{ __('Nama Mapel') }}</flux:table.column>
                <flux:table.column>{{ __('Kitab') }}</flux:table.column>
                <flux:table.column align="center">{{ __('KKM') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->rows as $mapel)
                    <flux:table.row wire:key="mapel-{{ $mapel->id }}">
                        <flux:table.cell>
                            <flux:badge size="sm" color="emerald" class="font-bold">
                                {{ $mapel->lembaga?->jenjang ?? 'SEMUA' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" color="zinc" class="font-mono font-bold">
                                {{ $mapel->kode ?? 'MPL-'.$mapel->id }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell variant="strong">{{ $mapel->nama }}</flux:table.cell>
                        <flux:table.cell>
                            @if ($mapel->kitab)
                                {{ $mapel->kitab }}
                            @else
                                <flux:text class="text-zinc-400">&mdash;</flux:text>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell align="center">
                            <flux:badge size="sm" color="zinc">{{ $mapel->kkm }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex justify-end gap-1">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $mapel->id }})" />
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-700"
                                    wire:click="delete({{ $mapel->id }})"
                                    wire:confirm="{{ __('Yakin ingin menghapus mata pelajaran ini?') }}"
                                />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="py-10 text-center text-zinc-400">
                            {{ __('Belum ada data mata pelajaran.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:modal name="mapel-form" flyout class="md:w-96" @close="$set('editingId', null)">
        <form wire:submit="save" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? __('Edit Mapel') : __('Tambah Mapel') }}</flux:heading>
                <flux:subheading>{{ __('Nama, rujukan kitab, unit lembaga, dan nilai KKM.') }}</flux:subheading>
            </div>

            <flux:select wire:model="lembaga_id" :label="__('Unit Lembaga (Kosongkan jika Berlaku Semua)')" placeholder="{{ __('Pilih Unit Lembaga') }}">
                <flux:select.option value="">{{ __('Semua Lembaga') }}</flux:select.option>
                @foreach ($this->lembagaOptions as $lembaga)
                    <flux:select.option value="{{ $lembaga->id }}">{{ $lembaga->nama }} ({{ $lembaga->jenjang }})</flux:select.option>
                @endforeach
            </flux:select>

            <flux:input wire:model="nama" :label="__('Nama Mapel')" placeholder="mis. Fiqih" />
            <flux:input wire:model="kitab" :label="__('Kitab (opsional)')" placeholder="mis. Safinatun Najah" />
            <flux:input wire:model="kkm" type="number" min="0" max="100" :label="__('KKM')" />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">{{ __('Simpan') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="import-mapel-modal" flyout class="md:w-96">
        <form action="{{ route('import.excel', 'mapel') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
            @csrf
            <div>
                <flux:heading size="lg">{{ __('Import Data Mata Pelajaran') }}</flux:heading>
                <flux:subheading>{{ __('Unduh template spreadsheet dan unggah berkas .xlsx / .csv yang telah diisi.') }}</flux:subheading>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50 flex flex-col gap-2">
                <flux:text class="text-xs font-semibold text-zinc-600 dark:text-zinc-400">
                    {{ __('Unduh format berkas template:') }}
                </flux:text>
                <a href="{{ route('import.template', 'mapel') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-700">
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
