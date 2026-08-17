<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Gaji Guru') }}</flux:heading>
            <flux:subheading>{{ __('Hitung bisyaroh guru berdasarkan kehadiran bulanan.') }}</flux:subheading>
        </div>

        <div class="flex items-center gap-2 rounded-xl border border-zinc-200 bg-zinc-50 px-3.5 py-2 text-xs text-zinc-600 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
            <flux:icon name="calendar" class="size-4 text-emerald-600 dark:text-emerald-400" />
            <span>{{ __('Tanggal Cutoff Payroll:') }} <strong class="font-bold text-zinc-900 dark:text-white">{{ __('Tgl :day setiap bulan', ['day' => $this->payrollCutoffDay]) }}</strong></span>
        </div>
    </div>

    @if (!$this->activeSemester)
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 dark:border-amber-800/40 dark:bg-amber-950/30 dark:text-amber-300">
            <span class="font-semibold">{{ __('Semester belum aktif.') }}</span>
            {{ __('Perhitungan gaji tidak dapat dilakukan. Aktifkan semester di halaman') }}
            <a href="{{ route('akademik.tahun-akademik') }}" wire:navigate class="font-semibold underline underline-offset-2 hover:text-amber-600">{{ __('Tahun Akademik') }}</a>.
        </div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="flex flex-wrap items-end gap-3">
            <flux:select wire:model.live="semesterId" :label="__('Semester')" placeholder="{{ __('Pilih Semester') }}" class="max-w-xs">
                @foreach ($this->semesterOptions as $semester)
                    <flux:select.option value="{{ $semester->id }}" :selected="$semesterId == $semester->id">
                        {{ $semester->tahunAkademik->nama }} &mdash; {{ ucfirst($semester->tipe->value) }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:input wire:model.live="bulan" type="number" min="1" max="12" :label="__('Bulan')" class="max-w-20" />
            <flux:input wire:model.live="tahun" type="number" min="2020" max="2100" :label="__('Tahun')" class="max-w-24" />
            <flux:input wire:model.live="bisyaroh" type="number" min="0" :label="__('Bisyaroh Pokok')" class="max-w-36" />
        </div>

        <div class="w-full sm:w-auto">
            <flux:input wire:model.live.debounce.250ms="search" placeholder="{{ __('Cari guru...') }}" icon="magnifying-glass" class="w-full sm:w-64" />
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Nama Guru') }}</flux:table.column>
                <flux:table.column align="center">{{ __('Hari Hadir') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Total Gaji') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->roster as $guru)
                    <flux:table.row wire:key="gaji-{{ $guru->id }}">
                        <flux:table.cell variant="strong">{{ $guru->user->name }}</flux:table.cell>
                        <flux:table.cell align="center">{{ $guru->jumlahHadir ?? '—' }}</flux:table.cell>
                        <flux:table.cell align="end" class="font-bold text-emerald-700 dark:text-emerald-400">
                            @if (! is_null($guru->totalGaji))
                                Rp{{ number_format($guru->totalGaji, 0, ',', '.') }}
                            @else
                                <flux:text class="text-zinc-400">&mdash;</flux:text>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:button size="sm" variant="ghost" icon="calculator" wire:click="generate({{ $guru->id }})" :disabled="!$this->activeSemester">
                                {{ $guru->totalGaji ? __('Hitung Ulang') : __('Hitung') }}
                            </flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="py-10 text-center text-zinc-400">
                            {{ __('Tidak ada guru ditemukan.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>
</div>
