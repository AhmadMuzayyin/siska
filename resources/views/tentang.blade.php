@php
    $setting = \App\Models\Setting::query()->first();
    $theme = $setting?->landing_theme ?? 'default';
    $lembagaName = $setting?->lembaga ?? config('app.name');
@endphp

<x-layouts::public :title="__('Tentang Kami')">
    @if ($theme === 'pixigon')
        {{-- ================= PIXIGON THEME (SOFT GREEN LIGHT) ================= --}}
        <div class="flex flex-col w-full overflow-hidden font-sans bg-white text-zinc-800">
            
            {{-- Inner Page Hero Banner Matching Screenshot 2 --}}
            <section class="relative bg-[#f0f8ec] py-20 lg:py-28 overflow-hidden font-sans">
                {{-- Left Botanical Branch Doodle --}}
                <div class="hidden lg:block absolute left-8 top-1/2 -translate-y-1/2 pointer-events-none opacity-40 text-emerald-800">
                    <svg class="w-36 h-48" viewBox="0 0 120 160" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M60 150 C60 110, 40 70, 20 20" />
                        <path d="M45 90 C30 80, 20 85, 25 70 C30 55, 45 70, 45 90 Z" fill="currentColor" fill-opacity="0.1" />
                        <path d="M52 115 C68 105, 78 110, 73 95 C68 80, 52 95, 52 115 Z" fill="currentColor" fill-opacity="0.1" />
                        <path d="M35 55 C20 45, 15 50, 18 38 C22 25, 35 40, 35 55 Z" fill="currentColor" fill-opacity="0.1" />
                        <path d="M25 30 C35 15, 45 18, 40 28 Z" fill="currentColor" fill-opacity="0.1" />
                    </svg>
                </div>

                {{-- Left Ribbon Squiggle --}}
                <div class="hidden lg:block absolute left-36 bottom-8 pointer-events-none text-zinc-400">
                    <svg class="w-10 h-16" viewBox="0 0 40 60" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                        <path d="M10 5 C25 15, 5 30, 20 45 C30 55, 15 58, 25 60"/>
                    </svg>
                </div>

                {{-- Right Ribbon Squiggle --}}
                <div class="hidden lg:block absolute right-32 top-10 pointer-events-none text-zinc-400">
                    <svg class="w-12 h-14" viewBox="0 0 50 60" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                        <path d="M5 10 C20 5, 35 15, 30 30 C25 45, 10 35, 20 20 C30 5, 40 25, 45 40"/>
                    </svg>
                </div>

                {{-- Right Large Circular Arc Outline --}}
                <div class="hidden lg:block absolute -right-24 -bottom-24 size-80 rounded-full border border-lime-400/40 pointer-events-none"></div>

                {{-- Center Title & Breadcrumbs --}}
                <div class="container mx-auto px-4 sm:px-6 relative z-10 text-center max-w-4xl">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-zinc-900 tracking-tight mb-3">
                        {{ __('Tentang Kami') }}
                    </h1>
                    
                    <div class="flex items-center justify-center gap-2 text-xs sm:text-sm font-medium text-zinc-500">
                        <a href="{{ route('home') }}" wire:navigate class="hover:text-[#2e5b18] transition">{{ __('Beranda') }}</a>
                        <span>/</span>
                        <span class="text-zinc-800 font-semibold">{{ __('Tentang Lembaga') }}</span>
                    </div>

                    <div class="mt-4 flex justify-center text-zinc-400">
                        <svg class="size-5 animate-bounce" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>
            </section>

            {{-- Visi & Misi Section --}}
            <section class="py-20 bg-white">
                <div class="container mx-auto px-4 sm:px-6 max-w-6xl">
                    <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-[#2e5b18]">
                            {{ __('Visi & Misi Lembaga') }}
                        </h2>
                        <p class="text-zinc-600 text-sm sm:text-base">
                            {{ __('Landasan dan arah perjuangan kami dalam mencetak generasi pecinta Al-Qur\'an.') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
                        {{-- Visi Card --}}
                        <div class="rounded-3xl border border-[#d6eda6] bg-[#f0f8ec] p-8 sm:p-10 shadow-xs flex flex-col justify-between">
                            <div class="space-y-4">
                                <div class="size-14 rounded-2xl bg-white text-[#2e5b18] flex items-center justify-center shadow-xs">
                                    <flux:icon name="flag" class="size-7 text-[#6bb82d]" />
                                </div>
                                <h3 class="text-2xl font-extrabold text-[#2e5b18]">{{ __('Visi Utama') }}</h3>
                                <p class="text-sm text-zinc-700 leading-relaxed">
                                    {{ __('Menjadi lembaga pendidikan Al-Qur\'an terdepan yang melahirkan generasi Qurani berakhlak mulia, berprestasi, mandiri, dan berkhidmat untuk umat.') }}
                                </p>
                            </div>
                        </div>

                        {{-- Misi Card --}}
                        <div class="rounded-3xl border border-[#d6eda6] bg-[#f0f8ec] p-8 sm:p-10 shadow-xs flex flex-col justify-between">
                            <div class="space-y-4">
                                <div class="size-14 rounded-2xl bg-white text-[#2e5b18] flex items-center justify-center shadow-xs">
                                    <flux:icon name="check-badge" class="size-7 text-[#6bb82d]" />
                                </div>
                                <h3 class="text-2xl font-extrabold text-[#2e5b18]">{{ __('Misi Lembaga') }}</h3>
                                <ul class="space-y-3 text-xs sm:text-sm text-zinc-700">
                                    <li class="flex items-start gap-2.5">
                                        <flux:icon name="check-circle" class="size-4 shrink-0 text-[#6bb82d] mt-0.5" />
                                        <span>{{ __('Menyelenggarakan pembelajaran Al-Qur\'an metode Tilawati secara intensif dan tartil.') }}</span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <flux:icon name="check-circle" class="size-4 shrink-0 text-[#6bb82d] mt-0.5" />
                                        <span>{{ __('Membentuk adab dan karakter santri sesuai tuntunan Al-Qur\'an dan Sunnah.') }}</span>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <flux:icon name="check-circle" class="size-4 shrink-0 text-[#6bb82d] mt-0.5" />
                                        <span>{{ __('Mengembangkan kurikulum diniyah komprehensif (fiqih, aqidah, akhlaq, bahasa Arab).') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Mengapa Memilih Kami & Metode Pembelajaran --}}
            <section class="py-20 bg-gradient-to-r from-[#e8f5e1] via-[#f0f8ec] to-[#e8f5e1]">
                <div class="container mx-auto px-4 sm:px-6 max-w-6xl">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                        <div class="lg:col-span-5 rounded-3xl overflow-hidden shadow-lg border-2 border-white">
                            <img 
                                src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=800&auto=format&fit=crop&q=80" 
                                alt="Santri Belajar Bersama" 
                                class="w-full h-full object-cover"
                            >
                        </div>
                        <div class="lg:col-span-7 space-y-6">
                            <span class="inline-flex items-center gap-2 border border-[#2e5b18]/20 bg-white/60 rounded-full px-4 py-1 text-xs font-semibold text-[#2e5b18]">
                                ✦ {{ __('Karakteristik & Keunggulan') }}
                            </span>
                            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#2e5b18] leading-tight">
                                {{ __('Mengapa Memilih :lembaga?', ['lembaga' => $lembagaName]) }}
                            </h2>
                            <p class="text-sm text-zinc-600 leading-relaxed">
                                {{ __('Kami memadukan kenyamanan belajar anak dengan standarisasi kurikulum nasional Tilawati Nurul Falah. Setiap santri didampingi secara sabar untuk mencapai ketuntasan membaca dan hafalan.') }}
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                                <div class="p-5 rounded-2xl bg-white border border-[#d6eda6] shadow-xs text-center">
                                    <div class="size-10 rounded-xl bg-[#f0f8ec] text-[#2e5b18] flex items-center justify-center mx-auto mb-2">
                                        <flux:icon name="book-open" class="size-5 text-[#6bb82d]" />
                                    </div>
                                    <h4 class="font-bold text-xs text-zinc-900">{{ __('Metode Tilawati') }}</h4>
                                    <p class="text-[11px] text-zinc-500 mt-1">{{ __('Lagu Rost Praktis') }}</p>
                                </div>

                                <div class="p-5 rounded-2xl bg-white border border-[#d6eda6] shadow-xs text-center">
                                    <div class="size-10 rounded-xl bg-[#f0f8ec] text-[#2e5b18] flex items-center justify-center mx-auto mb-2">
                                        <flux:icon name="users" class="size-5 text-[#6bb82d]" />
                                    </div>
                                    <h4 class="font-bold text-xs text-zinc-900">{{ __('Pendampingan') }}</h4>
                                    <p class="text-[11px] text-zinc-500 mt-1">{{ __('Klasikal & Privat') }}</p>
                                </div>

                                <div class="p-5 rounded-2xl bg-white border border-[#d6eda6] shadow-xs text-center">
                                    <div class="size-10 rounded-xl bg-[#f0f8ec] text-[#2e5b18] flex items-center justify-center mx-auto mb-2">
                                        <flux:icon name="academic-cap" class="size-5 text-[#6bb82d]" />
                                    </div>
                                    <h4 class="font-bold text-xs text-zinc-900">{{ __('Munaqosyah') }}</h4>
                                    <p class="text-[11px] text-zinc-500 mt-1">{{ __('Syahadah Resmi') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @else
        {{-- ================= DEFAULT THEME (KLASIK EMERALD) ================= --}}
        <div class="flex flex-col w-full overflow-hidden">
            {{-- Hero --}}
            <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-20 text-white border-b-2 border-emerald-500/30">
                <img
                    src="https://images.unsplash.com/photo-1585036156171-384164a8c675?w=1400&q=80&auto=format&fit=crop"
                    alt="Santri mengaji Al-Hikmah"
                    class="absolute inset-0 size-full object-cover opacity-20"
                    loading="eager"
                    width="1400" height="500"
                >
                <div class="relative mx-auto max-w-7xl px-6">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                        ✦ {{ __('Profil & Identitas') }}
                    </span>
                    <flux:heading size="xl" class="text-4xl! font-extrabold text-white leading-tight sm:text-5xl!">
                        {{ __('Mendidik Generasi Qurani Beradab') }}
                    </flux:heading>
                    <p class="mt-4 max-w-2xl text-sm text-emerald-100/90 leading-relaxed">
                        {{ __('TPQ & Madin Al-Hikmah adalah lembaga pendidikan Al-Qur\'an dan Diniyah yang berkomitmen mencetak generasi berakhlak mulia menggunakan metode Tilawati — belajar Al-Qur\'an mudah dan menyenangkan.') }}
                    </p>
                </div>
            </section>

            {{-- Motto Ticker Bar --}}
            <div class="w-full border-b border-emerald-500/30 bg-gradient-to-r from-[#059669] via-[#06382b] to-[#059669] py-4 text-white">
                <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-6 text-center text-xs font-bold uppercase tracking-widest text-emerald-100 sm:text-sm">
                    <span class="mx-auto sm:mx-0 flex items-center gap-2">
                        <span class="text-amber-300">✦</span> {{ __('Mengaji untuk Masa Depan') }}
                    </span>
                    <span class="hidden md:inline text-amber-300">✦</span>
                    <span class="hidden md:inline">{{ __('Metode Tilawati Mudah & Menyenangkan') }}</span>
                    <span class="hidden lg:inline text-amber-300">✦</span>
                    <span class="hidden lg:inline">{{ __('Ijazah Resmi Nurul Falah Surabaya') }}</span>
                </div>
            </div>

            {{-- Visi & Misi Section (Soft Sage Theme #dcf0ea) --}}
            <section class="w-full bg-[#dcf0ea] py-20 border-b border-emerald-500/20">
                <div class="mx-auto max-w-7xl px-6">
                    <div class="mb-12 max-w-xl">
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-600/30 bg-emerald-600/10 px-3.5 py-1 text-xs font-bold text-emerald-800 mb-3">
                            ✦ {{ __('Landasan Utama') }}
                        </span>
                        <flux:heading size="xl" class="text-3xl! font-bold text-emerald-950 sm:text-4xl!">{{ __('Visi & Misi Lembaga') }}</flux:heading>
                        <p class="mt-2 text-sm text-emerald-900/80">{{ __('Landasan dan arah perjuangan kami dalam mendidik generasi Qurani.') }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                        <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-8 shadow-md backdrop-blur-sm transition duration-300 hover:shadow-2xl">
                            <div class="flex size-14 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 mb-6">
                                <flux:icon name="flag" class="size-7" />
                            </div>
                            <h3 class="text-2xl font-bold text-emerald-950">{{ __('Visi Utama') }}</h3>
                            <p class="mt-3 text-xs text-zinc-600 leading-relaxed">
                                {{ __('Menjadi lembaga pendidikan Al-Qur\'an terkemuka yang menghasilkan generasi Qurani yang berakhlak mulia, berprestasi, dan bermanfaat bagi agama, orang tua, serta masyarakat.') }}
                            </p>
                        </div>

                        <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-8 shadow-md backdrop-blur-sm transition duration-300 hover:shadow-2xl">
                            <div class="flex size-14 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 mb-6">
                                <flux:icon name="check-badge" class="size-7" />
                            </div>
                            <h3 class="text-2xl font-bold text-emerald-950">{{ __('Misi Lembaga') }}</h3>
                            <ul class="mt-4 flex flex-col gap-3 text-xs text-zinc-600">
                                <li class="flex items-start gap-2.5">
                                    <flux:icon name="check-circle" class="mt-0.5 size-4 shrink-0 text-emerald-600" />
                                    <span>{{ __('Menyelenggarakan pendidikan Al-Qur\'an yang berkualitas dengan metode pembelajaran yang efektif dan menyenangkan.') }}</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <flux:icon name="check-circle" class="mt-0.5 size-4 shrink-0 text-emerald-600" />
                                    <span>{{ __('Membentuk karakter dan kepribadian santri berdasarkan nilai-nilai Islam dan Al-Qur\'an.') }}</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <flux:icon name="check-circle" class="mt-0.5 size-4 shrink-0 text-emerald-600" />
                                    <span>{{ __('Mengembangkan potensi santri melalui program pendidikan diniyah komprehensif.') }}</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <flux:icon name="check-circle" class="mt-0.5 size-4 shrink-0 text-emerald-600" />
                                    <span>{{ __('Membangun kerja sama yang baik dengan orang tua dan masyarakat dalam mendidik generasi Qurani.') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endif
</x-layouts::public>
