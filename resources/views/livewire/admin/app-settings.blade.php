<div class="flex max-w-2xl flex-col gap-6">
    <div>
        <flux:heading size="xl">{{ __('Pengaturan Aplikasi') }}</flux:heading>
        <flux:subheading>{{ __('Informasi lembaga dan konfigurasi umum sistem.') }}</flux:subheading>
    </div>

    <form wire:submit="save" class="flex flex-col gap-6">
        {{-- Callout Info Pengaturan Tema --}}
        <div class="flex items-center justify-between p-4 rounded-2xl bg-emerald-50/80 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800">
            <div class="flex items-center gap-3">
                <div class="size-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                    <flux:icon name="paint-brush" class="size-5" />
                </div>
                <div>
                    <h4 class="text-xs font-bold text-emerald-950 dark:text-emerald-200">{{ __('Pengaturan Tema Aplikasi & Website') }}</h4>
                    <p class="text-[11px] text-zinc-600 dark:text-zinc-400 mt-0.5">{{ __('Kini dapat dikonfigurasi melalui menu Pengaturan Tampilan (Appearance).') }}</p>
                </div>
            </div>
            <flux:button size="sm" variant="filled" :href="route('appearance.edit')" wire:navigate class="shrink-0 text-xs">
                {{ __('Buka Tampilan') }} ↗
            </flux:button>
        </div>

        <flux:heading size="sm">{{ __('Informasi Lembaga') }}</flux:heading>
        <flux:input wire:model="lembaga" :label="__('Nama Lembaga')" />
        <div class="grid grid-cols-2 gap-4">
            <flux:input wire:model="nsm" :label="__('NSM (opsional)')" />
            <flux:input wire:model="telepon" :label="__('Telepon')" />
        </div>
        <flux:input wire:model="email" type="email" :label="__('Email')" />
        <flux:textarea wire:model="alamat" :label="__('Alamat')" rows="2" />
        <flux:input wire:model="google_maps_url" type="url" :label="__('URL Google Maps Embed')" placeholder="https://www.google.com/maps/embed?pb=..." description="{{ __('Salin URL embed dari Google Maps → Share → Embed a map') }}" />

        <flux:separator />

        <flux:heading size="sm">{{ __('SEO') }}</flux:heading>
        <flux:textarea wire:model="meta_deskripsi" :label="__('Meta Deskripsi')" rows="2" />
        <flux:input wire:model="meta_keyword" :label="__('Meta Keyword')" />

        <flux:separator />

        <flux:heading size="sm">{{ __('Penggajian') }}</flux:heading>
        <flux:input wire:model="payroll_cutoff_day" type="number" min="1" max="31" :label="__('Tanggal Cutoff Penggajian')" />

        <flux:separator />

        <flux:heading size="sm">{{ __('Notifikasi WhatsApp') }}</flux:heading>
        <flux:switch wire:model="fitur_pesan_whatsapp" :label="__('Aktifkan pesan WhatsApp otomatis')" />
        <flux:textarea wire:model="pesan_whatsapp" :label="__('Template Pesan')" rows="3" />

        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">{{ __('Simpan Pengaturan') }}</flux:button>
        </div>
    </form>
</div>
