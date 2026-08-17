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
            
            <flux:badge size="sm" color="zinc" class="font-semibold">
                {{ __('Hari:') }} {{ ucfirst($this->hariSekolah->value) }}
            </flux:badge>
        </div>

        <div class="w-full sm:w-auto">
            <flux:input wire:model.live.debounce.250ms="search" placeholder="{{ __('Cari guru...') }}" icon="magnifying-glass" class="w-full sm:w-64" />
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Nama Guru') }}</flux:table.column>
                <flux:table.column>{{ __('Jadwal Mengajar Hari Ini') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Status Kehadiran') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->roster as $guru)
                    <flux:table.row wire:key="absensi-guru-{{ $guru->id }}">
                        <flux:table.cell variant="strong">
                            <div class="font-bold text-zinc-900 dark:text-white">{{ $guru->user->name }}</div>
                            <div class="text-[11px] text-zinc-500">{{ $guru->nip ? 'NIP: '.$guru->nip : 'Guru' }}</div>
                        </flux:table.cell>

                        <flux:table.cell>
                            @if ($guru->hasSchedule)
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($guru->schedules as $sch)
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 px-2 py-1 text-xs font-semibold text-emerald-800 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                            <flux:icon name="academic-cap" class="size-3 text-emerald-600 dark:text-emerald-400" />
                                            <span>{{ $sch->kelas->nama }} &middot; {{ $sch->mapel->nama }}</span>
                                            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-mono">({{ substr($sch->jam_mulai, 0, 5) }}-{{ substr($sch->jam_selesai, 0, 5) }})</span>
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <flux:badge size="sm" color="zinc">
                                    {{ __('Tidak Ada Jadwal') }}
                                </flux:badge>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell align="end">
                            @if ($guru->recordedStatus)
                                <flux:badge size="sm" :color="$guru->recordedStatus === 'hadir' ? 'green' : ($guru->recordedStatus === 'alpa' ? 'red' : 'amber')" class="font-bold uppercase tracking-wider">
                                    {{ ucfirst($guru->recordedStatus) }}
                                </flux:badge>
                            @elseif (!$guru->hasSchedule)
                                <span class="text-xs text-zinc-400 dark:text-zinc-500 italic">
                                    {{ __('Tidak dapat diisi (Tanpa Jadwal)') }}
                                </span>
                            @else
                                <div class="flex justify-end gap-1">
                                    <flux:button 
                                        size="xs" 
                                        variant="ghost" 
                                        wire:click="setStatus({{ $guru->id }}, 'hadir')" 
                                        class="hover:bg-emerald-50! hover:text-emerald-700! font-bold"
                                    >
                                        {{ __('Hadir') }}
                                    </flux:button>
                                    <flux:button 
                                        size="xs" 
                                        variant="ghost" 
                                        wire:click="setStatus({{ $guru->id }}, 'izin')" 
                                        class="hover:bg-blue-50! hover:text-blue-700!"
                                    >
                                        {{ __('Izin') }}
                                    </flux:button>
                                    <flux:button 
                                        size="xs" 
                                        variant="ghost" 
                                        wire:click="setStatus({{ $guru->id }}, 'sakit')" 
                                        class="hover:bg-amber-50! hover:text-amber-700!"
                                    >
                                        {{ __('Sakit') }}
                                    </flux:button>
                                    <flux:button 
                                        size="xs" 
                                        variant="ghost" 
                                        wire:click="setStatus({{ $guru->id }}, 'alpa')" 
                                        class="hover:bg-rose-50! hover:text-rose-700!"
                                    >
                                        {{ __('Alpa') }}
                                    </flux:button>
                                </div>
                            @endif
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="py-10 text-center text-zinc-400">
                            {{ __('Tidak ada guru ditemukan.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>
    @endif
</div>
