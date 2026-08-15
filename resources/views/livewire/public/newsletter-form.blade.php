@php
    $setting = \App\Models\Setting::query()->first();
    $theme = $setting?->landing_theme ?? 'default';
@endphp

<div class="w-full">
    @if ($submitted)
        <div class="flex items-center gap-3 rounded-2xl p-4 {{ $theme === 'pixigon' ? 'border border-[#86b53a]/60 bg-black/40 text-white shadow-xs' : 'border border-emerald-400/40 bg-emerald-950/80 text-emerald-200 shadow-md' }}">
            <flux:icon name="check-circle" class="size-5 shrink-0 {{ $theme === 'pixigon' ? 'text-[#86b53a]' : 'text-emerald-400' }}" />
            <div>
                <h4 class="font-bold text-xs {{ $theme === 'pixigon' ? 'text-[#86b53a]' : 'text-white' }}">{{ __('Terima kasih telah berlangganan!') }}</h4>
                <p class="text-[11px] {{ $theme === 'pixigon' ? 'text-zinc-300' : 'text-emerald-200/90' }}">{{ __('Alamat email Anda telah terdaftar.') }}</p>
            </div>
        </div>
    @else
        @if ($theme === 'pixigon')
            {{-- PIXIGON THEME NEWSLETTER (Dark Card Exact Style from Template) --}}
            <form wire:submit="subscribe" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <div class="relative flex-1">
                    <input
                        wire:model="email"
                        type="email"
                        placeholder="{{ __('Enter your email') }}"
                        required
                        class="w-full rounded-xl bg-black/30 border border-white/15 focus:border-[#86b53a] focus:ring-2 focus:ring-[#86b53a]/30 px-4 py-3 text-xs text-white placeholder-white/40 shadow-inner outline-none transition"
                    />
                </div>
                <button 
                    type="submit" 
                    class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#86b53a] hover:bg-[#7cb342] text-white font-bold text-xs px-5 py-3 shadow-md transition-all duration-200 shrink-0"
                >
                    <span>{{ __('Subscribe') }}</span>
                </button>
            </form>
        @else
            {{-- DEFAULT THEME NEWSLETTER --}}
            <form wire:submit="subscribe" class="flex flex-col gap-2 w-full">
                <div class="relative w-full">
                    <input
                        wire:model="email"
                        type="email"
                        placeholder="{{ __('Masukkan alamat email...') }}"
                        required
                        class="w-full rounded-xl bg-white border border-emerald-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-400/20 px-3.5 py-2.5 text-xs text-zinc-900 placeholder-zinc-400 shadow-sm outline-none transition"
                    />
                </div>
                <button 
                    type="submit" 
                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-emerald-950 font-extrabold text-xs py-2.5 px-4 shadow-md transition"
                >
                    <flux:icon name="paper-airplane" class="size-3.5" />
                    <span>{{ __('Berlangganan') }}</span>
                </button>
            </form>
        @endif

        @error('email')
            <div class="mt-2 flex items-center gap-1.5 text-xs font-bold {{ $theme === 'pixigon' ? 'text-amber-300' : 'text-rose-400' }}">
                <flux:icon name="exclamation-circle" class="size-3.5 shrink-0" />
                <span>{{ $message }}</span>
            </div>
        @enderror
    @endif
</div>
