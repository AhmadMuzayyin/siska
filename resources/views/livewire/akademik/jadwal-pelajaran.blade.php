<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Jadwal Pelajaran') }}</flux:heading>
            <flux:subheading>{{ __('Atur jadwal mata pelajaran per kelas setiap semester.') }}</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create" :disabled="!$this->activeSemester" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
            {{ __('Tambah Jadwal') }}
        </flux:button>
    </div>

    @if (!$this->activeSemester)
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 dark:border-amber-800/40 dark:bg-amber-950/30 dark:text-amber-300">
            <span class="font-semibold">{{ __('Semester belum aktif.') }}</span>
            {{ __('Anda tidak dapat menambah atau mengubah jadwal pelajaran. Aktifkan semester terlebih dahulu di halaman') }}
            <a href="{{ route('akademik.tahun-akademik') }}" wire:navigate class="font-semibold underline underline-offset-2 hover:text-amber-600">{{ __('Tahun Akademik') }}</a>.
        </div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap items-center gap-3">
            <flux:select wire:model.live="semesterFilter" class="max-w-xs" placeholder="{{ __('Semua semester') }}">
                @foreach ($this->semesterOptions as $semester)
                    <flux:select.option value="{{ $semester->id }}" :selected="$semesterFilter == $semester->id">
                        {{ $semester->tahunAkademik->nama }} &mdash; {{ ucfirst($semester->tipe->value) }}
                        @if ($semester->is_aktif) ({{ __('Aktif') }}) @endif
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="kelasFilter" class="max-w-44" placeholder="{{ __('Semua kelas') }}">
                @foreach ($this->kelasOptions as $kelas)
                    <flux:select.option value="{{ $kelas->id }}">{{ $kelas->nama }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div class="w-full sm:w-auto">
            <flux:input wire:model.live.debounce.250ms="search" placeholder="{{ __('Cari mapel/guru...') }}" icon="magnifying-glass" class="w-full sm:w-64" />
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <flux:table :paginate="$this->rows">
            <flux:table.columns>
                <flux:table.column>{{ __('Hari') }}</flux:table.column>
                <flux:table.column>{{ __('Jam') }}</flux:table.column>
                <flux:table.column>{{ __('Kelas') }}</flux:table.column>
                <flux:table.column>{{ __('Mapel') }}</flux:table.column>
                <flux:table.column>{{ __('Guru') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->rows as $jadwal)
                    <flux:table.row wire:key="jadwal-{{ $jadwal->id }}">
                        <flux:table.cell>{{ ucfirst($jadwal->hari->value) }}</flux:table.cell>
                        <flux:table.cell>{{ substr($jadwal->jam_mulai, 0, 5) }}&ndash;{{ substr($jadwal->jam_selesai, 0, 5) }}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $jadwal->kelas->nama }}</flux:table.cell>
                        <flux:table.cell>{{ $jadwal->mapel->nama }}</flux:table.cell>
                        <flux:table.cell>{{ $jadwal->guru->user->name }}</flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex justify-end gap-1">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $jadwal->id }})" />
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-700"
                                    wire:click="delete({{ $jadwal->id }})"
                                    wire:confirm="{{ __('Yakin ingin menghapus jadwal ini?') }}"
                                />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="py-10 text-center text-zinc-400">
                            {{ __('Belum ada jadwal pelajaran.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <flux:modal name="jadwal-form" flyout class="md:w-96" @close="$set('editingId', null)">
        <form wire:submit="save" class="flex flex-col gap-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? __('Edit Jadwal') : __('Tambah Jadwal') }}</flux:heading>
                <flux:subheading>{{ __('Satu kelas hanya boleh satu mapel per hari & jam yang sama.') }}</flux:subheading>
            </div>

            <flux:select wire:model="semester_id" :label="__('Semester')">
                @foreach ($this->semesterOptions as $semester)
                    <flux:select.option value="{{ $semester->id }}">
                        {{ $semester->tahunAkademik->nama }} &mdash; {{ ucfirst($semester->tipe->value) }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:select wire:model="kelas_id" :label="__('Kelas')">
                @foreach ($this->kelasOptions as $kelas)
                    <flux:select.option value="{{ $kelas->id }}">{{ $kelas->nama }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select wire:model="mapel_id" :label="__('Mata Pelajaran')">
                @foreach ($this->mapelOptions as $mapel)
                    <flux:select.option value="{{ $mapel->id }}">{{ $mapel->nama }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select wire:model="guru_id" :label="__('Guru Pengajar')">
                @foreach ($this->guruOptions as $guru)
                    <flux:select.option value="{{ $guru->id }}">{{ $guru->user->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select wire:model="hari" :label="__('Hari')">
                @foreach ($this->hariOptions as $hari)
                    <flux:select.option value="{{ $hari->value }}">{{ ucfirst($hari->value) }}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="jam_mulai" type="time" :label="__('Jam Mulai')" />
                <flux:input wire:model="jam_selesai" type="time" :label="__('Jam Selesai')" />
            </div>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">{{ __('Simpan') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
