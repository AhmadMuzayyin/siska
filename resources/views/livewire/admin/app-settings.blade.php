<div class="w-full max-w-5xl space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl">{{ __('Pengaturan Aplikasi') }}</flux:heading>
            <flux:subheading>{{ __('Informasi lembaga dan konfigurasi umum sistem.') }}</flux:subheading>
        </div>

        <div class="flex items-center gap-2">
            <flux:button size="sm" variant="filled" :href="route('settings')" wire:navigate class="text-xs">
                {{ __('Pusat Pengaturan Lengkap') }} ↗
            </flux:button>
        </div>
    </div>

    {{-- Callout Info Pengaturan Tema --}}
    <div class="flex items-center justify-between p-4 rounded-2xl bg-emerald-50/80 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800">
        <div class="flex items-center gap-3">
            <div class="size-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                <flux:icon name="paint-brush" class="size-5" />
            </div>
            <div>
                <h4 class="text-xs font-bold text-emerald-950 dark:text-emerald-200">{{ __('Pengaturan Tema & Konten Website') }}</h4>
                <p class="text-[11px] text-zinc-600 dark:text-zinc-400 mt-0.5">{{ __('Kini dapat dikonfigurasi perkategori halaman melalui menu Pengaturan Website & Tampilan.') }}</p>
            </div>
        </div>
        <flux:button size="sm" variant="filled" :href="route('settings', ['tab' => 'pages'])" wire:navigate class="shrink-0 text-xs">
            {{ __('Buka Konten Halaman') }} ↗
        </flux:button>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- Kolom Kiri: Informasi Lembaga & Kontak --}}
            <div class="lg:col-span-7 space-y-6">
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-4">
                    <div>
                        <flux:heading size="md">{{ __('Informasi & Identitas Lembaga') }}</flux:heading>
                        <flux:subheading>{{ __('Data identitas resmi dan kontak operasional lembaga.') }}</flux:subheading>
                    </div>

                    <flux:input wire:model="lembaga" :label="__('Nama Lembaga')" required />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <flux:input wire:model="nsm" :label="__('NSM (opsional)')" />
                        <flux:input wire:model="telepon" :label="__('Telepon')" required />
                    </div>

                    <flux:input wire:model="email" type="email" :label="__('Email Resmi')" required />
                    <flux:textarea wire:model="alamat" :label="__('Alamat Lengkap')" rows="2" required />
                    <flux:input wire:model="google_maps_url" type="url" :label="__('URL Google Maps Embed')" placeholder="https://www.google.com/maps/embed?pb=..." description="{{ __('Salin URL embed dari Google Maps → Share → Embed a map') }}" />
                </div>
            </div>

            {{-- Kolom Kanan: SEO, Penggajian & Kontrol Akses --}}
            <div class="lg:col-span-5 space-y-6">
                {{-- Buka / Kunci Fitur --}}
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-3">
                    <div>
                        <flux:heading size="md">{{ __('Buka / Kunci Fitur Sistem') }}</flux:heading>
                        <flux:subheading>{{ __('Izin penginputan nilai dan pendaftaran baru.') }}</flux:subheading>
                    </div>
                    <flux:switch wire:model="is_input_nilai_open" :label="__('Akses Input Nilai Guru')" description="{{ __('Jika dinonaktifkan, penginputan nilai oleh guru akan dikunci.') }}" />
                    <flux:separator />
                    <flux:switch wire:model="is_ppdb_open" :label="__('Pendaftaran Santri Baru (PPDB)')" description="{{ __('Jika dinonaktifkan, form pendaftaran santri baru publik akan dikunci.') }}" />
                </div>

                {{-- SEO --}}
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-4">
                    <div>
                        <flux:heading size="md">{{ __('Search Engine Optimization (SEO)') }}</flux:heading>
                        <flux:subheading>{{ __('Optimasi pencarian Google.') }}</flux:subheading>
                    </div>
                    <flux:textarea wire:model="meta_deskripsi" :label="__('Meta Deskripsi')" rows="2" />
                    <flux:input wire:model="meta_keyword" :label="__('Meta Keyword')" />
                </div>

                {{-- Penggajian & WhatsApp --}}
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-4">
                    <div>
                        <flux:heading size="md">{{ __('Penggajian & WhatsApp') }}</flux:heading>
                        <flux:subheading>{{ __('Cutoff payroll dan integrasi WA gateway.') }}</flux:subheading>
                    </div>
                    <flux:input wire:model="payroll_cutoff_day" type="number" min="1" max="31" :label="__('Tanggal Cutoff Penggajian')" required />
                    <flux:separator />
                    <flux:switch wire:model="fitur_pesan_whatsapp" :label="__('Aktifkan pesan WhatsApp')" />
                    <flux:textarea wire:model="pesan_whatsapp" :label="__('Template Pesan Default')" rows="2" />
                </div>
            </div>
        </div>

        <div class="flex justify-end p-4 rounded-2xl bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-200 dark:border-zinc-700 shadow-xs">
            <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                {{ __('Simpan Pengaturan') }}
            </flux:button>
        </div>
    </form>
</div>
