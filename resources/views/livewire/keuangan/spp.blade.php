<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Pembayaran SPP') }}</flux:heading>
            <flux:subheading>{{ __('Catat dan pantau riwayat pembayaran SPP santri.') }}</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create" :disabled="!$this->activeSemester" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
            {{ __('Catat Pembayaran') }}
        </flux:button>
    </div>

    @if (!$this->activeSemester)
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 dark:border-amber-800/40 dark:bg-amber-950/30 dark:text-amber-300">
            <span class="font-semibold">{{ __('Semester belum aktif.') }}</span>
            {{ __('Pembayaran SPP tidak dapat dicatat. Aktifkan semester di halaman') }}
            <a href="{{ route('akademik.tahun-akademik') }}" wire:navigate class="font-semibold underline underline-offset-2 hover:text-amber-600">{{ __('Tahun Akademik') }}</a>.
        </div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <flux:select wire:model.live="kelasFilter" class="max-w-xs" placeholder="{{ __('Semua kelas') }}">
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
                <flux:table.column>{{ __('Bulan/Tahun') }}</flux:table.column>
                <flux:table.column>{{ __('Tanggal Bayar') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Nominal') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->rows as $spp)
                    <flux:table.row wire:key="spp-{{ $spp->id }}">
                        <flux:table.cell variant="strong">{{ $spp->santri->nama_lengkap }}</flux:table.cell>
                        <flux:table.cell>{{ $spp->santri->kelas->nama }}</flux:table.cell>
                        <flux:table.cell>{{ str_pad($spp->bulan, 2, '0', STR_PAD_LEFT) }}/{{ $spp->tahun }}</flux:table.cell>
                        <flux:table.cell>{{ $spp->tanggal->format('d M Y') }}</flux:table.cell>
                        <flux:table.cell align="end" variant="strong" class="text-emerald-700 dark:text-emerald-400">Rp{{ number_format($spp->nominal, 0, ',', '.') }}</flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="py-10 text-center text-zinc-400">
                            {{ __('Belum ada pembayaran SPP.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:modal name="spp-form" class="md:w-96">
        <form wire:submit="save" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ __('Catat Pembayaran SPP') }}</flux:heading>
                <flux:subheading>{{ __('Bulan dan tahun otomatis diambil dari tanggal bayar.') }}</flux:subheading>
            </div>

            <flux:select wire:model="santriId" :label="__('Santri')">
                @foreach ($this->santriOptions as $santri)
                    <flux:select.option value="{{ $santri->id }}">{{ $santri->nama_lengkap }} ({{ $santri->noinduk }})</flux:select.option>
                @endforeach
            </flux:select>

            <flux:input wire:model="tanggal" type="date" :label="__('Tanggal Bayar')" />
            <flux:input wire:model="nominal" type="number" min="1" :label="__('Nominal')" />

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">{{ __('Simpan') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
