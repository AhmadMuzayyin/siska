@props(['setting' => null])

<footer class="border-t-2 border-emerald-500/30 bg-[#06382b] text-emerald-100 dark:bg-[#03241b]">
    <div class="mx-auto max-w-7xl px-6 py-16">
        <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Brand Column --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold shadow-md">
                        <x-app-logo-icon class="size-6 fill-current" />
                    </div>
                    <span class="text-lg font-extrabold tracking-tight text-white">
                        {{ $setting?->lembaga ?? config('app.name') }}
                    </span>
                </div>
                <flux:text class="text-xs leading-relaxed text-emerald-200/80">
                    {{ $setting?->meta_deskripsi ?? __('Sistem Informasi Akademik Terpadu untuk pengelolaan santri, kurikulum Tilawati, dan administrasi keuangan secara transparan.') }}
                </flux:text>
                <div class="flex items-center gap-2 pt-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/40 bg-emerald-500/10 px-3 py-1 text-[11px] font-semibold text-emerald-300">
                        <span class="size-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        {{ __('Metode Tilawati Nurul Falah') }}
                    </span>
                </div>
            </div>

            {{-- Quick Links Column --}}
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-300">{{ __('Tautan Cepat') }}</h3>
                <nav class="mt-4 flex flex-col gap-2.5 text-sm" aria-label="{{ __('Tautan cepat') }}">
                    <a href="{{ route('home') }}" wire:navigate class="transition hover:text-white">{{ __('Beranda') }}</a>
                    <a href="{{ route('program') }}" wire:navigate class="transition hover:text-white">{{ __('Program & Kurikulum') }}</a>
                    <a href="{{ route('galeri') }}" wire:navigate class="transition hover:text-white">{{ __('Galeri Dokumentasi') }}</a>
                    <a href="{{ route('about') }}" wire:navigate class="transition hover:text-white">{{ __('Tentang Kami') }}</a>
                    <a href="{{ route('contact.show') }}" wire:navigate class="transition hover:text-white">{{ __('Kontak Kami') }}</a>
                    <a href="{{ route('santri.register.form') }}" wire:navigate class="font-semibold text-amber-300 transition hover:text-white">{{ __('Daftar Santri Baru') }}</a>
                </nav>
            </div>

            {{-- Legal Links Column --}}
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-300">{{ __('Legal & Informasi') }}</h3>
                <nav class="mt-4 flex flex-col gap-2.5 text-sm" aria-label="{{ __('Tautan legal') }}">
                    <a href="{{ route('privacy') }}" wire:navigate class="transition hover:text-white">{{ __('Kebijakan Privasi') }}</a>
                    <a href="{{ route('terms') }}" wire:navigate class="transition hover:text-white">{{ __('Syarat & Ketentuan') }}</a>
                    <a href="{{ route('cookies') }}" wire:navigate class="transition hover:text-white">{{ __('Kebijakan Cookie') }}</a>
                </nav>
            </div>

            {{-- Contact Info Column --}}
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-300">{{ __('Kontak & Alamat') }}</h3>
                <div class="mt-4 flex flex-col gap-3 text-xs leading-relaxed text-emerald-100">
                    @if ($setting?->alamat)
                        <div class="flex items-start gap-2.5">
                            <flux:icon name="map-pin" class="mt-0.5 size-4 shrink-0 text-amber-400" />
                            <span>{{ $setting->alamat }}</span>
                        </div>
                    @endif
                    @if ($setting?->telepon)
                        <div class="flex items-center gap-2.5">
                            <flux:icon name="phone" class="size-4 shrink-0 text-amber-400" />
                            <a href="tel:{{ $setting->telepon }}" class="hover:text-white">{{ $setting->telepon }}</a>
                        </div>
                    @endif
                    @if ($setting?->email)
                        <div class="flex items-center gap-2.5">
                            <flux:icon name="envelope" class="size-4 shrink-0 text-amber-400" />
                            <a href="mailto:{{ $setting->email }}" class="hover:text-white">{{ $setting->email }}</a>
                        </div>
                    @endif
                </div>

                <div class="mt-6 border-t border-emerald-800/80 pt-4">
                    <livewire:public.newsletter-form />
                </div>
            </div>
        </div>

        <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-emerald-800/80 pt-8 text-center text-xs text-emerald-300/80 sm:flex-row sm:text-left">
            <p>&copy; {{ now()->year }} {{ $setting?->lembaga ?? config('app.name') }}. {{ __('Seluruh hak cipta dilindungi.') }}</p>
            <p class="text-emerald-300/80">
                {{ __('Sistem Informasi Akademik v2') }} &bull; <span class="text-amber-300 font-semibold">{{ __('Tilawati Digital') }}</span>
            </p>
        </div>
    </div>

    @if ($setting?->fitur_pesan_whatsapp && $setting->telepon)
        <a
            href="https://wa.me/{{ \App\Rules\IndonesianPhoneNumber::normalize($setting->telepon) }}?text={{ urlencode($setting->pesan_whatsapp ?? __('Assalamu\'alaikum, saya ingin bertanya tentang :lembaga.', ['lembaga' => $setting->lembaga])) }}"
            target="_blank"
            rel="noopener"
            class="fixed right-6 bottom-6 z-30 flex size-14 items-center justify-center rounded-full bg-emerald-600 text-white shadow-2xl ring-4 ring-emerald-500/30 transition duration-300 hover:scale-110 hover:bg-emerald-500"
            aria-label="{{ __('Hubungi kami via WhatsApp') }}"
        >
            <flux:icon name="chat-bubble-left-right" class="size-7" />
        </a>
    @endif
</footer>
