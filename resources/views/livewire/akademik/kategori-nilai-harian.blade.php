<div class="flex flex-col gap-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" class="text-2xl font-bold">{{ __('Kategori Nilai Harian Dinamis') }}</flux:heading>
            <flux:subheading class="mt-1">
                {{ __('Atur kriteria penilaian harian/karakter (Sikap, Kesopanan, Kedisiplinan, Hafalan) & pembobotan (%) per unit lembaga.') }}
            </flux:subheading>
        </div>

        <div>
            <flux:button variant="primary" icon="plus" wire:click="create">
                {{ __('Tambah Kategori Nilai') }}
            </flux:button>
        </div>
    </div>

    {{-- Filter Search & PerPage --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-between items-center bg-white dark:bg-zinc-900 p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-2xs">
        <div class="w-full sm:w-80">
            <flux:input 
                wire:model.live.debounce.300ms="search" 
                icon="magnifying-glass" 
                placeholder="Cari nama kategori nilai..." 
                clearable 
            />
        </div>

        <div class="flex items-center gap-2 text-xs text-zinc-500">
            <span>{{ __('Tampilkan:') }}</span>
            <flux:select wire:model.live="perPage" class="w-20!">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </flux:select>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="rounded-2xl border border-zinc-200 bg-white overflow-hidden shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 bg-zinc-50 text-zinc-600 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 font-bold uppercase tracking-wider">
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">{{ __('Kode') }}</th>
                        <th class="py-3 px-4">{{ __('Nama Kategori Kriteria') }}</th>
                        <th class="py-3 px-4 text-center">{{ __('Bobot (%)') }}</th>
                        <th class="py-3 px-4 text-center">{{ __('Status Wajib') }}</th>
                        <th class="py-3 px-4">{{ __('Unit Lembaga') }}</th>
                        <th class="py-3 px-4 text-right">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse ($this->rows as $index => $row)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/50 transition">
                            <td class="py-3 px-4 text-zinc-400 font-semibold">
                                {{ $this->rows->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-emerald-700 dark:text-emerald-400">
                                {{ $row->kode }}
                            </td>
                            <td class="py-3 px-4 font-extrabold text-zinc-900 dark:text-white">
                                {{ $row->nama }}
                                @if ($row->keterangan)
                                    <span class="block text-[11px] font-normal text-zinc-400 mt-0.5">{{ $row->keterangan }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-black text-xs dark:bg-emerald-950 dark:text-emerald-300">
                                    {{ $row->bobot }}%
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-md font-bold text-[11px] {{ $row->is_wajib ? 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400' }}">
                                    {{ $row->is_wajib ? __('Wajib') : __('Opsional') }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-zinc-600 dark:text-zinc-300 font-semibold">
                                {{ $row->lembaga?->nama ?? __('Semua Lembaga') }}
                            </td>
                            <td class="py-3 px-4 text-right space-x-1">
                                <flux:button variant="subtle" size="xs" icon="pencil-square" wire:click="edit({{ $row->id }})" />
                                <flux:button 
                                    variant="ghost" 
                                    size="xs" 
                                    icon="trash" 
                                    class="text-rose-600 hover:text-rose-700 cursor-pointer" 
                                    wire:click="$set('deletingId', {{ $row->id }})"
                                    x-on:click="$flux.modal('confirm-delete-kategori-modal').show()"
                                />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-xs text-zinc-400">
                                {{ __('Belum ada kategori nilai harian. Klik "Tambah Kategori Nilai" untuk membuat kriteria baru.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($this->rows->hasPages())
            <div class="p-4 border-t border-zinc-100 dark:border-zinc-800">
                {{ $this->rows->links() }}
            </div>
        @endif
    </div>

    {{-- Slide-Over Flyout Drawer Form --}}
    <flux:modal name="kategori-form" flyout class="space-y-6 max-w-lg">
        <div>
            <flux:heading size="lg" class="font-bold">
                {{ $editingId ? __('Edit Kategori Nilai Harian') : __('Tambah Kategori Nilai Harian') }}
            </flux:heading>
            <flux:subheading class="mt-1">
                {{ __('Masukkan nama kriteria (contoh: Sikap/Akhlaq, Kesopanan, Kedisiplinan, Hafalan) dan bobot persentase.') }}
            </flux:subheading>
        </div>

        <form wire:submit="save" class="space-y-4">
            @if (! app(\App\Services\LembagaService::class)->getActiveLembagaId())
                <flux:field>
                    <flux:label>{{ __('Unit Lembaga') }}</flux:label>
                    <flux:select wire:model="lembaga_id">
                        <option value="">-- {{ __('Semua Lembaga') }} --</option>
                        @foreach ($this->lembagas as $l)
                            <option value="{{ $l->id }}">{{ $l->nama }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="lembaga_id" />
                </flux:field>
            @endif
            <flux:field>
                <flux:label>{{ __('Nama Kategori Kriteria') }} <span class="text-rose-500">*</span></flux:label>
                <flux:input 
                    wire:model="nama" 
                    placeholder="misal: Sikap & Akhlaq, Kesopanan, Kedisiplinan" 
                    required 
                />
                <flux:error name="nama" />
            </flux:field>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Bobot Persentase (%)') }} <span class="text-rose-500">*</span></flux:label>
                    <flux:input 
                        type="number" 
                        wire:model="bobot" 
                        min="1" 
                        max="100" 
                        required 
                    />
                    <flux:error name="bobot" />
                </flux:field>

                <flux:field class="pt-6">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                        <input 
                            type="checkbox" 
                            wire:model="is_wajib" 
                            class="rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500 size-4"
                        />
                        <span>{{ __('Wajib diisi dalam penilaian') }}</span>
                    </label>
                </flux:field>
            </div>

            <flux:field>
                <flux:label>{{ __('Keterangan / Deskripsi Penilaian') }}</flux:label>
                <flux:textarea 
                    wire:model="keterangan" 
                    placeholder="Deskripsi singkat indikator atau aspek yang dinilai..." 
                    rows="2"
                />
                <flux:error name="keterangan" />
            </flux:field>

            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="primary" type="submit">
                    {{ __('Simpan Kategori') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Confirm Delete Kategori Modal --}}
    <x-confirm-modal 
        name="confirm-delete-kategori-modal" 
        title="{{ __('Hapus Kategori Nilai Harian') }}" 
        description="{{ __('Apakah Anda yakin ingin menghapus kategori kriteria penilaian ini?') }}" 
        action="delete" 
        confirmText="{{ __('Hapus Kategori') }}" 
    />
</div>
