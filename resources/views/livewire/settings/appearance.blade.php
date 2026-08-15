<div class="w-full space-y-10">
    {{-- Section 1: Mode Tema Aplikasi (Light / Dark / System) --}}
    <div class="space-y-4">
        <div>
            <flux:heading size="md">{{ __('Mode Tampilan Dashboard') }}</flux:heading>
            <flux:subheading>{{ __('Pilih preferensi mode warna antarmuka aplikasi untuk perangkat Anda.') }}</flux:subheading>
        </div>

        <div class="max-w-md">
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
            </flux:radio.group>
        </div>
    </div>

    @if ($isAdmin)
        <flux:separator />

        {{-- Section 2: Pengaturan Tema Landing Page & Website Publik --}}
        <div class="space-y-4">
            <div>
                <div class="flex items-center gap-2">
                    <flux:heading size="md">{{ __('Tema Website Publik & Landing Page') }}</flux:heading>
                    <span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/60 px-2 py-0.5 text-[10px] font-bold text-emerald-800 dark:text-emerald-300">
                        {{ __('Admin Only') }}
                    </span>
                </div>
                <flux:subheading>{{ __('Pilih desain tema aktif untuk halaman depan, program, galeri, kontak, dan login publik.') }}</flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-3xl">
                {{-- Opsi 1: Tema Default (Klasik Emerald) --}}
                <label class="relative flex flex-col p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 {{ $landing_theme === 'default' ? 'border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/20 dark:border-emerald-500 shadow-md ring-2 ring-emerald-500/20' : 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="size-3 rounded-full bg-emerald-500"></span>
                            <span class="font-bold text-sm text-zinc-900 dark:text-zinc-100">{{ __('Tema Default') }}</span>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">{{ __('Klasik Emerald') }}</span>
                        </div>
                        <input type="radio" wire:model.live="landing_theme" value="default" class="text-emerald-600 focus:ring-emerald-500 size-4">
                    </div>
                    
                    {{-- Mini Preview Box --}}
                    <div class="h-28 w-full rounded-xl bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-950 p-3 flex flex-col justify-between overflow-hidden shadow-inner border border-emerald-700/50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="size-2 rounded-full bg-emerald-400"></span>
                                <span class="h-1.5 w-14 bg-white/50 rounded"></span>
                            </div>
                            <span class="h-2 w-8 bg-emerald-400/80 rounded-full"></span>
                        </div>
                        <div class="space-y-1">
                            <div class="h-2.5 w-32 bg-white/90 rounded font-semibold text-[8px] text-white">Hero Slider & Statistik</div>
                            <div class="h-1.5 w-40 bg-white/40 rounded"></div>
                        </div>
                        <div class="flex gap-1.5">
                            <div class="h-4 w-12 bg-emerald-500/70 rounded-md"></div>
                            <div class="h-4 w-12 bg-white/20 rounded-md"></div>
                        </div>
                    </div>

                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-3 leading-relaxed">
                        {{ __('Desain klasik pesantren nuansa hijau emerald dengan hero slider interaktif, kartu program tebal, dan kartu auth split emerald.') }}
                    </p>
                </label>

                {{-- Opsi 2: Tema Pixigon (Soft Green Light) --}}
                <label class="relative flex flex-col p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 {{ $landing_theme === 'pixigon' ? 'border-[#6bb82d] bg-[#f0f8ec]/60 dark:bg-lime-950/20 dark:border-[#6bb82d] shadow-md ring-2 ring-lime-500/20' : 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="size-3 rounded-full bg-[#6bb82d]"></span>
                            <span class="font-bold text-sm text-zinc-900 dark:text-zinc-100">{{ __('Tema Pixigon') }}</span>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-lime-100 text-lime-800 dark:bg-lime-900/60 dark:text-lime-300">{{ __('Soft Green Light') }}</span>
                        </div>
                        <input type="radio" wire:model.live="landing_theme" value="pixigon" class="text-[#6bb82d] focus:ring-[#6bb82d] size-4">
                    </div>

                    {{-- Mini Preview Box --}}
                    <div class="h-28 w-full rounded-xl bg-[#f0f8ec] p-3 flex flex-col justify-between overflow-hidden shadow-inner border border-[#d6eda6]">
                        <div class="flex items-center justify-between">
                            <span class="h-2 w-16 bg-[#2e5b18] rounded"></span>
                            <span class="h-2.5 w-10 bg-[#536dfe] rounded-full"></span>
                        </div>
                        <div class="text-center py-1">
                            <span class="text-[9px] font-extrabold text-[#2e5b18]">Ellipse Curve Hero & Botanical Doodles</span>
                        </div>
                        <div class="flex justify-around items-center">
                            <span class="size-4 rounded-full bg-white border border-[#d6eda6] shadow-2xs"></span>
                            <span class="size-4 rounded-full bg-white border border-[#d6eda6] shadow-2xs"></span>
                            <span class="size-4 rounded-full bg-white border border-[#d6eda6] shadow-2xs"></span>
                            <span class="size-4 rounded-full bg-white border border-[#d6eda6] shadow-2xs"></span>
                        </div>
                    </div>

                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-3 leading-relaxed">
                        {{ __('Desain modern academy nuansa soft green light dengan doodle botani, navbar seamless, kartu review miring, dan dark forest footer.') }}
                    </p>
                </label>
            </div>
        </div>
    @endif
</div>
