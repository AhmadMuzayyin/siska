<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Sumbangan Haflatul Imtihan') }}</flux:heading>
            <flux:subheading>{{ __('Kelola dan catat pembayaran uang sumbangan Haflatul Imtihan santri.') }}</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
            {{ __('Catat Pembayaran') }}
        </flux:button>
    </div>

    @if (!$this->activeSemester)
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 dark:border-amber-800/40 dark:bg-amber-950/30 dark:text-amber-300">
            <span class="font-semibold">{{ __('Semester belum aktif.') }}</span>
            {{ __('Pencatatan sumbangan memerlukan semester aktif.') }}
        </div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <flux:select wire:model.live="kelasFilter" class="max-w-xs" placeholder="{{ __('Pilih Kelas') }}">
            <flux:select.option value="">{{ __('Semua Kelas') }}</flux:select.option>
            @foreach ($this->kelasOptions as $kelas)
                <flux:select.option value="{{ $kelas->id }}">{{ $kelas->nama }}</flux:select.option>
            @endforeach
        </flux:select>

        <div class="w-full sm:w-auto">
            <flux:input wire:model.live.debounce.250ms="search" placeholder="{{ __('Cari nama santri...') }}" icon="magnifying-glass" class="w-full sm:w-64" />
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <flux:table :paginate="$this->rows">
            <flux:table.columns>
                <flux:table.column>{{ __('Santri') }}</flux:table.column>
                <flux:table.column>{{ __('Kelas') }}</flux:table.column>
                <flux:table.column>{{ __('Tanggal Bayar') }}</flux:table.column>
                <flux:table.column>{{ __('Metode') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Nominal') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->rows as $item)
                    <flux:table.row wire:key="haflatul-{{ $item->id }}">
                        <flux:table.cell variant="strong">{{ $item->santri->nama_lengkap }}</flux:table.cell>
                        <flux:table.cell>{{ $item->santri->kelas->nama }}</flux:table.cell>
                        <flux:table.cell>{{ $item->tanggal->format('d M Y') }}</flux:table.cell>
                        <flux:table.cell><flux:badge size="sm" color="zinc">{{ ucfirst($item->metode_pembayaran) }}</flux:badge></flux:table.cell>
                        <flux:table.cell align="end" variant="strong" class="text-emerald-700 dark:text-emerald-400">Rp{{ number_format($item->nominal, 0, ',', '.') }}</flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:button size="sm" variant="ghost" icon="trash" class="text-red-600 hover:text-red-700 cursor-pointer" wire:click="$set('deletingId', {{ $item->id }})" x-on:click="$flux.modal('confirm-delete-haflatul-modal').show()" />
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="py-10 text-center text-zinc-400">
                            {{ __('Belum ada pembayaran Haflatul Imtihan.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:modal name="haflatul-form" flyout class="md:w-96">
        <form wire:submit="save" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ __('Catat Sumbangan Haflatul Imtihan') }}</flux:heading>
                <flux:subheading>{{ __('Masukkan data pembayaran sumbangan acara imtihan.') }}</flux:subheading>
            </div>

            <x-select-search 
                wire:model="santriId" 
                :options="$this->santriOptions" 
                label="{{ __('Santri') }}" 
                placeholder="{{ __('Pilih Santri') }}" 
            />

            <flux:input wire:model="nominal" type="number" min="1000" :label="__('Nominal Sumbangan (Rp)')" />
            <flux:input wire:model="tanggal" type="date" :label="__('Tanggal Bayar')" />

            <flux:select wire:model="metode" :label="__('Metode Pembayaran')" placeholder="{{ __('Pilih Metode') }}">
                <flux:select.option value="cash">{{ __('Cash / Tunai') }}</flux:select.option>
                <flux:select.option value="transfer">{{ __('Transfer Bank') }}</flux:select.option>
                <flux:select.option value="qris">{{ __('QRIS') }}</flux:select.option>
            </flux:select>

            <flux:textarea wire:model="keterangan" :label="__('Keterangan (Opsional)')" rows="2" />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">{{ __('Simpan') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Confirm Delete Haflatul Modal --}}
    <x-confirm-modal 
        name="confirm-delete-haflatul-modal" 
        title="{{ __('Hapus Pembayaran Haflatul Imtihan') }}" 
        description="{{ __('Apakah Anda yakin ingin menghapus catatan pembayaran Haflatul Imtihan ini?') }}" 
        action="delete" 
        confirmText="{{ __('Hapus Pembayaran') }}" 
    />
</div>
