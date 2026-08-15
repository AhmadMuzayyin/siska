<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Tabungan Santri') }}</flux:heading>
            <flux:subheading>{{ __('Kelola setoran dan penarikan tabungan santri secara terpusat.') }}</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
            {{ __('Tambah Transaksi') }}
        </flux:button>
    </div>

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
                <flux:table.column>{{ __('Tanggal') }}</flux:table.column>
                <flux:table.column>{{ __('Tipe') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Nominal') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Saldo Akhir') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->rows as $item)
                    <flux:table.row wire:key="tabungan-{{ $item->id }}">
                        <flux:table.cell variant="strong">{{ $item->santri->nama_lengkap }}</flux:table.cell>
                        <flux:table.cell>{{ $item->santri->kelas->nama }}</flux:table.cell>
                        <flux:table.cell>{{ $item->tanggal->format('d M Y') }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" :color="$item->tipe === 'setor' ? 'green' : 'amber'">
                                {{ $item->tipe === 'setor' ? __('Setor') : __('Tarik') }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end" variant="strong" class="{{ $item->tipe === 'setor' ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-400' }}">
                            {{ $item->tipe === 'setor' ? '+' : '-' }}Rp{{ number_format($item->nominal, 0, ',', '.') }}
                        </flux:table.cell>
                        <flux:table.cell align="end" variant="strong">
                            Rp{{ number_format($item->saldo_akhir, 0, ',', '.') }}
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:button size="sm" variant="ghost" icon="trash" class="text-red-600 hover:text-red-700" wire:click="delete({{ $item->id }})" wire:confirm="{{ __('Yakin hapus transaksi tabungan ini?') }}" />
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="7" class="py-10 text-center text-zinc-400">
                            {{ __('Belum ada transaksi tabungan santri.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:modal name="tabungan-form" flyout class="md:w-96">
        <form wire:submit="save" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ __('Transaksi Tabungan Santri') }}</flux:heading>
                <flux:subheading>{{ __('Setor atau tarik tabungan santri.') }}</flux:subheading>
            </div>

            <flux:select wire:model="santriId" :label="__('Santri')" placeholder="{{ __('Pilih Santri') }}">
                @foreach ($this->santriOptions as $santri)
                    <flux:select.option value="{{ $santri->id }}">{{ $santri->nama_lengkap }} ({{ $santri->noinduk }})</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select wire:model="tipe" :label="__('Tipe Transaksi')" placeholder="{{ __('Pilih Tipe') }}">
                <flux:select.option value="setor">{{ __('Setoran (+)') }}</flux:select.option>
                <flux:select.option value="tarik">{{ __('Penarikan (-)') }}</flux:select.option>
            </flux:select>

            <flux:input wire:model="nominal" type="number" min="1000" :label="__('Nominal Transaksi (Rp)')" />
            <flux:input wire:model="tanggal" type="date" :label="__('Tanggal')" />

            <flux:textarea wire:model="keterangan" :label="__('Keterangan (Opsional)')" rows="2" />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">{{ __('Simpan') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
