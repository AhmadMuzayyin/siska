<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Nilai Santri') }}</flux:heading>
            <flux:subheading>{{ __('Input nilai per mata pelajaran, predikat dihitung otomatis.') }}</flux:subheading>
        </div>

        <div class="flex items-center gap-2">
            <flux:button as="a" href="{{ route('export.excel', 'nilai') }}" variant="filled" icon="arrow-down-tray" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                {{ __('Export Excel') }}
            </flux:button>
            <flux:button as="a" href="{{ route('export.pdf', 'nilai') }}" target="_blank" variant="filled" icon="printer" class="bg-rose-600! hover:bg-rose-700! text-white! font-bold">
                {{ __('Export PDF') }}
            </flux:button>
        </div>
    </div>

    @if (!$this->activeSemester)
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 dark:border-amber-800/40 dark:bg-amber-950/30 dark:text-amber-300">
            <span class="font-semibold">{{ __('Semester belum aktif.') }}</span>
            {{ __('Input nilai tidak dapat dilakukan. Aktifkan semester di halaman') }}
            <a href="{{ route('akademik.tahun-akademik') }}" wire:navigate class="font-semibold underline underline-offset-2 hover:text-amber-600">{{ __('Tahun Akademik') }}</a>.
        </div>
    @else

    @if (! app(\App\Services\KalenderAkademikService::class)->canInputNilai(now()->toDateString(), app(\App\Services\LembagaService::class)->getActiveLembagaId()))
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-900 dark:border-amber-800/40 dark:bg-amber-950/30 dark:text-amber-300 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <flux:icon name="lock-closed" class="size-5 text-amber-600 shrink-0" />
                <div>
                    <strong class="font-bold">{{ __('Penginputan Nilai Dikunci:') }}</strong>
                    {{ __('Penginputan nilai saat ini sedang dikunci oleh Administrator.') }}
                </div>
            </div>
            @can('update', app(\App\Services\SettingService::class)->get())
                <flux:button wire:click="toggleInputNilai" size="xs" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold shrink-0">
                    {{ __('Buka Akses Nilai Sekarang') }}
                </flux:button>
            @endcan
        </div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap items-center gap-3">
            <div class="w-60">
                <x-select-search 
                    wire:model.live="semesterId" 
                    :options="$this->semesterSearchOptions" 
                    placeholder="{{ __('Pilih Semester') }}" 
                />
            </div>

            <div class="w-48">
                <x-select-search 
                    wire:model.live="kelasId" 
                    :options="$this->kelasOptions" 
                    placeholder="{{ __('Pilih Kelas') }}" 
                />
            </div>

            <div class="w-52">
                <x-select-search 
                    wire:model.live="mapelId" 
                    :options="$this->mapelOptions" 
                    placeholder="{{ __('Pilih Mata Pelajaran') }}" 
                />
            </div>
        </div>

        <div class="w-full sm:w-auto">
            <flux:input wire:model.live.debounce.250ms="search" placeholder="{{ __('Cari santri...') }}" icon="magnifying-glass" class="w-full sm:w-64" />
        </div>
    </div>

    @if (! $kelasId || ! $mapelId)
        <div class="rounded-xl border border-dashed border-zinc-300 p-10 text-center text-zinc-400 dark:border-zinc-700">
            {{ __('Pilih kelas dan mata pelajaran untuk mulai menginput nilai.') }}
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('Nama Santri') }}</flux:table.column>
                    <flux:table.column align="center">{{ __('Nilai') }}</flux:table.column>
                    <flux:table.column align="center">{{ __('Predikat') }}</flux:table.column>
                    <flux:table.column align="center">{{ __('Keterangan') }}</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->roster as $santri)
                        <flux:table.row wire:key="nilai-{{ $santri->id }}">
                            <flux:table.cell variant="strong">{{ $santri->nama_lengkap }}</flux:table.cell>
                            <flux:table.cell align="center">
                                <flux:input
                                    size="sm"
                                    type="number"
                                    min="0"
                                    max="100"
                                    class="mx-auto max-w-20 text-center font-bold text-emerald-700 dark:text-emerald-400"
                                    value="{{ $santri->currentNilai }}"
                                    wire:change="setNilai({{ $santri->id }}, $event.target.value)"
                                />
                            </flux:table.cell>
                            <flux:table.cell align="center" class="py-0">
                                @if ($santri->currentPredikat)
                                    <flux:badge size="sm" color="zinc">{{ $santri->currentPredikat }}</flux:badge>
                                @else
                                    <flux:text class="text-zinc-400">&mdash;</flux:text>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell align="center" class="py-0">
                                @if (! is_null($santri->currentLulus))
                                    <flux:badge size="sm" :color="$santri->currentLulus ? 'green' : 'red'">
                                        {{ $santri->currentLulus ? __('Lulus KKM') : __('Belum KKM') }}
                                    </flux:badge>
                                @endif
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="4" class="py-10 text-center text-zinc-400">
                                {{ __('Tidak ada santri ditemukan.') }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    @endif
    @endif
</div>
