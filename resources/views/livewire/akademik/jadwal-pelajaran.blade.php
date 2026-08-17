<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Jadwal Pelajaran') }}</flux:heading>
            <flux:subheading>{{ __('Atur jadwal mata pelajaran per kelas setiap semester.') }}</flux:subheading>
        </div>

        <div class="flex items-center gap-2">
            <flux:modal.trigger name="import-jadwal-modal">
                <flux:button variant="filled" icon="arrow-up-tray" class="bg-blue-600! hover:bg-blue-700! text-white! font-bold">
                    {{ __('Import Excel') }}
                </flux:button>
            </flux:modal.trigger>
            <flux:button variant="primary" icon="plus" wire:click="create" :disabled="!$this->activeSemester" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                {{ __('Tambah Jadwal') }}
            </flux:button>
        </div>
    </div>

    @if (!$this->activeSemester)
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 dark:border-amber-800/40 dark:bg-amber-950/30 dark:text-amber-300">
            <span class="font-semibold">{{ __('Semester belum aktif.') }}</span>
            {{ __('Anda tidak dapat menambah atau mengubah jadwal pelajaran. Aktifkan semester terlebih dahulu di halaman') }}
            <a href="{{ route('akademik.tahun-akademik') }}" wire:navigate class="font-semibold underline underline-offset-2 hover:text-amber-600">{{ __('Tahun Akademik') }}</a>.
        </div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap items-center gap-3">
            <div class="w-60">
                <x-select-search 
                    wire:model.live="semesterFilter" 
                    :options="$this->semesterFilterOptions" 
                    placeholder="{{ __('Semua Semester') }}" 
                />
            </div>

            <div class="w-48">
                <x-select-search 
                    wire:model.live="kelasFilter" 
                    :options="$this->kelasFilterOptions" 
                    placeholder="{{ __('Semua Kelas') }}" 
                />
            </div>
        </div>

        <div class="w-full sm:w-auto">
            <flux:input wire:model.live.debounce.250ms="search" placeholder="{{ __('Cari mapel/guru...') }}" icon="magnifying-glass" class="w-full sm:w-64" />
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <flux:table :paginate="$this->rows">
            <flux:table.columns>
                <flux:table.column>{{ __('Hari') }}</flux:table.column>
                <flux:table.column>{{ __('Jam') }}</flux:table.column>
                <flux:table.column>{{ __('Kelas') }}</flux:table.column>
                <flux:table.column>{{ __('Mapel') }}</flux:table.column>
                <flux:table.column>{{ __('Guru') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->rows as $jadwal)
                    <flux:table.row wire:key="jadwal-{{ $jadwal->id }}">
                        <flux:table.cell>{{ ucfirst($jadwal->hari->value) }}</flux:table.cell>
                        <flux:table.cell>{{ substr($jadwal->jam_mulai, 0, 5) }}&ndash;{{ substr($jadwal->jam_selesai, 0, 5) }}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $jadwal->kelas->nama }}</flux:table.cell>
                        <flux:table.cell>{{ $jadwal->mapel->nama }}</flux:table.cell>
                        <flux:table.cell>{{ $jadwal->guru->user->name }}</flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex justify-end gap-1">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $jadwal->id }})" />
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-700 cursor-pointer"
                                    wire:click="$set('deletingId', {{ $jadwal->id }})"
                                    x-on:click="$flux.modal('confirm-delete-jadwal-modal').show()"
                                />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="py-10 text-center text-zinc-400">
                            {{ __('Belum ada jadwal pelajaran.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:modal name="jadwal-form" flyout class="md:w-96" @close="$set('editingId', null)">
        <form wire:submit="save" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? __('Edit Jadwal') : __('Tambah Jadwal') }}</flux:heading>
                <flux:subheading>{{ __('Satu kelas hanya boleh satu mapel per hari & jam yang sama.') }}</flux:subheading>
            </div>

            <x-select-search 
                wire:model="semester_id" 
                :options="$this->semesterOptions->map(fn($s) => ['value' => $s->id, 'label' => $s->tahunAkademik->nama . ' — ' . ucfirst($s->tipe->value)])->toArray()" 
                label="{{ __('Semester') }}" 
                placeholder="{{ __('Pilih Semester') }}" 
            />

            <x-select-search 
                wire:model="kelas_id" 
                :options="$this->kelasOptions" 
                label="{{ __('Kelas') }}" 
                placeholder="{{ __('Pilih Kelas') }}" 
            />

            <x-select-search 
                wire:model="mapel_id" 
                :options="$this->mapelOptions" 
                label="{{ __('Mata Pelajaran') }}" 
                placeholder="{{ __('Pilih Mata Pelajaran') }}" 
            />

            <x-select-search 
                wire:model="guru_id" 
                :options="$this->guruSearchOptions" 
                label="{{ __('Guru Pengajar') }}" 
                placeholder="{{ __('Pilih Guru Pengajar') }}" 
            />

            <x-select-search 
                wire:model="hari" 
                :options="$this->hariSearchOptions" 
                :searchable="false"
                label="{{ __('Hari') }}" 
                placeholder="{{ __('Pilih Hari') }}" 
            />

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="jam_mulai" type="time" :label="__('Jam Mulai')" />
                <flux:input wire:model="jam_selesai" type="time" :label="__('Jam Selesai')" />
            </div>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">{{ __('Simpan') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="import-jadwal-modal" flyout class="md:w-96">
        <form action="{{ route('import.excel', 'jadwal') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
            @csrf
            <div>
                <flux:heading size="lg">{{ __('Import Jadwal Pelajaran') }}</flux:heading>
                <flux:subheading>{{ __('Unduh template spreadsheet dan unggah berkas .xlsx / .csv yang telah diisi.') }}</flux:subheading>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50 flex flex-col gap-2">
                <flux:text class="text-xs font-semibold text-zinc-600 dark:text-zinc-400">
                    {{ __('Unduh format berkas template:') }}
                </flux:text>
                <a href="{{ route('import.template', 'jadwal') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-700">
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

    {{-- Confirm Delete Jadwal Modal --}}
    <x-confirm-modal 
        name="confirm-delete-jadwal-modal" 
        title="{{ __('Hapus Jadwal Pelajaran') }}" 
        description="{{ __('Apakah Anda yakin ingin menghapus jadwal pelajaran ini?') }}" 
        action="delete" 
        confirmText="{{ __('Hapus Jadwal') }}" 
    />
</div>
