<div class="flex flex-col gap-6">
    <div>
        <flux:heading size="xl">{{ __('Absensi Guru') }}</flux:heading>
        <flux:subheading>{{ __('Catat kehadiran harian guru. Absensi yang sudah tersimpan tidak dapat diubah dari sini.') }}</flux:subheading>
    </div>

    @if (!$this->activeSemester)
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 dark:border-amber-800/40 dark:bg-amber-950/30 dark:text-amber-300">
            <span class="font-semibold">{{ __('Semester belum aktif.') }}</span>
            {{ __('Absensi guru tidak dapat dicatat. Aktifkan semester di halaman') }}
            <a href="{{ route('akademik.tahun-akademik') }}" wire:navigate class="font-semibold underline underline-offset-2 hover:text-amber-600">{{ __('Tahun Akademik') }}</a>.
        </div>
    @else

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap items-center gap-3">
            <flux:select wire:model.live="semesterId" class="max-w-sm" placeholder="{{ __('Pilih Semester') }}">
                @foreach ($this->semesterOptions as $semester)
                    <flux:select.option value="{{ $semester->id }}" :selected="$semesterId == $semester->id">
                        {{ $semester->tahunAkademik->nama }} &mdash; {{ ucfirst($semester->tipe->value) }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:input wire:model.live="tanggal" type="date" class="max-w-44" />
        </div>

        <div class="w-full sm:w-auto">
            <flux:input wire:model.live.debounce.250ms="search" placeholder="{{ __('Cari guru...') }}" icon="magnifying-glass" class="w-full sm:w-64" />
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Nama Guru') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Status Kehadiran') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->roster as $guru)
                    <flux:table.row wire:key="absensi-guru-{{ $guru->id }}">
                        <flux:table.cell variant="strong">{{ $guru->user->name }}</flux:table.cell>
                        <flux:table.cell align="end">
                            @if ($guru->recordedStatus)
                                <flux:badge size="sm" :color="$guru->recordedStatus === 'hadir' ? 'green' : 'amber'">
                                    {{ ucfirst($guru->recordedStatus) }}
                                </flux:badge>
                            @else
                                <flux:select
                                    size="sm"
                                    class="max-w-36"
                                    placeholder="{{ __('Pilih Status') }}"
                                    wire:change="setStatus({{ $guru->id }}, $event.target.value)"
                                >
                                    @foreach ($this->statuses as $status)
                                        <flux:select.option value="{{ $status->value }}">{{ ucfirst($status->value) }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                            @endif
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="2" class="py-10 text-center text-zinc-400">
                            {{ __('Tidak ada guru ditemukan.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>
    @endif
</div>
