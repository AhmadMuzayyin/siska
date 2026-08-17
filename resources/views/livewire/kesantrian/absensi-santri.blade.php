<div class="flex flex-col gap-6">
    <div>
        <flux:heading size="xl">{{ __('Absensi Santri') }}</flux:heading>
        <flux:subheading>{{ __('Catat kehadiran santri per jadwal pelajaran.') }}</flux:subheading>
    </div>

    @if (!$this->activeSemester)
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 dark:border-amber-800/40 dark:bg-amber-950/30 dark:text-amber-300">
            <span class="font-semibold">{{ __('Semester belum aktif.') }}</span>
            {{ __('Absensi santri tidak dapat dicatat. Aktifkan semester di halaman') }}
            <a href="{{ route('akademik.tahun-akademik') }}" wire:navigate class="font-semibold underline underline-offset-2 hover:text-amber-600">{{ __('Tahun Akademik') }}</a>.
        </div>
    @else

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap items-center gap-3">
            <div class="w-72 sm:w-80">
                <x-select-search 
                    wire:model.live="jadwalId" 
                    :options="$this->jadwalSearchOptions" 
                    placeholder="{{ __('Pilih Jadwal Pelajaran') }}" 
                />
            </div>

            <flux:input wire:model.live="tanggal" type="date" class="max-w-44" />
        </div>

        <div class="w-full sm:w-auto">
            <flux:input wire:model.live.debounce.250ms="search" placeholder="{{ __('Cari santri...') }}" icon="magnifying-glass" class="w-full sm:w-64" />
        </div>
    </div>

    @if (!$jadwalId)
        <div class="rounded-xl border border-dashed border-zinc-300 p-10 text-center text-zinc-400 dark:border-zinc-700">
            {{ __('Pilih jadwal pelajaran terlebih dahulu untuk mengisi absensi.') }}
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('Nama Santri') }}</flux:table.column>
                    <flux:table.column>{{ __('No. Induk') }}</flux:table.column>
                    <flux:table.column align="end">{{ __('Status Kehadiran') }}</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($this->roster as $santri)
                        <flux:table.row wire:key="roster-{{ $santri->id }}">
                            <flux:table.cell variant="strong">{{ $santri->nama_lengkap }}</flux:table.cell>
                            <flux:table.cell>{{ $santri->noinduk }}</flux:table.cell>
                            <flux:table.cell align="end">
                                <flux:select
                                    size="sm"
                                    class="max-w-36"
                                    placeholder="{{ __('Pilih Status') }}"
                                    wire:change="setStatus({{ $santri->id }}, $event.target.value)"
                                >
                                    @foreach ($this->statuses as $status)
                                        <flux:select.option value="{{ $status->value }}" :selected="$santri->currentStatus === $status->value">
                                            {{ ucfirst($status->value) }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="3" class="py-10 text-center text-zinc-400">
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
