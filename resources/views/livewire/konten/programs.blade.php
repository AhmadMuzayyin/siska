<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Program Pendidikan') }}</flux:heading>
            <flux:subheading>{{ __('Kelola daftar program kurikulum, materi unggulan, dan foto banner program.') }}</flux:subheading>
        </div>

        <flux:button variant="primary" icon="plus" wire:click="create" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
            {{ __('Tambah Program') }}
        </flux:button>
    </div>

    {{-- Filter & Search --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-wrap items-center gap-3">
            <flux:select wire:model.live="lembagaFilter" class="max-w-48" placeholder="{{ __('Semua Lembaga') }}">
                <flux:select.option value="">{{ __('Semua Lembaga') }}</flux:select.option>
                @foreach ($this->lembagas as $lembaga)
                    <flux:select.option value="{{ $lembaga->id }}">{{ $lembaga->nama }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div class="w-full sm:w-auto">
            <flux:input wire:model.live.debounce.250ms="search" placeholder="{{ __('Cari program...') }}" icon="magnifying-glass" class="w-full sm:w-64" />
        </div>
    </div>

    {{-- Program Table / Cards --}}
    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
        <flux:table>
            <flux:table.columns>
                <flux:table.column class="w-12 text-center">{{ __('No') }}</flux:table.column>
                <flux:table.column class="w-20">{{ __('Banner') }}</flux:table.column>
                <flux:table.column>{{ __('Nama Program') }}</flux:table.column>
                <flux:table.column>{{ __('Lembaga') }}</flux:table.column>
                <flux:table.column>{{ __('Materi Unggulan') }}</flux:table.column>
                <flux:table.column class="text-center">{{ __('Urutan') }}</flux:table.column>
                <flux:table.column class="text-center">{{ __('Status') }}</flux:table.column>
                <flux:table.column class="text-end">{{ __('Aksi') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->rows as $index => $program)
                    <flux:table.row :key="$program->id">
                        <flux:table.cell class="text-center text-xs text-zinc-500">
                            {{ $this->rows->firstItem() + $index }}
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="size-14 rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800">
                                @if ($program->gambar_url)
                                    <img src="{{ $program->gambar_url }}" alt="{{ $program->nama_program }}" class="size-full object-cover" />
                                @else
                                    <div class="size-full flex items-center justify-center text-zinc-400">
                                        <flux:icon name="photo" class="size-6" />
                                    </div>
                                @endif
                            </div>
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-zinc-900 dark:text-white">{{ $program->nama_program }}</span>
                                    @if ($program->kategori_badge)
                                        <flux:badge size="sm" color="emerald">{{ $program->kategori_badge }}</flux:badge>
                                    @endif
                                </div>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400 line-clamp-2 max-w-md">
                                    {{ $program->deskripsi_singkat }}
                                </p>
                            </div>
                        </flux:table.cell>

                        <flux:table.cell>
                            @if ($program->lembaga)
                                <flux:badge size="sm" color="sky">{{ $program->lembaga->nama }}</flux:badge>
                            @else
                                <flux:badge size="sm" color="zinc">{{ __('Umum / Global') }}</flux:badge>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            @if (! empty($program->materi_unggulan) && is_array($program->materi_unggulan))
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @foreach (array_slice($program->materi_unggulan, 0, 3) as $materi)
                                        <span class="inline-flex items-center rounded-md bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 text-[11px] text-zinc-700 dark:text-zinc-300">
                                            • {{ $materi['judul'] ?? '' }}
                                        </span>
                                    @endforeach
                                    @if (count($program->materi_unggulan) > 3)
                                        <span class="text-[10px] text-zinc-400 self-center">+{{ count($program->materi_unggulan) - 3 }} lainnya</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-zinc-400">-</span>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell class="text-center font-mono text-xs">
                            {{ $program->urutan }}
                        </flux:table.cell>

                        <flux:table.cell class="text-center">
                            <flux:badge
                                size="sm"
                                :color="$program->is_active ? 'emerald' : 'zinc'"
                                class="cursor-pointer"
                                wire:click="toggleActive({{ $program->id }})"
                            >
                                {{ $program->is_active ? __('Aktif') : __('Nonaktif') }}
                            </flux:badge>
                        </flux:table.cell>

                        <flux:table.cell class="text-end">
                            <div class="flex justify-end gap-1">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="edit({{ $program->id }})" />
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-700 cursor-pointer"
                                    wire:click="$set('deletingId', {{ $program->id }})"
                                    x-on:click="$flux.modal('confirm-delete-program-modal').show()"
                                />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="8" class="py-12 text-center text-zinc-400">
                            <flux:icon name="book-open" class="mx-auto size-8 mb-2 opacity-50" />
                            {{ __('Belum ada program pendidikan yang ditambahkan.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    {{ $this->rows->links() }}

    {{-- Form Slider (Flyout) --}}
    <flux:modal name="program-form" flyout class="space-y-6 md:w-[38rem]" @close="$set('editingId', null)">
        <form wire:submit="save" class="flex flex-col gap-5">
            <div>
                <flux:heading size="lg">{{ $editingId ? __('Edit Program Pendidikan') : __('Tambah Program Pendidikan') }}</flux:heading>
                <flux:subheading>{{ __('Isi detail nama program, badge, deskripsi, materi unggulan, dan link gambar.') }}</flux:subheading>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="nama_program" :label="__('Nama Program')" placeholder="Contoh: TPQ Tilawati" required />
                <flux:input wire:model="kategori_badge" :label="__('Kategori / Badge')" placeholder="Contoh: METODE TILAWATI" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:select wire:model="lembaga_id" :label="__('Lembaga Terkait (Opsional)')" placeholder="{{ __('Umum / Semua Lembaga') }}">
                    <flux:select.option value="">{{ __('Umum / Semua Lembaga') }}</flux:select.option>
                    @foreach ($this->lembagas as $lembaga)
                        <flux:select.option value="{{ $lembaga->id }}">{{ $lembaga->nama }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input wire:model="icon" :label="__('Icon (Heroicon)')" placeholder="book-open / academic-cap / sparkles" />
            </div>

            <flux:textarea wire:model="deskripsi_singkat" :label="__('Deskripsi Singkat')" rows="3" placeholder="{{ __('Jelaskan pengantar program ini...') }}" required />

            {{-- Dynamic Materi Unggulan Repeater --}}
            <div class="flex flex-col gap-3 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/30">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:heading size="sm">{{ __('List Materi & Keunggulan Program') }}</flux:heading>
                        <flux:subheading size="xs">{{ __('Poin materi yang diajarkan beserta penjelasannya.') }}</flux:subheading>
                    </div>
                    <flux:button type="button" size="xs" variant="primary" icon="plus" wire:click="addMateri" class="bg-emerald-600! text-white!">
                        {{ __('Tambah Materi') }}
                    </flux:button>
                </div>

                <div class="space-y-3 pt-2">
                    @forelse ($materi_unggulan as $index => $materi)
                        <div class="flex items-start gap-2 p-3 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900" wire:key="materi-{{ $index }}">
                            <div class="flex-1 space-y-2">
                                <flux:input wire:model="materi_unggulan.{{ $index }}.judul" placeholder="Judul materi (misal: Tilawati Jilid 1 s/d 6)" size="sm" required />
                                <flux:input wire:model="materi_unggulan.{{ $index }}.deskripsi" placeholder="Deskripsi/keterangan materi (opsional)" size="sm" />
                            </div>
                            <flux:button type="button" size="sm" variant="ghost" icon="trash" class="text-red-500 hover:text-red-700 mt-1" wire:click="removeMateri({{ $index }})" />
                        </div>
                    @empty
                        <div class="text-center py-3 text-xs text-zinc-400">
                            {{ __('Belum ada materi unggulan. Klik "Tambah Materi" di atas.') }}
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <flux:input wire:model="gambar_url" :label="__('URL Link Gambar Banner')" placeholder="https://images.unsplash.com/..." />
                </div>
                <div>
                    <flux:input type="number" wire:model="urutan" :label="__('Urutan Tampil')" min="0" />
                </div>
            </div>

            @if ($gambar_url)
                <div class="flex items-center gap-3 p-2 rounded-lg border border-zinc-200 dark:border-zinc-700">
                    <img src="{{ $gambar_url }}" alt="Preview" class="h-12 w-20 object-cover rounded" onerror="this.style.display='none'" />
                    <span class="text-xs text-zinc-500">{{ __('Pratinjau link gambar banner') }}</span>
                </div>
            @endif

            <div class="flex justify-end gap-2 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                    {{ __('Simpan Program') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Confirm Delete Modal --}}
    <x-confirm-modal 
        name="confirm-delete-program-modal" 
        title="{{ __('Hapus Program Pendidikan') }}" 
        description="{{ __('Apakah Anda yakin ingin menghapus program pendidikan ini?') }}" 
        action="delete" 
        confirmText="{{ __('Hapus Program') }}" 
    />
</div>
