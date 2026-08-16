<div class="flex flex-col gap-6">
    
    {{-- Header Utama Halaman --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Kalender Akademik / Pendidikan') }}</flux:heading>
            <flux:subheading>
                @if ($this->activeSemester)
                    {{ __('Semester Aktif:') }} 
                    <strong class="font-bold text-emerald-600 dark:text-emerald-400">
                        {{ $this->activeSemester->tahunAkademik?->nama }} ({{ ucfirst($this->activeSemester->tipe?->value ?? '') }})
                    </strong>
                    &bull; {{ $this->activeSemester->mulai?->translatedFormat('d M Y') }} - {{ $this->activeSemester->selesai?->translatedFormat('d M Y') }}
                @else
                    <span class="text-rose-600 dark:text-rose-400 font-bold">{{ __('Belum ada semester aktif yang dipilih!') }}</span>
                @endif
            </flux:subheading>
        </div>

        {{-- Tombol Aksi Utama Kelola / Tambah Agenda --}}
        @if ($this->activeSemester)
            @can('create', App\Models\KalenderAkademik::class)
                <flux:button 
                    variant="primary" 
                    icon="plus" 
                    wire:click="openDrawer()" 
                    class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold shadow-sm"
                >
                    {{ __('Kelola / Tambah Agenda') }}
                </flux:button>
            @endcan
        @endif
    </div>

    {{-- WARNING STATE: JIKA BELUM ADA SEMESTER AKTIF --}}
    @if (! $this->activeSemester)
        <div class="p-6 rounded-3xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 space-y-4 text-center">
            <div class="size-12 rounded-full bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto">
                <flux:icon name="exclamation-triangle" class="size-6" />
            </div>
            <div class="space-y-1 max-w-md mx-auto">
                <h3 class="text-base font-bold text-amber-900 dark:text-amber-200">
                    {{ __('Semester Aktif Belum Diatur') }}
                </h3>
                <p class="text-xs text-amber-700 dark:text-amber-300 leading-relaxed">
                    {{ __('Untuk mengelola Kalender Akademik / Pendidikan, silakan aktifkan salah satu semester pada menu Tahun Akademik terlebih dahulu.') }}
                </p>
            </div>
            <div class="pt-2">
                <a 
                    href="{{ route('akademik.tahun-akademik') }}" 
                    wire:navigate 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md transition-all"
                >
                    <flux:icon name="calendar-days" class="size-4" />
                    <span>{{ __('Atur Semester Aktif Sekarang') }}</span>
                </a>
            </div>
        </div>
    @else

        {{-- BAR LEGENDA KATEGORI AGENDA --}}
        <div class="flex flex-wrap items-center justify-between gap-3 p-3.5 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-xs">
            <div class="flex flex-wrap items-center gap-4 text-xs">
                <span class="font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider text-[11px]">{{ __('Kategori Agenda:') }}</span>
                <span class="inline-flex items-center gap-1.5 text-zinc-700 dark:text-zinc-300 font-medium">
                    <span class="size-2.5 rounded-full bg-emerald-500"></span> {{ __('KBM & Perkuliahan') }}
                </span>
                <span class="inline-flex items-center gap-1.5 text-zinc-700 dark:text-zinc-300 font-medium">
                    <span class="size-2.5 rounded-full bg-amber-500"></span> {{ __('Penjadwalan / Munaqasyah') }}
                </span>
                <span class="inline-flex items-center gap-1.5 text-zinc-700 dark:text-zinc-300 font-medium">
                    <span class="size-2.5 rounded-full bg-rose-500"></span> {{ __('Libur Nasional / Madrasah') }}
                </span>
                <span class="inline-flex items-center gap-1.5 text-zinc-700 dark:text-zinc-300 font-medium">
                    <span class="size-2.5 rounded-full bg-indigo-500"></span> {{ __('Ujian / PTS / PAS') }}
                </span>
                <span class="inline-flex items-center gap-1.5 text-zinc-700 dark:text-zinc-300 font-medium">
                    <span class="size-2.5 rounded-full bg-purple-500"></span> {{ __('Input Nilai & Rapor') }}
                </span>
            </div>

            <div class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">
                {{ __('Total:') }} <strong class="text-zinc-900 dark:text-white">{{ $this->eventsList->count() }} {{ __('Agenda') }}</strong>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- TAMPILAN EKSKLUSIF HALAMAN UTAMA: TIMELINE VERTIKAL (Sesuai Gambar Referensi 2) --}}
        {{-- ========================================================================= --}}
        <div class="p-6 sm:p-12 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 shadow-sm relative overflow-hidden">
            
            <div class="relative max-w-4xl mx-auto">
                
                {{-- NODE ATAS: MULAI SEMESTER --}}
                <div class="flex flex-col items-center justify-center mb-10 relative z-10">
                    <div class="px-6 py-2.5 rounded-full bg-indigo-600 text-white font-black text-xs tracking-wider shadow-lg shadow-indigo-500/30 uppercase flex items-center gap-2">
                        <span>{{ __('MULAI') }}</span>
                    </div>
                </div>

                {{-- Garis Vertikal Tengah --}}
                <div class="absolute left-1/2 top-6 bottom-6 -translate-x-1/2 w-0.5 bg-indigo-200 dark:bg-indigo-900/60 z-0"></div>

                {{-- Daftar Agenda Timeline Vertikal --}}
                <div class="space-y-12 relative z-10">
                    @forelse ($this->eventsList as $index => $event)
                        @php
                            $isEven = $index % 2 === 0;
                            $badgeColor = $event->warna ?? '#10b981';
                        @endphp

                        <div class="relative flex items-center justify-between w-full group">
                            
                            {{-- SISI KIRI --}}
                            @if ($isEven)
                                <div class="w-[calc(50%-2rem)] pr-4 text-right">
                                    <div class="inline-block text-left p-5 rounded-2xl bg-white dark:bg-zinc-800/90 border border-zinc-200/80 dark:border-zinc-700 shadow-md relative transition-all hover:shadow-xl hover:-translate-y-0.5 group">
                                        
                                        {{-- Ribbon Tanggal (Posisi Kiri) --}}
                                        <div class="absolute -top-3 -left-3 px-3 py-1 rounded-lg text-white font-bold text-xs shadow-md flex items-center gap-1" style="background-color: {{ $badgeColor }};">
                                            <span class="text-sm font-black">{{ $event->mulai?->format('d') }}</span>
                                            <span class="text-[10px] uppercase">{{ $event->mulai?->translatedFormat('M') }}</span>
                                        </div>

                                        <div class="pt-2 space-y-1.5">
                                            <div class="flex items-center justify-between gap-2">
                                                <h4 class="font-bold text-sm text-zinc-900 dark:text-white">
                                                    {{ $event->judul }}
                                                </h4>
                                                <span class="text-[10px] font-semibold text-zinc-400">
                                                    {{ $event->mulai?->format('d/m/Y') }}
                                                    @if ($event->selesai) - {{ $event->selesai->format('d/m/Y') }} @endif
                                                </span>
                                            </div>

                                            @if ($event->deskripsi)
                                                <p class="text-xs text-zinc-600 dark:text-zinc-300 leading-relaxed">
                                                    {{ $event->deskripsi }}
                                                </p>
                                            @endif

                                            @can('update', $event)
                                                <div class="pt-2 flex justify-end gap-2 border-t border-zinc-100 dark:border-zinc-700/60">
                                                    <button type="button" wire:click="edit({{ $event->id }})" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 cursor-pointer">
                                                        {{ __('Edit') }}
                                                    </button>
                                                    <button type="button" wire:click="delete({{ $event->id }})" wire:confirm="{{ __('Yakin ingin menghapus agenda ini?') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 cursor-pointer">
                                                        {{ __('Hapus') }}
                                                    </button>
                                                </div>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="w-[calc(50%-2rem)]"></div>
                            @endif

                            {{-- NODE IKON TENGAH --}}
                            <div class="absolute left-1/2 -translate-x-1/2 size-10 rounded-full bg-white dark:bg-zinc-800 border-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shadow-md z-20 shrink-0">
                                <flux:icon name="{{ $event->ikon ?? 'calendar' }}" class="size-4" />
                            </div>

                            {{-- SISI KANAN --}}
                            @if (! $isEven)
                                <div class="w-[calc(50%-2rem)] pl-4 text-left">
                                    <div class="inline-block w-full text-left p-5 rounded-2xl bg-white dark:bg-zinc-800/90 border border-zinc-200/80 dark:border-zinc-700 shadow-md relative transition-all hover:shadow-xl hover:-translate-y-0.5 group">
                                        
                                        {{-- Ribbon Tanggal (Posisi Kanan) --}}
                                        <div class="absolute -top-3 -right-3 px-3 py-1 rounded-lg text-white font-bold text-xs shadow-md flex items-center gap-1" style="background-color: {{ $badgeColor }};">
                                            <span class="text-sm font-black">{{ $event->mulai?->format('d') }}</span>
                                            <span class="text-[10px] uppercase">{{ $event->mulai?->translatedFormat('M') }}</span>
                                        </div>

                                        <div class="pt-2 space-y-1.5">
                                            <div class="flex items-center justify-between gap-2">
                                                <h4 class="font-bold text-sm text-zinc-900 dark:text-white">
                                                    {{ $event->judul }}
                                                </h4>
                                                <span class="text-[10px] font-semibold text-zinc-400">
                                                    {{ $event->mulai?->format('d/m/Y') }}
                                                    @if ($event->selesai) - {{ $event->selesai->format('d/m/Y') }} @endif
                                                </span>
                                            </div>

                                            @if ($event->deskripsi)
                                                <p class="text-xs text-zinc-600 dark:text-zinc-300 leading-relaxed">
                                                    {{ $event->deskripsi }}
                                                </p>
                                            @endif

                                            @can('update', $event)
                                                <div class="pt-2 flex justify-end gap-2 border-t border-zinc-100 dark:border-zinc-700/60">
                                                    <button type="button" wire:click="edit({{ $event->id }})" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 cursor-pointer">
                                                        {{ __('Edit') }}
                                                    </button>
                                                    <button type="button" wire:click="delete({{ $event->id }})" wire:confirm="{{ __('Yakin ingin menghapus agenda ini?') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 cursor-pointer">
                                                        {{ __('Hapus') }}
                                                    </button>
                                                </div>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="w-[calc(50%-2rem)]"></div>
                            @endif

                        </div>
                    @empty
                        <div class="py-16 text-center text-zinc-400 space-y-3">
                            <div class="size-12 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-400 flex items-center justify-center mx-auto">
                                <flux:icon name="calendar-days" class="size-6" />
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-sm font-bold text-zinc-700 dark:text-zinc-300">
                                    {{ __('Belum Ada Agenda Terdaftar') }}
                                </h4>
                                <p class="text-xs text-zinc-500 max-w-sm mx-auto">
                                    {{ __('Klik tombol "+ Kelola / Tambah Agenda" di atas untuk menambahkan agenda kegiatan akademik.') }}
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- NODE BAWAH: SELESAI SEMESTER --}}
                <div class="flex flex-col items-center justify-center mt-12 relative z-10">
                    <div class="px-6 py-2.5 rounded-full bg-indigo-600 text-white font-black text-xs tracking-wider shadow-lg shadow-indigo-500/30 uppercase flex items-center gap-2">
                        <span>{{ __('SELESAI') }}</span>
                    </div>
                </div>

            </div>

        </div>

    @endif

    {{-- ========================================================================= --}}
    {{-- MODAL / PANEL DRAWER KELOLA KALENDER INTERAKTIF (FULLCALENDAR YEAR VIEW + FORM) --}}
    {{-- ========================================================================= --}}
    <flux:modal name="calendar-drawer" flyout class="w-full md:w-[48rem] space-y-6">
        
        {{-- Header Drawer Modal --}}
        <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-4">
            <div>
                <flux:heading size="lg">{{ $editingId ? __('Edit Agenda Kalender') : __('Kelola & Tambah Agenda Kalender') }}</flux:heading>
                <flux:subheading>{{ __('Pilih tanggal pada kalender di bawah untuk menentukan tanggal mulai & selesai, lalu lengkapi detail agenda.') }}</flux:subheading>
            </div>
        </div>

        {{-- SECTION A: KALENDER TAHUNAN 12 BULAN INTERAKTIF (Sesuai Gambar 1) --}}
        <div class="p-4 rounded-2xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 space-y-4">
            
            {{-- Toolbar Navigasi Tahun --}}
            <div class="flex items-center justify-between pb-2 border-b border-zinc-200/80 dark:border-zinc-800">
                <div class="flex items-center gap-2">
                    <button type="button" wire:click="today()" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-800 dark:text-zinc-200 hover:bg-zinc-100 cursor-pointer">
                        {{ __('Hari Ini') }}
                    </button>
                    <div class="flex items-center gap-1">
                        <button type="button" wire:click="previousYear()" class="p-1.5 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-800 text-zinc-600 dark:text-zinc-300 cursor-pointer">
                            <flux:icon name="chevron-left" class="size-4" />
                        </button>
                        <button type="button" wire:click="nextYear()" class="p-1.5 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-800 text-zinc-600 dark:text-zinc-300 cursor-pointer">
                            <flux:icon name="chevron-right" class="size-4" />
                        </button>
                    </div>
                    <h3 class="text-xl font-black text-zinc-900 dark:text-white ms-1">
                        {{ $currentYear }}
                    </h3>
                </div>

                <div class="text-[11px] text-zinc-500 font-medium">
                    {{ __('Klik tanggal untuk memilih rentang waktu') }}
                </div>
            </div>

            {{-- Grid 12 Bulan (3 Kolom x 4 Baris - Tampilan Kalender Gambar 1 Dalam Bahasa Indonesia) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-72 overflow-y-auto pr-1">
                @for ($month = 1; $month <= 12; $month++)
                    @php
                        $firstDayOfMonth = \Carbon\Carbon::create($currentYear, $month, 1);
                        $daysInMonth = $firstDayOfMonth->daysInMonth;
                        $startDayOfWeek = $firstDayOfMonth->dayOfWeek; // 0 (Minggu) to 6 (Sabtu)
                        $monthNameIndo = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ][$month];
                    @endphp

                    <div class="p-3 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200/80 dark:border-zinc-800 space-y-2">
                        <h4 class="text-xs font-bold text-center text-zinc-800 dark:text-zinc-200">
                            {{ $monthNameIndo }}
                        </h4>

                        {{-- Singkatan Hari Bahasa Indonesia (M S S R K J S) --}}
                        <div class="grid grid-cols-7 text-center text-[9px] font-bold text-zinc-400 dark:text-zinc-500 uppercase pb-1 border-b border-zinc-100 dark:border-zinc-800">
                            <div>M</div>
                            <div>S</div>
                            <div>S</div>
                            <div>R</div>
                            <div>K</div>
                            <div>J</div>
                            <div>S</div>
                        </div>

                        {{-- Hari Bulan --}}
                        <div class="grid grid-cols-7 text-center text-[11px] gap-y-0.5">
                            @for ($i = 0; $i < $startDayOfWeek; $i++)
                                <div></div>
                            @endfor

                            @for ($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $dateStr = sprintf('%04d-%02d-%02d', $currentYear, $month, $day);
                                    $isSelectedStart = $mulai === $dateStr;
                                    $isSelectedEnd = $selesai === $dateStr;
                                    $isInRange = $mulai && $selesai && ($dateStr >= $mulai && $dateStr <= $selesai);
                                    $hasEvents = $this->eventsList->contains(fn($ev) => $dateStr >= $ev->mulai->format('Y-m-d') && $dateStr <= ($ev->selesai?->format('Y-m-d') ?? $ev->mulai->format('Y-m-d')));
                                @endphp

                                <button
                                    type="button"
                                    wire:click="selectDate('{{ $dateStr }}')"
                                    class="py-0.5 flex flex-col items-center justify-center rounded-md transition-all cursor-pointer relative {{ $isSelectedStart || $isSelectedEnd ? 'bg-emerald-600 text-white font-bold' : ($isInRange ? 'bg-emerald-100 text-emerald-900 font-semibold' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800') }}"
                                >
                                    <span>{{ $day }}</span>
                                    @if ($hasEvents)
                                        <span class="size-1 rounded-full bg-amber-500 absolute bottom-0.5"></span>
                                    @endif
                                </button>
                            @endfor
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        {{-- SECTION B: FORM DETIL AGENDA --}}
        <form wire:submit="save" class="flex flex-col gap-4 pt-2">
            
            {{-- Info Tanggal Terpilih --}}
            <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/80 text-xs text-emerald-800 dark:text-emerald-300 font-medium">
                <div class="flex items-center gap-1.5">
                    <flux:icon name="calendar" class="size-4 text-emerald-600 dark:text-emerald-400" />
                    <span>{{ __('Rentang Tanggal Terpilih:') }}</span>
                </div>
                <strong class="font-bold text-emerald-900 dark:text-emerald-200">
                    {{ $mulai ? \Carbon\Carbon::parse($mulai)->translatedFormat('d M Y') : '-' }}
                    @if ($selesai) &bull; s/d &bull; {{ \Carbon\Carbon::parse($selesai)->translatedFormat('d M Y') }} @endif
                </strong>
            </div>

            <flux:input wire:model="judul" :label="__('Nama Agenda / Kegiatan')" placeholder="{{ __('Contoh: Munaqasyah & Penilaian Akhir Santri') }}" required />

            <flux:select wire:model="tipe" :label="__('Kategori Agenda')">
                <flux:select.option value="kbm">{{ __('KBM & Perkuliahan') }}</flux:select.option>
                <flux:select.option value="pendaftaran">{{ __('Penjadwalan / KRS / Perwalian') }}</flux:select.option>
                <flux:select.option value="ujian">{{ __('Ujian / PTS / PAS / Munaqasyah') }}</flux:select.option>
                <flux:select.option value="rapor">{{ __('Input Nilai & Pembagian Rapor') }}</flux:select.option>
                <flux:select.option value="libur">{{ __('Libur Nasional & Madrasah') }}</flux:select.option>
                <flux:select.option value="kegiatan">{{ __('Kegiatan & Acara Pesantren') }}</flux:select.option>
            </flux:select>

            <div class="grid grid-cols-2 gap-3">
                <flux:input wire:model="mulai" type="date" :label="__('Tanggal Mulai')" required />
                <flux:input wire:model="selesai" type="date" :label="__('Tanggal Selesai (Opsional)')" />
            </div>

            {{-- Pilihan Warna Label Badge --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Warna Label Badge') }}</label>
                <div class="flex items-center gap-2">
                    @foreach (['#10b981' => 'Hijau Emerald', '#f59e0b' => 'Kuning Amber', '#ef4444' => 'Merah', '#6366f1' => 'Nila Indigo', '#8b5cf6' => 'Ungu', '#3b82f6' => 'Biru'] as $code => $label)
                        <button
                            type="button"
                            wire:click="$set('warna', '{{ $code }}')"
                            class="size-7 rounded-full transition-all border-2 cursor-pointer flex items-center justify-center {{ $warna === $code ? 'border-zinc-900 dark:border-white scale-110 shadow-sm' : 'border-transparent hover:scale-105' }}"
                            style="background-color: {{ $code }};"
                            title="{{ $label }}"
                        >
                            @if ($warna === $code)
                                <flux:icon name="check" class="size-4 text-white" />
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>

            <flux:select wire:model="ikon" :label="__('Ikon Timeline Label')">
                <flux:select.option icon="calendar" value="calendar">{{ __('Kalender') }}</flux:select.option>
                <flux:select.option icon="pencil-square" value="pencil-square">{{ __('Pensil / Edit') }}</flux:select.option>
                <flux:select.option icon="clock" value="clock">{{ __('Jam / Waktu') }}</flux:select.option>
                <flux:select.option icon="academic-cap" value="academic-cap">{{ __('Topi Toga / Akademik') }}</flux:select.option>
                <flux:select.option icon="bell" value="bell">{{ __('Lonceng / Pengingat') }}</flux:select.option>
                <flux:select.option icon="sparkles" value="sparkles">{{ __('Kilauan / Acara') }}</flux:select.option>
                <flux:select.option icon="check-circle" value="check-circle">{{ __('Centang / Selesai') }}</flux:select.option>
            </flux:select>

            <flux:textarea wire:model="deskripsi" :label="__('Deskripsi / Catatan Tambahan')" placeholder="{{ __('Penjelasan detail mengenai ketentuan & pelaksanaan agenda...') }}" rows="3" />

            <div class="flex justify-end gap-2 pt-3 border-t border-zinc-100 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                    {{ __('Simpan Agenda') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

</div>
