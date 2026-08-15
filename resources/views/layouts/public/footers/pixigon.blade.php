@props(['setting' => null])

@php
    $lembagaName = $setting?->lembaga ?? config('app.name');
@endphp

<footer class="relative bg-[#183207] text-zinc-300 pt-20 pb-12 overflow-hidden font-sans border-t border-white/10">
    <div class="container mx-auto px-4 sm:px-8 relative z-10">
        <div class="grid grid-cols-12 gap-y-12 gap-x-8 pb-16 border-b border-white/10">
            
            {{-- Column 1: Brand Info & Socials --}}
            <div class="col-span-12 lg:col-span-4 space-y-6">
                <div class="flex items-center gap-2.5">
                    <div class="size-9 rounded-xl bg-[#7cb342] text-white flex items-center justify-center font-black text-lg shadow-sm">
                        <svg class="size-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2L15 9H22L16.5 13.5L18.5 20.5L12 16L5.5 20.5L7.5 13.5L2 9H9L12 2Z"/>
                        </svg>
                    </div>
                    <span class="font-black text-2xl tracking-tight text-white uppercase">{{ $lembagaName }}</span>
                </div>

                <p class="text-xs sm:text-sm text-zinc-300/90 leading-relaxed max-w-sm">
                    {{ $setting?->meta_deskripsi ?? __('Pelajari ilmu Al-Qur\'an, dirasah Islamiyah, dan bangun karakter beradab bersama komunitas pembelajar dan asatidz berpengalaman.') }}
                </p>

                {{-- Social Icons in Dark Green Circular Badges --}}
                <div class="flex items-center gap-2.5 pt-2">
                    <a href="https://facebook.com" target="_blank" rel="noopener" class="size-10 rounded-full bg-white/10 hover:bg-[#7cb342] hover:text-white text-zinc-300 flex items-center justify-center transition-all shadow-xs" aria-label="Facebook">
                        <svg class="size-4 fill-current" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noopener" class="size-10 rounded-full bg-white/10 hover:bg-[#7cb342] hover:text-white text-zinc-300 flex items-center justify-center transition-all shadow-xs" aria-label="X Twitter">
                        <svg class="size-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://instagram.com" target="_blank" rel="noopener" class="size-10 rounded-full bg-white/10 hover:bg-[#7cb342] hover:text-white text-zinc-300 flex items-center justify-center transition-all shadow-xs" aria-label="Instagram">
                        <svg class="size-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="https://wa.me/{{ $setting?->telepon }}" target="_blank" rel="noopener" class="size-10 rounded-full bg-white/10 hover:bg-[#7cb342] hover:text-white text-zinc-300 flex items-center justify-center transition-all shadow-xs" aria-label="WhatsApp">
                        <flux:icon name="chat-bubble-left-right" class="size-4" />
                    </a>
                </div>
            </div>

            {{-- Column 2: Company / Lembaga --}}
            <div class="col-span-6 sm:col-span-3 lg:col-span-2 space-y-4">
                <h4 class="font-bold text-base text-white tracking-wide">{{ __('Lembaga') }}</h4>
                <ul class="space-y-2.5 text-xs text-zinc-300/80 font-medium">
                    <li><a href="{{ route('about') }}" wire:navigate class="hover:text-white hover:underline transition">{{ __('Tentang Kami') }}</a></li>
                    <li><a href="{{ route('program') }}" wire:navigate class="hover:text-white hover:underline transition">{{ __('Program Unggulan') }}</a></li>
                    <li><a href="{{ route('galeri') }}" wire:navigate class="hover:text-white hover:underline transition">{{ __('Galeri Kegiatan') }}</a></li>
                    <li><a href="{{ route('contact.show') }}" wire:navigate class="hover:text-white hover:underline transition">{{ __('Kontak Kami') }}</a></li>
                </ul>
            </div>

            {{-- Column 3: Learning / Pendidikan --}}
            <div class="col-span-6 sm:col-span-3 lg:col-span-2 space-y-4">
                <h4 class="font-bold text-base text-white tracking-wide">{{ __('Pendidikan') }}</h4>
                <ul class="space-y-2.5 text-xs text-zinc-300/80 font-medium">
                    <li><a href="{{ route('santri.register.form') }}" wire:navigate class="hover:text-white hover:underline transition">{{ __('Pendaftaran Santri') }}</a></li>
                    <li><a href="{{ route('program') }}#tpq" wire:navigate class="hover:text-white hover:underline transition">{{ __('TPQ Tilawati') }}</a></li>
                    <li><a href="{{ route('program') }}#mdta" wire:navigate class="hover:text-white hover:underline transition">{{ __('Madin Diniyah') }}</a></li>
                    <li><a href="{{ route('program') }}#tahfizh" wire:navigate class="hover:text-white hover:underline transition">{{ __('Tahfidz Qur\'an') }}</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white hover:underline transition">{{ __('Portal Login') }}</a></li>
                </ul>
            </div>

            {{-- Column 4: Newsletter Box (Dark Translucent Card Exact Pixigon Style) --}}
            <div class="col-span-12 lg:col-span-4">
                <div class="rounded-3xl bg-black/25 border border-white/10 p-6 sm:p-7 space-y-4 shadow-xl">
                    <h4 class="font-bold text-lg text-white">{{ __('Subscribe to newsletter') }}</h4>
                    <p class="text-xs text-zinc-300/80 leading-relaxed">
                        {{ __('Get program updates, tips, and free announcements straight to your inbox.') }}
                    </p>

                    <div>
                        <livewire:public.newsletter-form />
                    </div>

                    <p class="text-[11px] text-zinc-400/80 pt-1">
                        {{ __('We respect your privacy. Unsubscribe anytime.') }}
                    </p>
                </div>
            </div>

        </div>

        {{-- Bottom Copyright Row --}}
        <div class="pt-8 text-center text-xs text-zinc-400">
            <p>&copy; {{ date('Y') }} {{ $lembagaName }} . {{ __('Crafted & Designed with Pixigon Theme.') }}</p>
        </div>
    </div>

    @if ($setting?->fitur_pesan_whatsapp && $setting->telepon)
        <a
            href="https://wa.me/{{ \App\Rules\IndonesianPhoneNumber::normalize($setting->telepon) }}?text={{ urlencode($setting->pesan_whatsapp ?? __('Assalamu\'alaikum, saya ingin bertanya tentang :lembaga.', ['lembaga' => $setting->lembaga])) }}"
            target="_blank"
            rel="noopener"
            class="fixed right-6 bottom-6 z-30 flex size-12 items-center justify-center rounded-full bg-[#7cb342] text-white shadow-2xl ring-4 ring-lime-500/20 transition duration-300 hover:scale-110 hover:bg-[#689f38]"
            aria-label="{{ __('Hubungi kami via WhatsApp') }}"
        >
            <flux:icon name="chat-bubble-left-right" class="size-6" />
        </a>
    @endif
</footer>
