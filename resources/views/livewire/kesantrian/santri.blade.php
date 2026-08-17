<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Data Santri') }}</flux:heading>
            <flux:subheading>{{ __('Kelola data santri, persetujuan pendaftaran, dan kenaikan kelas per unit lembaga.') }}</flux:subheading>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <flux:button as="a" href="{{ route('export.excel', 'santri') }}" variant="filled" icon="arrow-down-tray" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                {{ __('Export Excel') }}
            </flux:button>
            <flux:button as="a" href="{{ route('export.pdf', 'santri') }}" target="_blank" variant="filled" icon="printer" class="bg-rose-600! hover:bg-rose-700! text-white! font-bold">
                {{ __('Export PDF') }}
            </flux:button>
            <flux:modal.trigger name="import-santri-modal">
                <flux:button variant="filled" icon="arrow-up-tray" class="bg-blue-600! hover:bg-blue-700! text-white! font-bold">
                    {{ __('Import Excel') }}
                </flux:button>
            </flux:modal.trigger>
            <flux:button variant="filled" icon="sparkles" x-on:click="$flux.modal('confirm-kenaikan-kelas-modal').show()" class="bg-amber-600! hover:bg-amber-700! text-white! font-bold">
                {{ __('Kenaikan Kelas') }}
            </flux:button>
            <flux:button variant="primary" icon="plus" wire:click="create" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                {{ __('Tambah Santri') }}
            </flux:button>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <flux:input
            wire:model.live.debounce.400ms="search"
            icon="magnifying-glass"
            placeholder="{{ __('Cari nama atau nomor induk...') }}"
            class="max-w-xs"
        />

        <div class="w-48">
            <x-select-search 
                wire:model.live="kelasFilter" 
                :options="$this->kelasFilterOptions" 
                placeholder="{{ __('Semua Kelas') }}" 
            />
        </div>

        <div class="w-44">
            <x-select-search 
                wire:model.live="statusFilter" 
                :options="$this->statusFilterOptions" 
                :searchable="false"
                placeholder="{{ __('Semua Status') }}" 
            />
        </div>
    </div>

    @if (count($selected) > 0)
        <div class="flex flex-wrap items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-800 dark:bg-emerald-950">
            <flux:text>{{ __(':count santri dipilih', ['count' => count($selected)]) }}</flux:text>
            <flux:select wire:model="promoteKelasId" class="max-w-48" placeholder="{{ __('Pilih Kelas Tujuan') }}">
                @foreach ($this->kelasOptions as $kelas)
                    <flux:select.option value="{{ $kelas->id }}">{{ $kelas->nama }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:button size="sm" variant="primary" wire:click="promote" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">{{ __('Promosikan Kelas') }}</flux:button>
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <flux:table :paginate="$this->rows">
            <flux:table.columns>
                <flux:table.column></flux:table.column>
                <flux:table.column>{{ __('Lembaga') }}</flux:table.column>
                <flux:table.column>{{ __('Nama Santri') }}</flux:table.column>
                <flux:table.column>{{ __('No. Induk') }}</flux:table.column>
                <flux:table.column>{{ __('Kelas') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->rows as $santri)
                    <flux:table.row wire:key="santri-{{ $santri->id }}">
                        <flux:table.cell class="py-0">
                            <flux:checkbox wire:model.live="selected" value="{{ $santri->id }}" />
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" color="emerald" class="font-bold">
                                {{ $santri->lembaga?->jenjang ?? $santri->kelas?->lembaga?->jenjang ?? 'GLOBAL' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell variant="strong">{{ $santri->nama_lengkap }}</flux:table.cell>
                        <flux:table.cell>{{ $santri->noinduk }}</flux:table.cell>
                        <flux:table.cell>{{ $santri->kelas->nama }}</flux:table.cell>
                        <flux:table.cell class="py-0">
                            <flux:badge size="sm" :color="match ($santri->status->value) {
                                'aktif' => 'green',
                                'pending_approval' => 'amber',
                                'lulus' => 'blue',
                                'keluar' => 'red',
                                default => 'zinc',
                            }">
                                {{ ucfirst(str_replace('_', ' ', $santri->status->value)) }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex justify-end gap-1">
                                @if ($santri->status->value === 'pending_approval')
                                    <flux:button size="sm" variant="ghost" icon="check" class="text-green-600 hover:text-green-700 font-bold" wire:click="approve({{ $santri->id }})">
                                        {{ __('Setujui') }}
                                    </flux:button>
                                @endif
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $santri->id }})" />
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-700 cursor-pointer"
                                    wire:click="$set('deletingId', {{ $santri->id }})"
                                    x-on:click="$flux.modal('confirm-delete-santri-modal').show()"
                                />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="7" class="py-10 text-center text-zinc-400">
                            {{ __('Belum ada data santri.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:modal name="santri-form" flyout class="md:w-[32rem]" @close="$set('editingId', null)">
        <form wire:submit="save" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? __('Edit Santri') : __('Tambah Santri') }}</flux:heading>
                <flux:subheading>{{ __('Lengkapi data santri di bawah ini.') }}</flux:subheading>
            </div>

            <flux:heading size="sm">{{ __('Data Pokok') }}</flux:heading>

            <x-select-search 
                wire:model.live="lembaga_id" 
                :options="$this->lembagaOptions->map(fn($l) => ['value' => $l->id, 'label' => $l->nama . ' (' . $l->jenjang . ')'])->toArray()" 
                label="{{ __('Unit Lembaga') }}" 
                placeholder="{{ __('Pilih Unit Lembaga') }}" 
            />

            <x-select-search 
                wire:model="kelas_id" 
                :options="$this->kelasOptions" 
                label="{{ __('Kelas') }}" 
                placeholder="{{ __('Pilih Kelas') }}" 
            />
            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="noinduk" :label="__('No. Induk')" />
                <flux:input wire:model="rfid_uid" :label="__('RFID UID (opsional)')" />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="nama_lengkap" :label="__('Nama Lengkap')" />
                <flux:input wire:model="nama_panggilan" :label="__('Nama Panggilan')" />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="tempat_lahir" :label="__('Tempat Lahir')" />
                <flux:input wire:model="tanggal_lahir" type="date" :label="__('Tanggal Lahir')" />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <flux:select wire:model="jenis_kelamin" :label="__('Jenis Kelamin')" placeholder="{{ __('Pilih Jenis Kelamin') }}">
                    @foreach ($this->genders as $gender)
                        <flux:select.option value="{{ $gender->value }}">
                            {{ $gender->value === 'laki_laki' ? __('Laki-laki') : __('Perempuan') }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="anak_ke" type="number" min="1" :label="__('Anak Ke-')" />
            </div>
            <flux:textarea wire:model="alamat" :label="__('Alamat')" rows="2" />
            <flux:input wire:model="telepon_wali" :label="__('No. WhatsApp Wali')" placeholder="08xxxxxxxxxx" />

            <flux:separator />

            <flux:heading size="sm">{{ __('Data Orang Tua') }}</flux:heading>
            <div class="grid grid-cols-3 gap-4">
                <flux:input wire:model="nama_ayah" :label="__('Nama Ayah')" />
                <flux:input wire:model="pendidikan_ayah" :label="__('Pendidikan')" />
                <flux:input wire:model="pekerjaan_ayah" :label="__('Pekerjaan')" />
            </div>
            <div class="grid grid-cols-3 gap-4">
                <flux:input wire:model="nama_ibu" :label="__('Nama Ibu')" />
                <flux:input wire:model="pendidikan_ibu" :label="__('Pendidikan')" />
                <flux:input wire:model="pekerjaan_ibu" :label="__('Pekerjaan')" />
            </div>

            <flux:separator />

            <flux:select wire:model="status" :label="__('Status')" placeholder="{{ __('Pilih Status') }}">
                @foreach ($this->statuses as $statusOption)
                    <flux:select.option value="{{ $statusOption->value }}">{{ ucfirst(str_replace('_', ' ', $statusOption->value)) }}</flux:select.option>
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

    <flux:modal name="import-santri-modal" flyout class="md:w-96">
        <form action="{{ route('import.excel', 'santri') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
            @csrf
            <div>
                <flux:heading size="lg">{{ __('Import Data Santri') }}</flux:heading>
                <flux:subheading>{{ __('Unduh template spreadsheet dan unggah berkas .xlsx / .csv yang telah diisi.') }}</flux:subheading>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50 flex flex-col gap-2">
                <flux:text class="text-xs font-semibold text-zinc-600 dark:text-zinc-400">
                    {{ __('Unduh format berkas template:') }}
                </flux:text>
                <a href="{{ route('import.template', 'santri') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-700">
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

    {{-- Confirm Delete Santri Modal --}}
    <x-confirm-modal 
        name="confirm-delete-santri-modal" 
        title="{{ __('Hapus Data Santri') }}" 
        description="{{ __('Apakah Anda yakin ingin menghapus data santri ini?') }}" 
        action="delete" 
        confirmText="{{ __('Hapus Santri') }}" 
    />

    {{-- Confirm Kenaikan Kelas Modal --}}
    <x-confirm-modal 
        name="confirm-kenaikan-kelas-modal" 
        title="{{ __('Proses Kenaikan Kelas Otomatis') }}" 
        description="{{ __('Sistem akan mengevaluasi rata-rata akumulasi nilai dan KKM seluruh santri untuk memproses kenaikan kelas. Lanjutkan?') }}" 
        action="processAutomaticKenaikanKelas" 
        variant="warning" 
        confirmText="{{ __('Ya, Proses Kenaikan') }}" 
    />
</div>
