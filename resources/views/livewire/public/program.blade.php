@php
    $setting = \App\Models\Setting::query()->first();
    $theme = $setting?->landing_theme ?? 'default';
@endphp

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
                    {{ __('Program & Kurikulum') }}
                </h1>
                
                <div class="flex items-center justify-center gap-2 text-xs sm:text-sm font-medium text-zinc-500">
                    <a href="{{ route('home') }}" wire:navigate class="hover:text-[#2e5b18] transition">{{ __('Beranda') }}</a>
                    <span>/</span>
                    <span class="text-zinc-800 font-semibold">{{ __('Program Pendidikan') }}</span>
                </div>

                <div class="mt-4 flex justify-center text-zinc-400">
                    <svg class="size-5 animate-bounce" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
            </div>
        </section>

        {{-- Program List Section --}}
        <section class="py-20 bg-white">
            <div class="container mx-auto px-4 sm:px-6 max-w-6xl space-y-16">
                
                {{-- 1. TPQ Tilawati --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center rounded-3xl border border-[#d6eda6] bg-[#f0f8ec] p-8 sm:p-10 shadow-xs">
                    <div class="lg:col-span-7 space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="size-12 rounded-2xl bg-white text-[#2e5b18] flex items-center justify-center shadow-xs">
                                <flux:icon name="book-open" class="size-6 text-[#6bb82d]" />
                            </div>
                            <div>
                                <span class="px-3 py-1 rounded-full bg-[#6bb82d] text-white text-xs font-bold shadow-2xs">METODE TILAWATI</span>
                                <h3 class="text-2xl font-extrabold text-[#2e5b18] mt-1">{{ __('Taman Pendidikan Al-Qur\'an (TPQ)') }}</h3>
                            </div>
                        </div>

                        <p class="text-sm text-zinc-700 leading-relaxed">
                            {{ __('Program utama akselerasi baca Al-Qur\'an metode Tilawati dengan lagu rost yang mudah dan menyenangkan. Dimulai dari jilid 1 sampai Al-Qur\'an dan munaqosyah kelulusan bersanad.') }}
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-xs font-medium text-zinc-700">
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-[#6bb82d]" />
                                <span>{{ __('Tilawati Jilid 1 s/d 6') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-[#6bb82d]" />
                                <span>{{ __('Tajwid & Ghorib Al-Qur\'an') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-[#6bb82d]" />
                                <span>{{ __('Munaqosyah & Ijazah Resmi') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-[#6bb82d]" />
                                <span>{{ __('Hafalan Surat Pendek Juz 30') }}</span>
                            </div>
                        </div>

                        <div class="pt-3">
                            <a 
                                href="{{ route('santri.register.form') }}" 
                                wire:navigate 
                                class="inline-flex items-center gap-3 rounded-full bg-[#6bb82d] hover:bg-[#5ca828] text-white font-bold text-xs py-2 ps-6 pe-2 shadow-md transition-all group"
                            >
                                <span>{{ __('Daftar Kelas TPQ') }}</span>
                                <span class="size-8 rounded-full bg-[#4d8f1e] flex items-center justify-center text-white">
                                    <flux:icon name="arrow-up-right" class="size-4" />
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-5 rounded-3xl overflow-hidden shadow-md border-2 border-white">
                        <img 
                            src="https://images.unsplash.com/photo-1585036156171-384164a8c675?w=600&auto=format&fit=crop&q=80" 
                            alt="Santriwati Mengaji" 
                            class="w-full aspect-4/3 object-cover hover:scale-105 transition-transform duration-500"
                        >
                    </div>
                </div>

                {{-- 2. Madin Diniyah --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center rounded-3xl border border-[#d6eda6] bg-[#f0f8ec] p-8 sm:p-10 shadow-xs">
                    <div class="lg:col-span-5 order-2 lg:order-1 rounded-3xl overflow-hidden shadow-md border-2 border-white">
                        <img 
                            src="https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=600&auto=format&fit=crop&q=80" 
                            alt="Kajian Kitab Diniyah" 
                            class="w-full aspect-4/3 object-cover hover:scale-105 transition-transform duration-500"
                        >
                    </div>

                    <div class="lg:col-span-7 order-1 lg:order-2 space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="size-12 rounded-2xl bg-white text-[#2e5b18] flex items-center justify-center shadow-xs">
                                <flux:icon name="academic-cap" class="size-6 text-[#6bb82d]" />
                            </div>
                            <div>
                                <span class="px-3 py-1 rounded-full bg-[#6bb82d] text-white text-xs font-bold shadow-2xs">DIRASAH ISLAMIYAH</span>
                                <h3 class="text-2xl font-extrabold text-[#2e5b18] mt-1">{{ __('Madrasah Diniyah Takmiliyah (MDTA)') }}</h3>
                            </div>
                        </div>

                        <p class="text-sm text-zinc-700 leading-relaxed">
                            {{ __('Program penguatan dasar-dasar ilmu agama Islam meliputi Fiqih ibadah, Aqidah tauhid, Akhlaq, Bahasa Arab dasar, serta Tarikh Islam keteladanan Nabi.') }}
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-xs font-medium text-zinc-700">
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-[#6bb82d]" />
                                <span>{{ __('Fiqih Ibadah & Bersuci') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-[#6bb82d]" />
                                <span>{{ __('Kajian Aqidah & Akhlaqul Karimah') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-[#6bb82d]" />
                                <span>{{ __('Bahasa Arab & Mufrodat') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-[#6bb82d]" />
                                <span>{{ __('Tarikh Nabi & Sahabat') }}</span>
                            </div>
                        </div>

                        <div class="pt-3">
                            <a 
                                href="{{ route('santri.register.form') }}" 
                                wire:navigate 
                                class="inline-flex items-center gap-3 rounded-full bg-[#6bb82d] hover:bg-[#5ca828] text-white font-bold text-xs py-2 ps-6 pe-2 shadow-md transition-all group"
                            >
                                <span>{{ __('Daftar Kelas Madin') }}</span>
                                <span class="size-8 rounded-full bg-[#4d8f1e] flex items-center justify-center text-white">
                                    <flux:icon name="arrow-up-right" class="size-4" />
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 3. Tahfizh & Adab --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center rounded-3xl border border-[#d6eda6] bg-[#f0f8ec] p-8 sm:p-10 shadow-xs">
                    <div class="lg:col-span-7 space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="size-12 rounded-2xl bg-white text-[#2e5b18] flex items-center justify-center shadow-xs">
                                <flux:icon name="sparkles" class="size-6 text-[#6bb82d]" />
                            </div>
                            <div>
                                <span class="px-3 py-1 rounded-full bg-[#6bb82d] text-white text-xs font-bold shadow-2xs">TAHFIDZ & ADAB</span>
                                <h3 class="text-2xl font-extrabold text-[#2e5b18] mt-1">{{ __('Halaqah Tahfidzul Qur\'an & Mutaba\'ah') }}</h3>
                            </div>
                        </div>

                        <p class="text-sm text-zinc-700 leading-relaxed">
                            {{ __('Bimbingan hafalan Al-Qur\'an juz 30 dan surat-surat pilihan dengan metode talaqqi dan muroja\'ah berkesinambungan bersama ustadz/ustadzah hafizh.') }}
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-xs font-medium text-zinc-700">
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-[#6bb82d]" />
                                <span>{{ __('Setoran Hafalan Harian') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-[#6bb82d]" />
                                <span>{{ __('Muroja\'ah & Ujian Kenaikan Juz') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-[#6bb82d]" />
                                <span>{{ __('Buku Mutaba\'ah Terpantau') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-[#6bb82d]" />
                                <span>{{ __('Pembiasaan Shalat Dhuha & Berjamaah') }}</span>
                            </div>
                        </div>

                        <div class="pt-3">
                            <a 
                                href="{{ route('santri.register.form') }}" 
                                wire:navigate 
                                class="inline-flex items-center gap-3 rounded-full bg-[#6bb82d] hover:bg-[#5ca828] text-white font-bold text-xs py-2 ps-6 pe-2 shadow-md transition-all group"
                            >
                                <span>{{ __('Daftar Halaqah Tahfidz') }}</span>
                                <span class="size-8 rounded-full bg-[#4d8f1e] flex items-center justify-center text-white">
                                    <flux:icon name="arrow-up-right" class="size-4" />
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-5 rounded-3xl overflow-hidden shadow-md border-2 border-white">
                        <img 
                            src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=600&auto=format&fit=crop&q=80" 
                            alt="Halaqah Tahfizh" 
                            class="w-full aspect-4/3 object-cover hover:scale-105 transition-transform duration-500"
                        >
                    </div>
                </div>

            </div>
        </section>

        {{-- Call to action band --}}
        <section class="py-16 bg-[#2e5b18] text-white text-center">
            <div class="container mx-auto px-4 sm:px-6 max-w-3xl space-y-4">
                <h2 class="text-3xl font-extrabold">{{ __('Daftarkan Putra-Putri Anda Sekarang') }}</h2>
                <p class="text-white/80 text-sm">
                    {{ __('Pendaftaran santri baru dapat dilakukan langsung secara online melalui portal pendaftaran kami.') }}
                </p>
                <div class="pt-4 flex flex-wrap justify-center gap-4">
                    <a 
                        href="{{ route('santri.register.form') }}" 
                        wire:navigate 
                        class="inline-flex items-center gap-2 rounded-full bg-[#6bb82d] hover:bg-[#5ca828] text-white font-bold text-sm px-8 py-3 shadow-lg shadow-lime-600/30 transition-all"
                    >
                        <span>{{ __('Daftar Santri Baru') }}</span>
                        <flux:icon name="arrow-right" class="size-4" />
                    </a>
                    <a 
                        href="{{ route('contact.show') }}" 
                        wire:navigate 
                        class="inline-flex items-center gap-2 rounded-full border border-white/40 hover:bg-white/10 text-white font-bold text-sm px-6 py-3 transition-all"
                    >
                        <span>{{ __('Hubungi Kami') }}</span>
                    </a>
                </div>
            </div>
        </section>
    </div>
@else
    {{-- ================= DEFAULT THEME (KLASIK EMERALD) ================= --}}
    <div class="flex flex-col w-full overflow-hidden">
        {{-- Hero Banner --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-20 text-white border-b-2 border-emerald-500/30">
            <img
                src="https://images.unsplash.com/photo-1585036156171-384164a8c675?w=1400&q=80&auto=format&fit=crop"
                alt="Program Pendidikan Al-Hikmah"
                class="absolute inset-0 size-full object-cover opacity-20"
                loading="eager"
                width="1400" height="400"
            >
            <div class="relative mx-auto max-w-7xl px-6">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                    ✦ {{ __('Kurikulum & Pendidikan') }}
                </span>
                <flux:heading size="xl" class="text-4xl! font-extrabold text-white leading-tight sm:text-5xl!">
                    {{ __('Program Pendidikan & Kurikulum') }}
                </flux:heading>
                <p class="mt-4 max-w-2xl text-sm text-emerald-100/90 leading-relaxed">
                    {{ __('Program pembelajaran terstruktur yang memadukan pembacaan Al-Qur\'an metode Tilawati, ilmu diniyah, serta pembinaan karakter santri.') }}
                </p>
            </div>
        </section>

        {{-- Program Detail Sections (Soft Jade Mist Theme #edf7f4) --}}
        <section class="w-full bg-[#edf7f4] py-16 border-b border-emerald-500/20">
            <div class="mx-auto max-w-7xl px-6 space-y-16">
                {{-- 1. TPQ --}}
                <div id="tpq" class="grid grid-cols-1 gap-10 items-center lg:grid-cols-12 rounded-3xl border border-emerald-500/20 bg-white/95 p-8 shadow-md backdrop-blur-sm transition duration-300 hover:shadow-2xl">
                    <div class="lg:col-span-7 space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                <flux:icon name="book-open" class="size-6" />
                            </div>
                            <div>
                                <span class="rounded-full bg-emerald-900 px-3 py-1 text-xs font-bold text-emerald-200">TPQ TILAWATI</span>
                                <h3 class="text-2xl font-bold text-emerald-950 mt-1">{{ __('Taman Pendidikan Al-Qur\'an (TPQ)') }}</h3>
                            </div>
                        </div>

                        <p class="text-xs text-zinc-600 leading-relaxed">
                            {{ __('Program utama akselerasi baca Al-Qur\'an metode Tilawati dengan motto "Belajar Al-Qur\'an Mudah dan Menyenangkan". Dimulai dari tingkat PAUD hingga santri mengkhatamkan Al-Qur\'an 30 juz dan menerima ijazah resmi dari pusat Tilawati Nurul Falah Surabaya.') }}
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="flex items-start gap-3">
                                <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-semibold text-xs text-emerald-950">{{ __('Metode Tilawati Jilid 1 - 6') }}</h4>
                                    <p class="text-[11px] text-zinc-500">{{ __('Pembelajaran bertahap nada lagu Rost.') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-semibold text-xs text-emerald-950">{{ __('Tajwid & Ghorib') }}</h4>
                                    <p class="text-[11px] text-zinc-500">{{ __('Pemahaman hukum bacaan & bacaan khusus.') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-semibold text-xs text-emerald-950">{{ __('Munaqosyah & Ijazah') }}</h4>
                                    <p class="text-[11px] text-zinc-500">{{ __('Ujian resmi bersertifikasi Nurul Falah.') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-semibold text-xs text-emerald-950">{{ __('Hafalan Juz \'Amma') }}</h4>
                                    <p class="text-[11px] text-zinc-500">{{ __('Target hafalan surat-surat pendek.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-5 overflow-hidden rounded-2xl border-2 border-emerald-500/30">
                        <img src="https://images.unsplash.com/photo-1609220136736-443140cffec6?w=600&q=80&auto=format&fit=crop" alt="TPQ Tilawati" class="w-full aspect-4/3 object-cover rounded-2xl">
                    </div>
                </div>

                {{-- 2. MDTA --}}
                <div id="mdta" class="grid grid-cols-1 gap-10 items-center lg:grid-cols-12 rounded-3xl border border-emerald-500/20 bg-white/95 p-8 shadow-md backdrop-blur-sm transition duration-300 hover:shadow-2xl">
                    <div class="lg:col-span-5 order-2 lg:order-1 overflow-hidden rounded-2xl border-2 border-emerald-500/30">
                        <img src="https://images.unsplash.com/photo-1585036156171-384164a8c675?w=600&q=80&auto=format&fit=crop" alt="MDTA Diniyah" class="w-full aspect-4/3 object-cover rounded-2xl">
                    </div>
                    <div class="lg:col-span-7 order-1 lg:order-2 space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                <flux:icon name="academic-cap" class="size-6" />
                            </div>
                            <div>
                                <span class="rounded-full bg-emerald-900 px-3 py-1 text-xs font-bold text-emerald-200">DINIYAH (MDTA)</span>
                                <h3 class="text-2xl font-bold text-emerald-950 mt-1">{{ __('Madrasah Diniyah Takmiliyah Awwaliyah') }}</h3>
                            </div>
                        </div>

                        <p class="text-xs text-zinc-600 leading-relaxed">
                            {{ __('Program pendidikan diniyah yang memperdalam ilmu syariat dasar Islam. Santri diajarkan Fiqih praktis, Akidah, Akhlak, Bahasa Arab dasar, serta sejarah nabi dan sahabat untuk bekal ibadah harian yang benar.') }}
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="flex items-start gap-3">
                                <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-semibold text-xs text-emerald-950">{{ __('Fiqih Ibadah Praktis') }}</h4>
                                    <p class="text-[11px] text-zinc-500">{{ __('Tata cara thoharah, salat, & bersuci.') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-semibold text-xs text-emerald-950">{{ __('Akidah & Akhlak Islam') }}</h4>
                                    <p class="text-[11px] text-zinc-500">{{ __('Penanaman tauhid & adab bermasyarakat.') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-semibold text-xs text-emerald-950">{{ __('Bahasa Arab Dasar') }}</h4>
                                    <p class="text-[11px] text-zinc-500">{{ __('Mufrodat & kaidah percakapan sederhana.') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-semibold text-xs text-emerald-950">{{ __('Tarikh / Sejarah Islam') }}</h4>
                                    <p class="text-[11px] text-zinc-500">{{ __('Kisah keteladanan Nabi Muhammad SAW.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Tahfizh & Akhlak --}}
                <div id="tahfizh" class="grid grid-cols-1 gap-10 items-center lg:grid-cols-12 rounded-3xl border border-emerald-500/20 bg-white/95 p-8 shadow-md backdrop-blur-sm transition duration-300 hover:shadow-2xl">
                    <div class="lg:col-span-7 space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                <flux:icon name="heart" class="size-6" />
                            </div>
                            <div>
                                <span class="rounded-full bg-emerald-900 px-3 py-1 text-xs font-bold text-emerald-200">TAHFIDH & ADAB</span>
                                <h3 class="text-2xl font-bold text-emerald-950 mt-1">{{ __('Tahfizh & Pembinaan Karakter') }}</h3>
                            </div>
                        </div>

                        <p class="text-xs text-zinc-600 leading-relaxed">
                            {{ __('Bimbingan pembiasaan karakter Islami dan bimbingan hafalan Al-Qur\'an intensif (Tahfizh) dengan pendampingan langsung ustadz/ustadzah. Santri diajarkan disiplin salat berjamaah, berbakti kepada orang tua, serta adab terhadap sesama.') }}
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="flex items-start gap-3">
                                <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-semibold text-xs text-emerald-950">{{ __('Halaqah Tahfizh') }}</h4>
                                    <p class="text-[11px] text-zinc-500">{{ __('Setoran harian & muroja\'ah berkesinambungan.') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                                <div>
                                    <h4 class="font-semibold text-xs text-emerald-950">{{ __('Pembiasaan Ibadah Harian') }}</h4>
                                    <p class="text-[11px] text-zinc-500">{{ __('Salat dhuha & dzikir bersama.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-5 overflow-hidden rounded-2xl border-2 border-emerald-500/30">
                        <img src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=600&q=80&auto=format&fit=crop" alt="Tahfizh Qur'an" class="w-full aspect-4/3 object-cover rounded-2xl">
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA Band --}}
        <section class="w-full bg-gradient-to-r from-[#06382b] via-[#094a38] to-[#021d16] py-16 text-white text-center border-t-2 border-emerald-500/30">
            <div class="mx-auto max-w-4xl px-6 space-y-4">
                <flux:heading size="xl" class="text-3xl! font-bold text-white">{{ __('Daftarkan Putra-Putri Anda Sekarang') }}</flux:heading>
                <p class="text-emerald-100/90 text-xs max-w-xl mx-auto">
                    {{ __('Proses pendaftaran santri baru dapat dilakukan secara online melalui website ini.') }}
                </p>
                <div class="pt-4 flex justify-center gap-4">
                    <flux:button variant="primary" class="bg-emerald-500! text-emerald-950! font-extrabold hover:bg-emerald-400! shadow-xl border border-emerald-300 px-6 py-3" :href="route('santri.register.form')" wire:navigate>
                        <flux:icon name="user-plus" class="size-4 me-1.5" />
                        {{ __('Daftar Santri Baru') }}
                    </flux:button>
                    <flux:button variant="ghost" class="text-white! hover:bg-white/10! border border-emerald-500/30 px-5 py-3" :href="route('contact.show')" wire:navigate>
                        <flux:icon name="chat-bubble-left-right" class="size-4 me-1.5 text-emerald-200" />
                        {{ __('Hubungi Kami') }}
                    </flux:button>
                </div>
            </div>
        </section>
    </div>
@endif
