<div class="flex flex-col gap-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" class="text-2xl font-bold">{{ __('Input Nilai Harian Santri') }}</flux:heading>
            <flux:subheading class="mt-1">
                {{ __('Isi skor penilaian harian (Sikap, Kesopanan, Kedisiplinan, Hafalan) per kelas dan per kategori kriteria secara cepat.') }}
            </flux:subheading>
        </div>
    </div>

    {{-- Filter Selector Bar --}}
    <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-xs space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Kelas Selector --}}
            <div class="space-y-1.5">
                <flux:label>{{ __('Pilih Kelas') }} <span class="text-rose-500">*</span></flux:label>
                <x-select-search 
                    wire:model.live="kelas_id" 
                    :options="$this->kelasList" 
                    placeholder="{{ __('Pilih Kelas') }}" 
                />
            </div>

            {{-- Kategori Nilai Selector --}}
            <div class="space-y-1.5">
                <flux:label>{{ __('Kategori Kriteria Nilai') }} <span class="text-rose-500">*</span></flux:label>
                <x-select-search 
                    wire:model.live="kategori_nilai_harian_id" 
                    :options="$this->kategoriSearchOptions" 
                    placeholder="{{ __('Pilih Kategori') }}" 
                />
            </div>

            {{-- Semester Selector --}}
            <div class="space-y-1.5">
                <flux:label>{{ __('Semester') }} <span class="text-rose-500">*</span></flux:label>
                <x-select-search 
                    wire:model.live="semester_id" 
                    :options="$this->semesterSearchOptions" 
                    placeholder="{{ __('Pilih Semester') }}" 
                />
            </div>

            {{-- Tanggal Penilaian --}}
            <div class="space-y-1.5">
                <flux:label>{{ __('Tanggal Penilaian') }} <span class="text-rose-500">*</span></flux:label>
                <flux:input type="date" wire:model.live="tanggal" />
            </div>
        </div>
    </div>

    {{-- Batch Form Table --}}
    <form wire:submit="save" class="space-y-4">
        <div class="rounded-2xl border border-zinc-200 bg-white overflow-hidden shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-200 bg-zinc-50 text-zinc-600 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 font-bold uppercase tracking-wider">
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">{{ __('NIS') }}</th>
                            <th class="py-3 px-4">{{ __('Nama Santri') }}</th>
                            <th class="py-3 px-4 w-40 text-center">{{ __('Skor Nilai (0-100)') }} <span class="text-rose-500">*</span></th>
                            <th class="py-3 px-4">{{ __('Catatan / Keterangan Khusus') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse ($this->santriList as $index => $santri)
                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/50 transition">
                                <td class="py-3 px-4 text-zinc-400 font-semibold">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-3 px-4 font-mono text-zinc-600 dark:text-zinc-400 font-bold">
                                    {{ $santri->noinduk }}
                                </td>
                                <td class="py-3 px-4 font-extrabold text-zinc-900 dark:text-white">
                                    {{ $santri->nama_lengkap }}
                                </td>
                                <td class="py-2 px-4 text-center">
                                    <input 
                                        type="number" 
                                        wire:model="scores.{{ $santri->id }}.nilai" 
                                        min="0" 
                                        max="100" 
                                        required 
                                        class="w-24 text-center font-bold rounded-xl border border-zinc-300 bg-white dark:bg-zinc-800 dark:border-zinc-700 px-3 py-2 text-sm text-zinc-900 dark:text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition"
                                    />
                                </td>
                                <td class="py-2 px-4">
                                    <input 
                                        type="text" 
                                        wire:model="scores.{{ $santri->id }}.catatan" 
                                        placeholder="Catatan perkembangan atau kejadian..." 
                                        class="w-full rounded-xl border border-zinc-200 bg-white dark:bg-zinc-800 dark:border-zinc-700 px-3 py-2 text-xs text-zinc-900 dark:text-white focus:border-emerald-500 outline-none transition"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-xs text-zinc-400">
                                    {{ __('Tidak ada santri aktif ditemukan pada kelas ini.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($this->santriList->isNotEmpty())
                <div class="p-4 bg-zinc-50 dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <span class="text-xs text-zinc-500">
                        Total Santri: <span class="font-bold text-zinc-900 dark:text-white">{{ $this->santriList->count() }} Orang</span>
                    </span>

                    <flux:button variant="primary" type="submit" icon="check">
                        {{ __('Simpan Semua Nilai Harian') }}
                    </flux:button>
                </div>
            @endif
        </div>
    </form>
</div>
