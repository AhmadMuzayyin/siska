<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Tahun Akademik & Semester') }}</flux:heading>
            <flux:subheading>{{ __('Kelola tahun akademik dan aktivasi semester berjalan per unit lembaga.') }}</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="createTahun" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
            {{ __('Tambah Tahun Akademik') }}
        </flux:button>
    </div>

    <div class="flex items-center gap-3">
        <flux:input
            wire:model.live.debounce.400ms="search"
            icon="magnifying-glass"
            placeholder="{{ __('Cari tahun akademik...') }}"
            class="max-w-xs"
        />
    </div>

    <div class="flex flex-col gap-4">
        @forelse ($this->rows as $tahun)
            <div wire:key="tahun-{{ $tahun->id }}" class="rounded-xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <flux:heading size="lg">{{ $tahun->nama }}</flux:heading>
                        <flux:badge size="sm" color="emerald" class="font-bold">
                            {{ $tahun->lembaga?->nama ? ($tahun->lembaga->nama . ' (' . $tahun->lembaga->jenjang . ')') : __('') }}
                        </flux:badge>
                    </div>

                    <div class="flex items-center gap-1">
                        <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="editTahun({{ $tahun->id }})" />
                        <flux:button
                            size="sm"
                            variant="ghost"
                            icon="trash"
                            class="text-red-600 hover:text-red-700 cursor-pointer"
                            wire:click="$set('deletingTahunId', {{ $tahun->id }})"
                            x-on:click="$flux.modal('confirm-delete-tahun-modal').show()"
                        />
                    </div>
                </div>

                <flux:separator class="my-4" />

                <div class="flex flex-wrap items-center gap-3">
                    @forelse ($tahun->semesters as $semester)
                        <div wire:key="semester-{{ $semester->id }}" class="flex items-center gap-2 rounded-lg border border-zinc-200 px-3 py-2 dark:border-zinc-700">
                            <flux:badge size="sm" :color="$semester->is_aktif ? 'green' : 'zinc'">
                                {{ ucfirst($semester->tipe->value) }}
                            </flux:badge>
                            <flux:text class="text-xs text-zinc-500">
                                {{ $semester->mulai->format('d M Y') }} &ndash; {{ $semester->selesai->format('d M Y') }}
                            </flux:text>

                            @if ($semester->is_aktif)
                                <flux:badge size="sm" color="green" icon="check">{{ __('Aktif') }}</flux:badge>
                            @else
                                <flux:button size="sm" variant="ghost" wire:click="activateSemester({{ $semester->id }})">
                                    {{ __('Aktifkan') }}
                                </flux:button>
                            @endif

                            <flux:button
                                size="sm"
                                variant="ghost"
                                icon="trash"
                                class="text-red-600 hover:text-red-700 cursor-pointer"
                                wire:click="$set('deletingSemesterId', {{ $semester->id }})"
                                x-on:click="$flux.modal('confirm-delete-semester-modal').show()"
                            />
                        </div>
                    @empty
                        <flux:text class="text-zinc-400">{{ __('Belum ada semester.') }}</flux:text>
                    @endforelse

                    @if ($tahun->semesters->count() < 2)
                        <flux:button size="sm" variant="ghost" icon="plus" wire:click="createSemester({{ $tahun->id }})">
                            {{ __('Tambah Semester') }}
                        </flux:button>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-zinc-300 p-10 text-center text-zinc-400 dark:border-zinc-700">
                {{ __('Belum ada tahun akademik.') }}
            </div>
        @endforelse

        {{ $this->rows->links() }}
    </div>

    <flux:modal name="tahun-form" flyout class="md:w-96" @close="$set('editingTahunId', null)">
        <form wire:submit="saveTahun" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ $editingTahunId ? __('Edit Tahun Akademik') : __('Tambah Tahun Akademik') }}</flux:heading>
                <flux:subheading>{{ __('Format penamaan: 2025/2026 atau 1447 H') }}</flux:subheading>
            </div>

            <flux:select wire:model="lembaga_id" :label="__('Unit Lembaga (Kosongkan jika Berlaku Semua)')" placeholder="{{ __('Pilih Unit Lembaga') }}">
                <flux:select.option value="">{{ __('Semua Lembaga') }}</flux:select.option>
                @foreach ($this->lembagaOptions as $l)
                    <flux:select.option value="{{ $l->id }}">{{ $l->nama }} ({{ $l->jenjang }})</flux:select.option>
                @endforeach
            </flux:select>

            <flux:input wire:model="nama" :label="__('Nama Tahun Akademik')" placeholder="2025/2026" />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">{{ __('Simpan') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="semester-form" flyout class="md:w-96" @close="$set('semesterTahunAkademikId', null)">
        <form wire:submit="saveSemester" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ __('Tambah Semester') }}</flux:heading>
                <flux:subheading>{{ __('Maksimal 2 semester per tahun akademik.') }}</flux:subheading>
            </div>

            <flux:select wire:model="tipe" :label="__('Tipe Semester')" placeholder="{{ __('Pilih Tipe Semester') }}">
                @foreach ($this->semesterTypes as $type)
                    <flux:select.option value="{{ $type->value }}">{{ ucfirst($type->value) }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:input wire:model="mulai" type="date" :label="__('Tanggal Mulai')" />
            <flux:input wire:model="selesai" type="date" :label="__('Tanggal Selesai')" />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">{{ __('Simpan') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Confirm Delete Tahun Modal --}}
    <x-confirm-modal 
        name="confirm-delete-tahun-modal" 
        title="{{ __('Hapus Tahun Akademik') }}" 
        description="{{ __('Apakah Anda yakin ingin menghapus tahun akademik ini?') }}" 
        action="deleteTahun" 
        confirmText="{{ __('Hapus Tahun') }}" 
    />

    {{-- Confirm Delete Semester Modal --}}
    <x-confirm-modal 
        name="confirm-delete-semester-modal" 
        title="{{ __('Hapus Semester') }}" 
        description="{{ __('Apakah Anda yakin ingin menghapus semester ini?') }}" 
        action="deleteSemester" 
        confirmText="{{ __('Hapus Semester') }}" 
    />
</div>
