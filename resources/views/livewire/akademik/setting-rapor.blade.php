<div class="flex flex-col gap-6">
    <div>
        <flux:heading size="xl">{{ __('Setting Rapor & Deskripsi Nilai') }}</flux:heading>
        <flux:subheading>{{ __('Kelola template dokumen Word (.docx) rapor dan deskripsi capaian nilai tiap mata pelajaran.') }}</flux:subheading>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Card 1: Upload Template Word (.docx) --}}
        <div class="flex flex-col gap-4 rounded-xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <flux:heading size="lg">{{ __('Template Rapor (.docx)') }}</flux:heading>
                <flux:badge size="sm" color="blue">{{ __('.docx Only') }}</flux:badge>
            </div>

            <flux:text class="text-xs text-zinc-500">
                {{ __('Upload template Word dengan tag placeholder seperti {nama}, {nisn}, {kelas}, {lembaga}, {tahun_akademik}, {semester}, {nilai}, {deskripsi} yang akan diganti otomatis saat mencetak rapor.') }}
            </flux:text>

            @if ($currentTemplatePath)
                <div class="flex items-center justify-between rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs text-emerald-800 dark:border-emerald-800/40 dark:bg-emerald-950/30 dark:text-emerald-300">
                    <div class="flex items-center gap-2">
                        <flux:icon name="document-text" class="size-4 shrink-0 text-emerald-600" />
                        <span class="font-semibold">{{ __('Template Aktif Tersedia') }}</span>
                    </div>
                    <a href="{{ Storage::disk('public')->url($currentTemplatePath) }}" target="_blank" class="font-bold underline">{{ __('Download Template') }}</a>
                </div>
            @endif

            <form wire:submit="uploadTemplate" class="flex flex-col gap-4">
                <flux:input wire:model="template_file" type="file" accept=".docx" :label="__('Pilih File Word (.docx)')" />
                @error('template_file') <flux:text class="text-xs text-rose-500 font-semibold">{{ $message }}</flux:text> @enderror

                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                        {{ __('Unggah Template Rapor') }}
                    </flux:button>
                </div>
            </form>
        </div>

        {{-- Card 2: Deskripsi Mata Pelajaran --}}
        <div class="flex flex-col gap-4 rounded-xl border border-zinc-200 bg-white p-6 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
            <flux:heading size="lg">{{ __('Deskripsi Capaian Nilai per Mapel') }}</flux:heading>

            <flux:select wire:model.live="selectedMapelId" :label="__('Pilih Mata Pelajaran')" placeholder="{{ __('Pilih Mata Pelajaran') }}">
                @foreach ($this->mapelOptions as $mapel)
                    <flux:select.option value="{{ $mapel->id }}">{{ $mapel->nama }} @if($mapel->lembaga) ({{ $mapel->lembaga->jenjang }}) @endif</flux:select.option>
                @endforeach
            </flux:select>

            <form wire:submit="saveDeskripsi" class="flex flex-col gap-4">
                <flux:textarea wire:model="deskripsi_a" :label="__('Deskripsi Predikat A (Sangat Baik)')" rows="2" placeholder="Memberikan deskripsi capaian nilai A..." />
                <flux:textarea wire:model="deskripsi_b" :label="__('Deskripsi Predikat B (Baik)')" rows="2" placeholder="Memberikan deskripsi capaian nilai B..." />
                <flux:textarea wire:model="deskripsi_c" :label="__('Deskripsi Predikat C (Cukup)')" rows="2" placeholder="Memberikan deskripsi capaian nilai C..." />
                <flux:textarea wire:model="deskripsi_d" :label="__('Deskripsi Predikat D (Kurang)')" rows="2" placeholder="Memberikan deskripsi capaian nilai D..." />

                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                        {{ __('Simpan Deskripsi') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</div>
