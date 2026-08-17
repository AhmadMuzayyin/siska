<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Cetak Rapor Santri') }}</flux:heading>
            <flux:subheading>{{ __('Daftar santri dengan kelengkapan penginputan nilai dan fasilitas cetak rapor.') }}</flux:subheading>
        </div>

        <div class="flex items-center gap-2">
            <flux:modal.trigger name="setting-rapor-modal">
                <flux:button variant="filled" icon="cog-6-tooth" class="bg-zinc-800! hover:bg-zinc-900! text-white! font-bold">
                    {{ __('Setting Rapor') }}
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <flux:input
            wire:model.live.debounce.400ms="search"
            icon="magnifying-glass"
            placeholder="{{ __('Cari nama atau nomor induk...') }}"
            class="max-w-xs"
        />

        <div class="w-60">
            <x-select-search 
                wire:model.live="semesterId" 
                :options="$this->semesterSearchOptions" 
                placeholder="{{ __('Pilih Semester') }}" 
            />
        </div>

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
                placeholder="{{ __('Status Nilai') }}" 
            />
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Lembaga') }}</flux:table.column>
                <flux:table.column>{{ __('Nama Santri') }}</flux:table.column>
                <flux:table.column>{{ __('No. Induk / NISN') }}</flux:table.column>
                <flux:table.column>{{ __('Kelas') }}</flux:table.column>
                <flux:table.column align="center">{{ __('Kelengkapan Nilai') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Aksi') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->santriRoster as $santri)
                    <flux:table.row wire:key="santri-rapor-{{ $santri->id }}">
                        <flux:table.cell>
                            <flux:badge size="sm" color="emerald" class="font-bold">
                                {{ $santri->lembaga?->jenjang ?? $santri->kelas?->lembaga?->jenjang ?? 'GLOBAL' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell variant="strong">{{ $santri->nama_lengkap }}</flux:table.cell>
                        <flux:table.cell>{{ $santri->noinduk }} @if($santri->rfid_uid) <span class="text-xs text-zinc-400">({{ $santri->rfid_uid }})</span> @endif</flux:table.cell>
                        <flux:table.cell>{{ $santri->kelas->nama }}</flux:table.cell>
                        <flux:table.cell align="center" class="py-0">
                            @if ($santri->isLengkap)
                                <flux:badge size="sm" color="green" class="font-bold">
                                    <flux:icon name="check-circle" class="size-3.5 mr-1 inline" />
                                    {{ __('Nilai Lengkap (:count/:total Mapel)', ['count' => $santri->inputtedCount, 'total' => $santri->totalMapel]) }}
                                </flux:badge>
                            @else
                                <flux:badge size="sm" color="amber" class="font-bold">
                                    <flux:icon name="exclamation-triangle" class="size-3.5 mr-1 inline" />
                                    {{ __('Belum Lengkap (:count/:total Mapel)', ['count' => $santri->inputtedCount, 'total' => $santri->totalMapel]) }}
                                </flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <a href="{{ route('akademik.rapor.print', $santri->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold shadow-2xs transition-all {{ $santri->isLengkap ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-amber-100 text-amber-900 border border-amber-300 hover:bg-amber-200 dark:bg-amber-950/40 dark:text-amber-300' }}">
                                <flux:icon name="printer" class="size-3.5" />
                                {{ __('Cetak Rapor') }}
                            </a>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="py-10 text-center text-zinc-400">
                            {{ __('Belum ada data santri ditemukan.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    {{-- Settings Popover Drawer --}}
    <flux:modal name="setting-rapor-modal" flyout class="md:w-[38rem] space-y-6">
        <div class="space-y-1 border-b border-zinc-200 pb-4 dark:border-zinc-800">
            <flux:heading size="lg">{{ __('Setting Rapor & Template Word (.docx)') }}</flux:heading>
            <flux:subheading>{{ __('Kelola berkas template rapor Word dan deskripsi capaian nilai per unit lembaga.') }}</flux:subheading>
        </div>

        {{-- Tabs Unit Lembaga (jika lebih dari 1 atau global) --}}
        <div class="flex items-center gap-1.5 border-b border-zinc-200 dark:border-zinc-800 pb-3 overflow-x-auto">
            <button 
                type="button" 
                wire:click="$set('selectedLembagaId', null)" 
                class="px-3 py-1.5 text-xs font-bold rounded-lg transition-colors shrink-0 {{ is_null($selectedLembagaId) ? 'bg-emerald-600 text-white shadow-xs' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-300' }}"
            >
                {{ __('Semua Lembaga (Global)') }}
            </button>
            @foreach ($this->lembagaOptions as $lembaga)
                <button 
                    type="button" 
                    wire:click="$set('selectedLembagaId', {{ $lembaga->id }})" 
                    class="px-3 py-1.5 text-xs font-bold rounded-lg transition-colors shrink-0 {{ $selectedLembagaId == $lembaga->id ? 'bg-emerald-600 text-white shadow-xs' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-300' }}"
                >
                    {{ $lembaga->nama }} ({{ $lembaga->jenjang }})
                </button>
            @endforeach
        </div>

        <div class="space-y-6">
            {{-- Template Upload & Management --}}
            <div class="flex flex-col gap-4 rounded-xl border border-zinc-200 bg-zinc-50/50 p-4 dark:border-zinc-800 dark:bg-zinc-900/50">
                <div class="flex items-center justify-between">
                    <flux:heading size="sm">{{ __('Template Rapor (.docx / .xml / .html)') }}</flux:heading>
                    <flux:badge size="sm" color="blue">{{ __('.docx / .xml / .html') }}</flux:badge>
                </div>

                <flux:text class="text-xs text-zinc-500">
                    {{ __('Gunakan berkas Word (.docx), Word XML (.xml), atau HTML (.html) dengan placeholder tag seperti {nama}, {nisn}, {kelas}, {lembaga}, {tahun_akademik}, {semester}, {nilai}, {deskripsi}, serta {mapel_nama}, {mapel_kitab}, {mapel_nilai}, {mapel_predikat}, {mapel_deskripsi}.') }}
                </flux:text>

                @if ($currentTemplatePath)
                    <div class="flex items-center justify-between gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800 dark:border-emerald-800/40 dark:bg-emerald-950/30 dark:text-emerald-300">
                        <div class="flex items-center gap-2 truncate">
                            <flux:icon name="document-text" class="size-4 shrink-0 text-emerald-600" />
                            <span class="font-semibold truncate">{{ __('Template Tersedia') }} ({{ strtoupper(pathinfo($currentTemplatePath, PATHINFO_EXTENSION)) }})</span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ Storage::disk('public')->url($currentTemplatePath) }}" target="_blank" class="font-bold underline">{{ __('Download') }}</a>
                            <flux:modal.trigger name="confirm-delete-template">
                                <flux:button size="xs" variant="ghost" icon="trash" class="text-rose-600 hover:text-rose-700" />
                            </flux:modal.trigger>
                        </div>
                    </div>
                @else
                    <div class="rounded-lg border border-dashed border-zinc-300 p-3 text-center text-xs text-zinc-400 dark:border-zinc-700">
                        {{ __('Belum ada template rapor yang diunggah untuk unit ini.') }}
                    </div>
                @endif

                <form wire:submit="uploadTemplate" class="flex flex-col gap-3">
                    <flux:input wire:model="template_file" type="file" accept=".docx,.xml,.html,.htm,.blade.php" :label="__('Unggah Template Baru (.docx / .xml / .html)')" />
                    @error('template_file') <flux:text class="text-xs text-rose-500 font-semibold">{{ $message }}</flux:text> @enderror

                    <div class="flex justify-end">
                        <flux:button type="submit" variant="primary" size="sm" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                            {{ __('Unggah Template') }}
                        </flux:button>
                    </div>
                </form>
            </div>

            {{-- Deskripsi Nilai Mapel --}}
            <div class="flex flex-col gap-4 rounded-xl border border-zinc-200 bg-zinc-50/50 p-4 dark:border-zinc-800 dark:bg-zinc-900/50">
                <flux:heading size="sm">{{ __('Deskripsi Capaian Nilai per Mapel') }}</flux:heading>

                <flux:select wire:model.live="selectedMapelId" :label="__('Pilih Mata Pelajaran')" placeholder="{{ __('Pilih Mata Pelajaran') }}">
                    @foreach ($this->mapelOptions as $mapel)
                        <flux:select.option value="{{ $mapel->id }}">{{ $mapel->nama }} @if($mapel->lembaga) ({{ $mapel->lembaga->jenjang }}) @endif</flux:select.option>
                    @endforeach
                </flux:select>

                <form wire:submit="saveDeskripsi" class="flex flex-col gap-3">
                    <flux:textarea wire:model="deskripsi_a" :label="__('Deskripsi Predikat A (Sangat Baik)')" rows="2" placeholder="Capaian nilai A..." />
                    <flux:textarea wire:model="deskripsi_b" :label="__('Deskripsi Predikat B (Baik)')" rows="2" placeholder="Capaian nilai B..." />
                    <flux:textarea wire:model="deskripsi_c" :label="__('Deskripsi Predikat C (Cukup)')" rows="2" placeholder="Capaian nilai C..." />
                    <flux:textarea wire:model="deskripsi_d" :label="__('Deskripsi Predikat D (Kurang)')" rows="2" placeholder="Capaian nilai D..." />

                    <div class="flex justify-end">
                        <flux:button type="submit" variant="primary" size="sm" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                            {{ __('Simpan Deskripsi') }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </flux:modal>

    {{-- Confirm Delete Template Modal --}}
    <x-confirm-modal 
        name="confirm-delete-template" 
        title="{{ __('Hapus Template Rapor') }}" 
        description="{{ __('Apakah Anda yakin ingin menghapus berkas template rapor Word (.docx) ini?') }}" 
        action="deleteTemplate" 
        confirmText="{{ __('Hapus Template') }}" 
    />
</div>
