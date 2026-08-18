@php
    $setting = $this->setting;
    $heroBadge = $setting?->getLandingContent('hero_badge', __('Sistem Informasi Akademik Terpadu'), 'default');
    $heroTitle = $setting?->getLandingContent('hero_title', $setting?->lembaga ?? config('app.name'), 'default');
    $heroSubtitle = $setting?->getLandingContent('hero_subtitle', $setting?->meta_deskripsi ?? __('Mengelola pendidikan Al-Qur\'an dan diniyah santri secara digital — akademik, presensi RFID, nilai, dan keuangan dalam satu sistem terpadu.'), 'default');
    $heroCtaPrimary = $setting?->getLandingContent('hero_cta_primary_text', __('Daftar Santri Baru'), 'default');
    $heroCtaSecondary = $setting?->getLandingContent('hero_cta_secondary_text', __('Lihat Program'), 'default');

    $whyUsBadge = $setting?->getLandingContent('why_us_badge', __('Mengapa Memilih Kami'), 'default');
    $whyUsTitle = $setting?->getLandingContent('why_us_title', __('Komitmen Menyajikan Pendidikan Al-Qur\'an Berkualitas & Berintegritas'), 'default');
    $whyUsSubtitle = $setting?->getLandingContent('why_us_subtitle', __('Kami memadukan metode pembelajaran Al-Qur\'an teruji nasional dengan tata kelola akademik digital modern untuk kenyamanan santri dan kepastian perkembangan anak bagi orang tua.'), 'default');

    $programsBadge = $setting?->getLandingContent('programs_badge', __('Kurikulum & Pendidikan'), 'default');
    $programsTitle = $setting?->getLandingContent('programs_title', __('Program Pendidikan Unggulan'), 'default');
    $programsSubtitle = $setting?->getLandingContent('programs_subtitle', __('Program terstruktur yang dirancang untuk memandu santri dari dasar hingga khatam.'), 'default');

    $statsTitle = $setting?->getLandingContent('stats_title', __('Statistik Lembaga'), 'default');

    $galleryBadge = $setting?->getLandingContent('gallery_badge', __('Dokumentasi Kegiatan'), 'default');
    $galleryTitle = $setting?->getLandingContent('gallery_title', __('Galeri Foto Unggulan'), 'default');
    $gallerySubtitle = $setting?->getLandingContent('gallery_subtitle', __('Dokumentasi aktivitas dan momentum berharga santri lembaga kami.'), 'default');

    $teachersBadge = $setting?->getLandingContent('teachers_badge', __('Tenaga Pendidik'), 'default');
    $teachersTitle = $setting?->getLandingContent('teachers_title', __('Para Pengajar'), 'default');
    $teachersSubtitle = $setting?->getLandingContent('teachers_subtitle', __('Ustadz dan Ustadzah kompeten yang berdedikasi mendampingi santri.'), 'default');

    $testimonialsBadge = $setting?->getLandingContent('testimonials_badge', __('Testimoni'), 'default');
    $testimonialsTitle = $setting?->getLandingContent('testimonials_title', __('Kata Wali Santri & Alumni'), 'default');
    $testimonialsSubtitle = $setting?->getLandingContent('testimonials_subtitle', __('Pengalaman wali murid dan santri belajar bersama lembaga kami.'), 'default');

    $faqBadge = $setting?->getLandingContent('faq_badge', __('Pertanyaan Umum'), 'default');
    $faqTitle = $setting?->getLandingContent('faq_title', __('Pertanyaan Yang Sering Diajukan'), 'default');
    $faqSubtitle = $setting?->getLandingContent('faq_subtitle', __('Informasi penting seputar pendaftaran, kurikulum, dan sistem akademik.'), 'default');

    $ctaTitle = $setting?->getLandingContent('cta_title', __('Tertarik Mendaftarkan Putra-Putri Anda?'), 'default');
    $ctaSubtitle = $setting?->getLandingContent('cta_subtitle', __('Mari bergabung bersama keluarga besar lembaga kami untuk mencetak generasi Qurani yang berakhlak mulia dan mandiri.'), 'default');
    $ctaButtonText = $setting?->getLandingContent('cta_button_text', __('Daftar Sekarang'), 'default');

    $heroSlide1 = $setting?->getLandingContent('hero_slide_1_image', 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=1600&q=80&auto=format&fit=crop', 'default');
    $heroSlide2 = $setting?->getLandingContent('hero_slide_2_image', 'https://images.unsplash.com/photo-1609220136736-443140cffec6?w=1600&q=80&auto=format&fit=crop', 'default');
    $heroSlide3 = $setting?->getLandingContent('hero_slide_3_image', 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=1600&q=80&auto=format&fit=crop', 'default');
    $whyUsImage = $setting?->getLandingContent('why_us_image', 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&q=80&auto=format&fit=crop', 'default');

    $heroSlides = [
        [
            'image' => $heroSlide1,
            'badge' => $heroBadge,
            'title' => $heroTitle,
            'subtitle' => $heroSubtitle,
        ],
        [
            'image' => $heroSlide2,
            'badge' => __('Metode Tilawati & Ijazah Resmi'),
            'title' => __('Mencetak Generasi Rabbani & Beradab'),
            'subtitle' => __('Pendidikan Al-Qur\'an terstruktur dengan metode praktis, menyenangkan, dan terstandarisasi nasional Nurul Falah Surabaya.'),
        ],
        [
            'image' => $heroSlide3,
            'badge' => __('Penerimaan Santri Baru Dibuka'),
            'title' => __('Madrasah Diniyah & TPQ Modern'),
            'subtitle' => __('Pembelajaran Fiqih, Akidah, Hadits, dan Bahasa Arab yang didampingi oleh Ustadz & Ustadzah berpengalaman.'),
        ],
    ];
@endphp

<div class="flex flex-col w-full overflow-hidden">
    {{-- 1. Hero Section (Full-Width Auto-Play Slider with 2-Column Desktop Grid & Floating Admission Widget) --}}
    <section
        x-data="heroSliderComponent({{ json_encode($heroSlides) }})"
        data-editable-image="hero_slide_1_image"
        data-image-label="Ganti Foto Background Slider"
        class="relative w-full overflow-hidden bg-emerald-950 text-white min-h-[680px] lg:min-h-[750px] flex items-center"
        aria-labelledby="hero-heading"
    >
        {{-- Background Image Slides --}}
        <template x-for="(slide, index) in slides" :key="index">
            <div
                x-show="activeSlide === index"
                x-transition:enter="transition-opacity duration-1000 ease-in-out"
                x-transition:enter-start="opacity-0 scale-105"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition-opacity duration-1000 ease-in-out"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-105"
                class="absolute inset-0 size-full"
            >
                <img
                    :src="slide.image"
                    :alt="slide.title"
                    class="size-full object-cover object-center"
                    loading="eager"
                >
            </div>
        </template>

        {{-- Dark Gradient Overlays --}}
        <div class="absolute inset-0 bg-gradient-to-r from-[#021d16]/95 via-[#06382b]/90 to-[#06382b]/60 z-10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#021d16] via-transparent to-[#021d16]/40 z-10"></div>
        <div class="pointer-events-none absolute -top-24 -left-24 size-96 rounded-full bg-emerald-400/20 blur-3xl z-10"></div>

        {{-- Hero Container (2-Column Desktop Grid for Dense, Impactful Hero) --}}
        <div class="relative z-20 mx-auto w-full max-w-7xl px-6 py-20 lg:py-28">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-center">
                {{-- Left Content Column (7 cols) --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2.5 rounded-full border border-emerald-400/40 bg-emerald-500/20 px-4 py-1.5 text-xs font-bold text-emerald-200 backdrop-blur-md shadow-lg">
                        <span class="flex size-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span x-text="slides[activeSlide] ? slides[activeSlide].badge : ''"></span>
                    </div>

                    <flux:heading id="hero-heading" size="xl" class="text-4xl! font-extrabold leading-tight tracking-tight sm:text-5xl! lg:text-6xl! text-white drop-shadow-md" x-text="slides[activeSlide] ? slides[activeSlide].title : ''">
                    </flux:heading>

                    <p class="max-w-2xl text-base leading-relaxed text-emerald-100/95 font-normal sm:text-lg drop-shadow-sm" x-text="slides[activeSlide] ? slides[activeSlide].subtitle : ''">
                    </p>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <flux:button variant="primary" class="bg-emerald-500! hover:bg-emerald-400! text-emerald-950! font-extrabold text-sm px-6 py-3 shadow-xl border border-emerald-300/50 transition-all duration-300 transform hover:-translate-y-0.5" :href="route('santri.register.form')" wire:navigate>
                            <flux:icon name="user-plus" class="size-4 me-1.5 text-emerald-950" />
                            {{ __('Daftar Santri Baru') }}
                        </flux:button>
                        <flux:button variant="ghost" :href="route('program')" wire:navigate class="text-white! hover:bg-white/10! border border-emerald-400/30 font-semibold px-5 py-3">
                            {{ __('Lihat Program') }}
                        </flux:button>
                        <flux:button variant="ghost" :href="route('contact.show')" wire:navigate class="text-emerald-200! hover:bg-emerald-800/40! border border-emerald-500/20 px-5 py-3">
                            {{ __('Hubungi Kami') }}
                        </flux:button>
                    </div>

                    <div class="flex flex-wrap items-center gap-6 border-t border-emerald-700/60 pt-6 text-xs font-semibold text-emerald-200">
                        <div class="flex items-center gap-2">
                            <flux:icon name="check-badge" class="size-4 text-emerald-400" />
                            <span>{{ __('Metode Tilawati Nurul Falah') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <flux:icon name="shield-check" class="size-4 text-emerald-400" />
                            <span>{{ __('Ijazah Resmi Pusat') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <flux:icon name="device-phone-mobile" class="size-4 text-emerald-400" />
                            <span>{{ __('Presensi Digital RFID & WA') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Glassmorphism Floating Quick Admission & Info Widget (5 cols - eCademy Style) --}}
                <div class="lg:col-span-5 hidden sm:block">
                    <div class="relative rounded-3xl border border-emerald-400/30 bg-[#06382b]/80 p-7 shadow-2xl backdrop-blur-xl">
                        <div class="absolute -top-3 right-6 rounded-full border border-amber-400/50 bg-amber-500 px-3.5 py-1 text-[11px] font-black uppercase tracking-wider text-amber-950 shadow-lg animate-bounce">
                            ✦ {{ __('PPDB Dibuka') }}
                        </div>

                        <div class="flex items-center gap-3 border-b border-emerald-700/80 pb-5">
                            <div class="flex size-11 items-center justify-center rounded-2xl bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                                <flux:icon name="academic-cap" class="size-6" />
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-base">{{ __('Penerimaan Santri Baru') }}</h3>
                                <p class="text-xs text-emerald-200/80">{{ __('Tahun Ajaran 2026/2027') }}</p>
                            </div>
                        </div>

                        <div class="mt-5 space-y-3.5 text-xs text-emerald-100">
                            <div class="flex items-center justify-between rounded-xl bg-[#021d16]/70 p-3 border border-emerald-800/80">
                                <span class="flex items-center gap-2 font-medium">
                                    <flux:icon name="book-open" class="size-4 text-emerald-400" />
                                    {{ __('TPQ (Tilawati Jilid 1-6)') }}
                                </span>
                                <span class="rounded-md bg-emerald-500/20 px-2 py-0.5 text-[10px] font-bold text-emerald-300">{{ __('Tersedia') }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-xl bg-[#021d16]/70 p-3 border border-emerald-800/80">
                                <span class="flex items-center gap-2 font-medium">
                                    <flux:icon name="building-library" class="size-4 text-emerald-400" />
                                    {{ __('Madrasah Diniyah Takmiliyah') }}
                                </span>
                                <span class="rounded-md bg-emerald-500/20 px-2 py-0.5 text-[10px] font-bold text-emerald-300">{{ __('Tersedia') }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-xl bg-[#021d16]/70 p-3 border border-emerald-800/80">
                                <span class="flex items-center gap-2 font-medium">
                                    <flux:icon name="sparkles" class="size-4 text-emerald-400" />
                                    {{ __('Tahfidh & Pembinaan Akhlak') }}
                                </span>
                                <span class="rounded-md bg-emerald-500/20 px-2 py-0.5 text-[10px] font-bold text-emerald-300">{{ __('Tersedia') }}</span>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between border-t border-emerald-700/80 pt-5">
                            <div class="text-xs">
                                <span class="block text-emerald-300/80 text-[11px]">{{ __('Santri Terdaftar:') }}</span>
                                <span class="font-extrabold text-amber-300 text-sm">{{ $this->santriAktifCount }}+ {{ __('Santri') }}</span>
                            </div>
                            <flux:button variant="primary" class="bg-amber-400! hover:bg-amber-300! text-amber-950! font-extrabold text-xs px-4 py-2.5 shadow-md border border-amber-300" :href="route('santri.register.form')" wire:navigate>
                                {{ __('Daftar Online') }}
                                <flux:icon name="arrow-right" class="size-3.5 ms-1" />
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slider Navigation Controls --}}
        <div class="absolute bottom-6 left-0 right-0 z-30 flex items-center justify-between mx-auto max-w-7xl px-6 pointer-events-none">
            <div class="flex items-center gap-2 pointer-events-auto">
                <template x-for="(slide, index) in slides" :key="index">
                    <button
                        type="button"
                        x-on:click="activeSlide = index"
                        class="h-2.5 rounded-full transition-all duration-300"
                        :class="activeSlide === index ? 'w-8 bg-emerald-400 shadow-md' : 'w-2.5 bg-white/40 hover:bg-white/70'"
                        :aria-label="'Slide ' + (index + 1)"
                    ></button>
                </template>
            </div>

            <div class="flex items-center gap-2 pointer-events-auto">
                <button
                    type="button"
                    x-on:click="prev()"
                    class="flex size-9 items-center justify-center rounded-full border border-white/20 bg-emerald-950/60 text-white backdrop-blur-md transition hover:bg-emerald-900/80"
                    aria-label="Previous Slide"
                >
                    <flux:icon name="chevron-left" class="size-4" />
                </button>
                <button
                    type="button"
                    x-on:click="next()"
                    class="flex size-9 items-center justify-center rounded-full border border-white/20 bg-emerald-950/60 text-white backdrop-blur-md transition hover:bg-emerald-900/80"
                    aria-label="Next Slide"
                >
                    <flux:icon name="chevron-right" class="size-4" />
                </button>
            </div>
        </div>
    </section>

    {{-- 2. Floating Quick Highlights Bar (4-Column Feature Grid Overlapping Hero) --}}
    <section class="relative z-30 w-full bg-[#dcf0ea] py-6 border-b border-emerald-500/20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Feature 1 --}}
                <div class="flex items-start gap-4 rounded-2xl border border-emerald-500/20 bg-white/95 p-4 shadow-md backdrop-blur-md transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold shadow-md">
                        <flux:icon name="book-open" class="size-5" />
                    </div>
                    <div>
                        <h4 class="font-bold text-emerald-950 text-sm">{{ __('Metode Tilawati') }}</h4>
                        <p class="mt-1 text-xs text-zinc-600 leading-relaxed">{{ __('Pembelajaran Al-Qur\'an terstandarisasi dengan lagu Rost yang khas.') }}</p>
                    </div>
                </div>

                {{-- Feature 2 --}}
                <div class="flex items-start gap-4 rounded-2xl border border-emerald-500/20 bg-white/95 p-4 shadow-md backdrop-blur-md transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold shadow-md">
                        <flux:icon name="building-library" class="size-5" />
                    </div>
                    <div>
                        <h4 class="font-bold text-emerald-950 text-sm">{{ __('Diniyah Takmiliyah') }}</h4>
                        <p class="mt-1 text-xs text-zinc-600 leading-relaxed">{{ __('Pendalaman Fiqih, Akidah, Hadits, & Bahasa Arab terstruktur.') }}</p>
                    </div>
                </div>

                {{-- Feature 3 --}}
                <div class="flex items-start gap-4 rounded-2xl border border-emerald-500/20 bg-white/95 p-4 shadow-md backdrop-blur-md transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold shadow-md">
                        <flux:icon name="device-phone-mobile" class="size-5" />
                    </div>
                    <div>
                        <h4 class="font-bold text-emerald-950 text-sm">{{ __('Presensi Digital RFID') }}</h4>
                        <p class="mt-1 text-xs text-zinc-600 leading-relaxed">{{ __('Laporan kehadiran harian santri tersambung langsung ke HP Wali.') }}</p>
                    </div>
                </div>

                {{-- Feature 4 --}}
                <div class="flex items-start gap-4 rounded-2xl border border-emerald-500/20 bg-white/95 p-4 shadow-md backdrop-blur-md transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold shadow-md">
                        <flux:icon name="check-badge" class="size-5" />
                    </div>
                    <div>
                        <h4 class="font-bold text-emerald-950 text-sm">{{ __('Ijazah Syahadah') }}</h4>
                        <p class="mt-1 text-xs text-zinc-600 leading-relaxed">{{ __('Sertifikasi ijazah munaqosyah resmi dari Tilawati Pusat Surabaya.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Why Choose Us / Mengapa Memilih Kami (2-Column Dense Split Section - eCademy Style) --}}
    <section class="w-full bg-[#edf7f4] py-20 border-b border-emerald-500/20" aria-labelledby="why-us-heading">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-center">
                {{-- Left Image Composition & Experience Badge (5 cols) --}}
                <div class="lg:col-span-5 relative" data-editable-image="why_us_image" data-image-label="Ganti Foto Keunggulan">
                    <div class="relative overflow-hidden rounded-3xl border-2 border-emerald-500/30 shadow-2xl">
                        <img
                            src="{{ $whyUsImage }}"
                            alt="Kegiatan Pembelajaran Santri"
                            class="w-full h-[420px] object-cover"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-[#06382b]/80 via-transparent to-transparent"></div>
                    </div>

                    {{-- Overlapping Floating Experience Badge --}}
                    <div class="absolute -bottom-6 -right-4 rounded-2xl border border-amber-400/40 bg-gradient-to-br from-[#06382b] to-[#094a38] p-5 text-white shadow-2xl hidden sm:block">
                        <div class="flex items-center gap-3">
                            <div class="flex size-12 items-center justify-center rounded-xl bg-amber-400 text-amber-950 font-black text-xl shadow-md">
                                15+
                            </div>
                            <div>
                                <h4 class="font-extrabold text-white text-sm">{{ __('Tahun Berdedikasi') }}</h4>
                                <p class="text-[11px] text-emerald-200/80">{{ __('Mendidik Generasi Qurani') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Overlapping Floating Stat Badge --}}
                    <div class="absolute -top-4 -left-4 rounded-2xl border border-emerald-500/30 bg-white/95 p-4 shadow-xl backdrop-blur-md hidden sm:block">
                        <div class="flex items-center gap-2.5">
                            <div class="flex size-9 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-700">
                                <flux:icon name="sparkles" class="size-5" />
                            </div>
                            <div>
                                <span class="font-extrabold text-emerald-950 text-xs block">{{ __('98% Kelulusan') }}</span>
                                <span class="text-[10px] text-zinc-500 block">{{ __('Munaqosyah Tilawati') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Rich Content Column (7 cols) --}}
                <div class="lg:col-span-7 space-y-6">
                    <div>
                        <span data-editable-field="why_us_badge" class="inline-flex items-center gap-1.5 rounded-full border border-emerald-600/30 bg-emerald-600/10 px-3.5 py-1 text-xs font-bold text-emerald-800 mb-3">
                            ✦ {{ $whyUsBadge }}
                        </span>
                        <flux:heading id="why-us-heading" size="xl" class="text-3xl! font-bold text-emerald-950 sm:text-4xl!" data-editable-field="why_us_title">
                            {{ $whyUsTitle }}
                        </flux:heading>
                        <p class="mt-3 text-sm text-zinc-600 leading-relaxed" data-editable-field="why_us_subtitle">
                            {{ $whyUsSubtitle }}
                        </p>
                    </div>

                    {{-- 4 Benefit Feature Grid Cards --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 pt-2">
                        <div class="rounded-2xl border border-emerald-500/20 bg-white/90 p-4 shadow-sm backdrop-blur-sm">
                            <div class="flex items-center gap-3">
                                <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                    <flux:icon name="academic-cap" class="size-5" />
                                </div>
                                <h4 class="font-bold text-emerald-950 text-xs">{{ __('Pengajar Bersyahadah') }}</h4>
                            </div>
                            <p class="mt-2 text-[11px] text-zinc-500 leading-relaxed">{{ __('Seluruh Ustadz/ah telah lulus munaqosyah & mengantongi sertifikat resmi.') }}</p>
                        </div>

                        <div class="rounded-2xl border border-emerald-500/20 bg-white/90 p-4 shadow-sm backdrop-blur-sm">
                            <div class="flex items-center gap-3">
                                <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                    <flux:icon name="chart-bar" class="size-5" />
                                </div>
                                <h4 class="font-bold text-emerald-950 text-xs">{{ __('Siakad Digital Real-Time') }}</h4>
                            </div>
                            <p class="mt-2 text-[11px] text-zinc-500 leading-relaxed">{{ __('Rekapitulasi nilai, kehadiran RFID, dan tagihan SPP tercatat rapi.') }}</p>
                        </div>

                        <div class="rounded-2xl border border-emerald-500/20 bg-white/90 p-4 shadow-sm backdrop-blur-sm">
                            <div class="flex items-center gap-3">
                                <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                    <flux:icon name="trophy" class="size-5" />
                                </div>
                                <h4 class="font-bold text-emerald-950 text-xs">{{ __('Prestasi & Munaqosyah') }}</h4>
                            </div>
                            <p class="mt-2 text-[11px] text-zinc-500 leading-relaxed">{{ __('Ujian munaqosyah terbuka dan siap berprestasi di kejuaraan Musabaqah.') }}</p>
                        </div>

                        <div class="rounded-2xl border border-emerald-500/20 bg-white/90 p-4 shadow-sm backdrop-blur-sm">
                            <div class="flex items-center gap-3">
                                <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                    <flux:icon name="heart" class="size-5" />
                                </div>
                                <h4 class="font-bold text-emerald-950 text-xs">{{ __('Pembiasaan Karakter') }}</h4>
                            </div>
                            <p class="mt-2 text-[11px] text-zinc-500 leading-relaxed">{{ __('Penanaman adab harian, salat berjamaah, dan kepribadian Islami.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. Program Unggulan Section (3-Column Interactive Cards with Badges & Metadata - eCademy Style) --}}
    <section id="program" class="w-full bg-[#dcf0ea] py-20 border-b border-emerald-500/20" aria-labelledby="program-heading">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div class="max-w-2xl">
                    <span data-editable-field="programs_badge" class="inline-flex items-center gap-1.5 rounded-full border border-emerald-600/30 bg-emerald-600/10 px-3.5 py-1 text-xs font-bold text-emerald-800 mb-3">
                        ✦ {{ $programsBadge }}
                    </span>
                    <flux:heading id="program-heading" size="xl" class="text-3xl! font-bold text-emerald-950 sm:text-4xl!" data-editable-field="programs_title">{{ $programsTitle }}</flux:heading>
                    <p class="mt-2 text-sm text-emerald-900/80 leading-relaxed" data-editable-field="programs_subtitle">{{ $programsSubtitle }}</p>
                </div>
                <a href="{{ route('program') }}" wire:navigate class="inline-flex items-center gap-2 rounded-full border border-emerald-700/40 bg-emerald-700 px-5 py-2.5 text-xs font-bold text-white shadow-md transition hover:bg-emerald-800 shrink-0">
                    <span>{{ __('Lihat Kurikulum Lengkap') }}</span>
                    <flux:icon name="arrow-right" class="size-4" />
                </a>
            </div>

            <div data-db-locked="true" class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 p-4 rounded-3xl">
                @forelse ($this->lembagas as $lembaga)
                    <div wire:key="lembaga-card-{{ $lembaga->id }}" class="group relative flex flex-col justify-between overflow-hidden rounded-3xl border border-emerald-500/20 bg-white/95 shadow-md backdrop-blur-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                        <div class="relative h-48 overflow-hidden bg-gradient-to-br from-[#06382b] to-[#0d5c46]">
                            <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center">
                                <flux:icon name="building-office-2" class="size-12 text-emerald-400 opacity-80 group-hover:scale-110 transition duration-300" />
                                <span class="mt-2 text-xs font-bold uppercase tracking-wider text-emerald-200">{{ $lembaga->jenjang }}</span>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-emerald-950/80 via-transparent to-transparent"></div>
                            <span class="absolute top-4 right-4 rounded-full bg-emerald-700 border border-emerald-400/40 px-3 py-1 text-xs font-extrabold text-white shadow-md">
                                {{ strtoupper($lembaga->jenjang) }}
                            </span>
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3 text-xs text-zinc-500 mb-2">
                                    <span class="inline-flex items-center gap-1"><flux:icon name="user-group" class="size-3.5 text-emerald-600" /> {{ $lembaga->santris_count }} {{ __('Santri Aktif') }}</span>
                                    <span>&bull;</span>
                                    <span class="inline-flex items-center gap-1"><flux:icon name="building-library" class="size-3.5 text-emerald-600" /> {{ $lembaga->kelas_count }} {{ __('Rombel') }}</span>
                                </div>

                                <h3 class="text-xl font-bold text-emerald-950 group-hover:text-emerald-700 transition">{{ $lembaga->nama }}</h3>
                                <p class="mt-2 text-xs leading-relaxed text-zinc-600">
                                    {{ $lembaga->alamat ?? __('Pendidikan keagamaan berstruktur terintegrasi dengan tata kelola digital siakad.') }}
                                </p>

                                <ul class="mt-4 space-y-2 text-xs text-zinc-600 border-t border-emerald-100 pt-4">
                                    <li class="flex items-center gap-2">
                                        <flux:icon name="user-circle" class="size-4 text-emerald-600" />
                                        <span>{{ __('Kepala Lembaga: ') }} <strong>{{ $lembaga->kepala_lembaga ?? '-' }}</strong></span>
                                    </li>
                                    @if ($lembaga->nsm)
                                        <li class="flex items-center gap-2">
                                            <flux:icon name="identification" class="size-4 text-emerald-600" />
                                            <span>{{ __('NSM / NPSN: ') }} <strong>{{ $lembaga->nsm }}</strong></span>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                            <div class="mt-6 border-t border-emerald-100 pt-4 flex items-center justify-between">
                                <span class="text-xs font-bold text-emerald-800">{{ __('Unit Resmi') }}</span>
                                <a href="{{ route('program') }}" wire:navigate class="inline-flex items-center gap-1 text-xs font-extrabold text-emerald-700 hover:text-emerald-900">
                                    <span>{{ __('Detail Program') }}</span>
                                    <flux:icon name="arrow-right" class="size-3.5" />
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-emerald-400 p-8 text-center text-emerald-900">
                        {{ __('Belum ada unit lembaga pendidikan yang ditambahkan.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- 5. Statistics Counter Banner (Full-Width High Contrast Dark Emerald Theme #06382b - eCademy Style) --}}
    <section class="w-full bg-gradient-to-r from-[#021d16] via-[#06382b] to-[#094a38] text-white py-16 border-y border-emerald-600/30" aria-labelledby="stats-heading">
        <div class="mx-auto max-w-7xl px-6">
            <h2 id="stats-heading" class="sr-only">{{ $statsTitle }}</h2>

            <div data-db-locked="true" class="grid grid-cols-2 gap-8 lg:grid-cols-4 p-4 rounded-3xl">
                {{-- Stat 1 --}}
                <div class="flex flex-col items-center text-center p-4 rounded-2xl bg-white/5 border border-emerald-500/20 backdrop-blur-md">
                    <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/20 text-emerald-300 mb-3">
                        <flux:icon name="user-group" class="size-6" />
                    </div>
                    <span class="text-4xl font-extrabold text-emerald-300 sm:text-5xl">{{ $this->santriAktifCount }}</span>
                    <span class="mt-2 text-xs font-bold uppercase tracking-wider text-emerald-100/90">{{ __('Santri Aktif') }}</span>
                </div>

                {{-- Stat 2 --}}
                <div class="flex flex-col items-center text-center p-4 rounded-2xl bg-white/5 border border-emerald-500/20 backdrop-blur-md">
                    <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/20 text-emerald-300 mb-3">
                        <flux:icon name="academic-cap" class="size-6" />
                    </div>
                    <span class="text-4xl font-extrabold text-emerald-300 sm:text-5xl">{{ $this->guruAktifCount }}</span>
                    <span class="mt-2 text-xs font-bold uppercase tracking-wider text-emerald-100/90">{{ __('Ustadz & Ustadzah') }}</span>
                </div>

                {{-- Stat 3 --}}
                <div class="flex flex-col items-center text-center p-4 rounded-2xl bg-white/5 border border-emerald-500/20 backdrop-blur-md">
                    <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/20 text-emerald-300 mb-3">
                        <flux:icon name="building-library" class="size-6" />
                    </div>
                    <span class="text-4xl font-extrabold text-emerald-300 sm:text-5xl">{{ $this->kelasCount }}</span>
                    <span class="mt-2 text-xs font-bold uppercase tracking-wider text-emerald-100/90">{{ __('Rombongan Belajar') }}</span>
                </div>

                {{-- Stat 4 --}}
                <div class="flex flex-col items-center text-center p-4 rounded-2xl bg-white/5 border border-emerald-500/20 backdrop-blur-md">
                    <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/20 text-amber-300 mb-3">
                        <flux:icon name="building-office-2" class="size-6" />
                    </div>
                    <span class="text-4xl font-extrabold text-amber-300 sm:text-5xl">{{ $this->totalLembagaCount }}</span>
                    <span class="mt-2 text-xs font-bold uppercase tracking-wider text-emerald-100/90">{{ __('Unit Lembaga') }}</span>
                </div>
            </div>
        </div>
    </section>


    {{-- 6. Agenda & Kegiatan Mendatang (Upcoming Events - eCademy Style) --}}
    <section class="w-full bg-[#edf7f4] py-20 border-b border-emerald-500/20" aria-labelledby="events-heading">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 max-w-2xl" data-editable="true">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-600/30 bg-emerald-600/10 px-3.5 py-1 text-xs font-bold text-emerald-800 mb-3">
                    ✦ {{ __('Agenda Lembaga') }}
                </span>
                <flux:heading id="events-heading" size="xl" class="text-3xl! font-bold text-emerald-950 sm:text-4xl!">{{ __('Kegiatan & Event Mendatang') }}</flux:heading>
                <p class="mt-2 text-sm text-zinc-600 leading-relaxed">{{ __('Momentum penting, ujian munaqosyah, dan agenda keagamaan santri.') }}</p>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                {{-- Event 1 --}}
                <div class="flex flex-col justify-between rounded-3xl border border-emerald-500/20 bg-white/95 p-6 shadow-md backdrop-blur-sm transition-all duration-300 hover:shadow-2xl">
                    <div>
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-800 text-white p-3 min-w-[64px] text-center shadow-md">
                                <span class="text-xl font-extrabold leading-none">24</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider mt-1">AGUS</span>
                            </div>
                            <div>
                                <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-bold text-emerald-800">{{ __('Ujian Munaqosyah') }}</span>
                                <h4 class="font-bold text-emerald-950 text-base mt-1">{{ __('Munaqosyah & Imtihan Tilawati') }}</h4>
                            </div>
                        </div>
                        <p class="mt-4 text-xs text-zinc-600 leading-relaxed">
                            {{ __('Ujian terbuka santri calon khatam Al-Qur\'an bersama Tim Penguji resmi dari Nurul Falah Surabaya.') }}
                        </p>
                    </div>

                    <div class="mt-6 border-t border-emerald-100 pt-4 flex items-center justify-between text-xs text-zinc-500">
                        <span class="flex items-center gap-1"><flux:icon name="map-pin" class="size-3.5 text-amber-500" /> {{ __('Aula Utama Lembaga') }}</span>
                        <span class="font-bold text-emerald-700">{{ __('08.00 WIB') }}</span>
                    </div>
                </div>

                {{-- Event 2 --}}
                <div class="flex flex-col justify-between rounded-3xl border border-emerald-500/20 bg-white/95 p-6 shadow-md backdrop-blur-sm transition-all duration-300 hover:shadow-2xl">
                    <div>
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-800 text-white p-3 min-w-[64px] text-center shadow-md">
                                <span class="text-xl font-extrabold leading-none">12</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider mt-1">SEPT</span>
                            </div>
                            <div>
                                <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-[10px] font-bold text-amber-900">{{ __('Pentas Seni') }}</span>
                                <h4 class="font-bold text-emerald-950 text-base mt-1">{{ __('Peringatan Hari Besar & Pentas Santri') }}</h4>
                            </div>
                        </div>
                        <p class="mt-4 text-xs text-zinc-600 leading-relaxed">
                            {{ __('Ajang apresiasi seni Islami, hafalan Al-Qur\'an, dan kebolehan pidato da\'i cilik bagi seluruh santri.') }}
                        </p>
                    </div>

                    <div class="mt-6 border-t border-emerald-100 pt-4 flex items-center justify-between text-xs text-zinc-500">
                        <span class="flex items-center gap-1"><flux:icon name="map-pin" class="size-3.5 text-amber-500" /> {{ __('Halaman Panggung') }}</span>
                        <span class="font-bold text-emerald-700">{{ __('13.00 WIB') }}</span>
                    </div>
                </div>

                {{-- Event 3 --}}
                <div class="flex flex-col justify-between rounded-3xl border border-emerald-500/20 bg-white/95 p-6 shadow-md backdrop-blur-sm transition-all duration-300 hover:shadow-2xl sm:col-span-2 lg:col-span-1">
                    <div>
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-800 text-white p-3 min-w-[64px] text-center shadow-md">
                                <span class="text-xl font-extrabold leading-none">05</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider mt-1">OKT</span>
                            </div>
                            <div>
                                <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-bold text-emerald-800">{{ __('Rihlah Ilmiah') }}</span>
                                <h4 class="font-bold text-emerald-950 text-base mt-1">{{ __('Kunjungan Edukasi & Ziarah Santri') }}</h4>
                            </div>
                        </div>
                        <p class="mt-4 text-xs text-zinc-600 leading-relaxed">
                            {{ __('Kegiatan rekreasi edukatif untuk mengenalkan sejarah para ulama dan mempererat ukhuwah antar santri.') }}
                        </p>
                    </div>

                    <div class="mt-6 border-t border-emerald-100 pt-4 flex items-center justify-between text-xs text-zinc-500">
                        <span class="flex items-center gap-1"><flux:icon name="map-pin" class="size-3.5 text-amber-500" /> {{ __('Destinasi Edukasi') }}</span>
                        <span class="font-bold text-emerald-700">{{ __('09.00 WIB') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 7. Galeri Section (Full-Width Soft Sage Theme #dcf0ea) --}}
    <section id="galeri" class="w-full bg-[#dcf0ea] py-20 border-b border-emerald-500/20" aria-labelledby="galeri-heading">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-10 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span data-editable-field="gallery_badge" class="inline-flex items-center gap-1.5 rounded-full border border-emerald-600/30 bg-emerald-600/10 px-3.5 py-1 text-xs font-bold text-emerald-800 mb-3">
                        ✦ {{ $galleryBadge }}
                    </span>
                    <flux:heading id="galeri-heading" size="xl" class="text-3xl! font-bold text-emerald-950 sm:text-4xl!" data-editable-field="gallery_title">{{ $galleryTitle }}</flux:heading>
                    <p class="mt-2 text-sm text-[#06382b] font-medium leading-relaxed" data-editable-field="gallery_subtitle">{{ $gallerySubtitle }}</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach ($this->galleryTypes as $type)
                        <button
                            type="button"
                            wire:click="setGalleryType('{{ $type->value }}')"
                            class="rounded-full px-4 py-1.5 text-xs font-semibold transition {{ $activeGalleryType === $type->value ? 'bg-emerald-700 text-white shadow-md font-bold' : 'bg-white/80 text-emerald-900 hover:bg-white border border-emerald-500/20' }}"
                        >
                            {{ ucfirst($type->value) }}
                        </button>
                    @endforeach
                </div>
            </div>

            @if ($this->galleries->isEmpty())
                <flux:callout icon="photo" heading="{{ __('Belum ada galeri') }}" text="{{ __('Dokumentasi untuk kategori ini akan segera diperbarui.') }}" />
            @else
                <div data-db-locked="true" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 p-4 rounded-3xl">
                    @foreach ($this->galleries as $gallery)
                        <div class="group overflow-hidden rounded-3xl border border-emerald-500/20 bg-white/95 shadow-md backdrop-blur-sm transition-all duration-300 hover:shadow-2xl" wire:key="galeri-{{ $gallery->id }}">
                            <div class="relative aspect-video overflow-hidden bg-emerald-900">
                                <img src="{{ $gallery->image }}" alt="{{ $gallery->title }}" class="size-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" width="400" height="225">
                                <span class="absolute top-3 right-3 rounded-full bg-emerald-950/80 border border-emerald-400/30 px-3 py-1 text-[10px] font-bold text-emerald-200 backdrop-blur-md">
                                    {{ strtoupper($gallery->type->value ?? $gallery->type) }}
                                </span>
                            </div>
                            <div class="p-5">
                                <h4 class="font-bold text-emerald-950 text-sm group-hover:text-emerald-700 transition">{{ $gallery->title }}</h4>
                                @if ($gallery->description)
                                    <p class="mt-2 text-xs leading-relaxed text-zinc-600">{{ \Illuminate\Support\Str::limit($gallery->description, 90) }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-10 text-center">
                <a href="{{ route('galeri') }}" wire:navigate class="inline-flex items-center gap-2 rounded-full border border-emerald-600/30 bg-emerald-600/10 px-6 py-2.5 text-xs font-bold text-emerald-800 transition hover:bg-emerald-600/20">
                    <span>{{ __('Lihat Seluruh Galeri Dokumentasi') }}</span>
                    <flux:icon name="arrow-right" class="size-4" />
                </a>
            </div>
        </div>
    </section>

    {{-- 8. Pengajar Section (Full-Width Soft Jade Mist Theme #edf7f4) --}}
    @if ($this->pengajar->isNotEmpty())
        <section class="w-full bg-[#edf7f4] py-20 border-b border-emerald-500/20" aria-labelledby="pengajar-heading">
            <div class="mx-auto max-w-7xl px-6">
                <div class="mb-12 max-w-xl">
                    <span data-editable-field="teachers_badge" class="inline-flex items-center gap-1.5 rounded-full border border-emerald-600/30 bg-emerald-600/10 px-3.5 py-1 text-xs font-bold text-emerald-800 mb-3">
                        ✦ {{ $teachersBadge }}
                    </span>
                    <flux:heading id="pengajar-heading" size="xl" class="text-3xl! font-bold text-emerald-950 sm:text-4xl!" data-editable-field="teachers_title">{{ $teachersTitle }}</flux:heading>
                    <p class="mt-2 text-sm text-emerald-900/80" data-editable-field="teachers_subtitle">{{ $teachersSubtitle }}</p>
                </div>

                <div data-db-locked="true" class="grid grid-cols-2 gap-6 sm:grid-cols-4 p-4 rounded-3xl">
                    @foreach ($this->pengajar as $guru)
                        <div class="flex flex-col items-center rounded-3xl border border-emerald-500/20 bg-white/95 p-6 text-center shadow-md backdrop-blur-sm transition-all duration-300 hover:shadow-2xl hover:-translate-y-1" wire:key="guru-{{ $guru->id }}">
                            <flux:avatar size="xl" :src="$guru->foto" :name="$guru->user->name" class="ring-4 ring-emerald-500/30" />
                            <h4 class="mt-4 font-bold text-emerald-950 text-sm">{{ $guru->user->name }}</h4>
                            <p class="text-xs text-emerald-800/80 font-medium">{{ __('Pengajar Bersyahadah') }}</p>

                            @if($guru->whatsapp)
                                <a
                                    href="https://wa.me/{{ \App\Rules\IndonesianPhoneNumber::normalize($guru->whatsapp) }}?text={{ urlencode('Assalamu\'alaikum Ust. ' . $guru->user->name) }}"
                                    target="_blank"
                                    class="mt-4 inline-flex items-center gap-1.5 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3 py-1 text-xs text-emerald-800 hover:bg-emerald-600 hover:text-white font-bold transition"
                                >
                                    <flux:icon name="chat-bubble-left-right" class="size-3.5" />
                                    <span>{{ __('Hubungi') }}</span>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 9. Testimonial Section (Full-Width Soft Sage Theme #dcf0ea) --}}
    <section class="w-full bg-[#dcf0ea] py-20 border-b border-emerald-500/20" aria-labelledby="testimonial-heading">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 text-center">
                <span data-editable-field="testimonials_badge" class="inline-flex items-center gap-1.5 rounded-full border border-emerald-600/30 bg-emerald-600/10 px-3.5 py-1 text-xs font-bold text-emerald-800 mb-3">
                    ✦ {{ $testimonialsBadge }}
                </span>
                <flux:heading id="testimonial-heading" size="xl" class="text-3xl! font-bold text-emerald-950 sm:text-4xl!" data-editable-field="testimonials_title">{{ $testimonialsTitle }}</flux:heading>
                <p class="mt-2 text-sm text-emerald-900/80" data-editable-field="testimonials_subtitle">{{ $testimonialsSubtitle }}</p>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                {{-- Card 1 --}}
                <div class="flex flex-col justify-between rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm transition-all hover:shadow-xl">
                    <div>
                        <div class="flex gap-1 text-amber-400 text-base">★★★★★</div>
                        <p class="mt-4 italic text-zinc-600 text-xs leading-relaxed">
                            &ldquo;{{ __('Lembaga ini benar-benar mengubah cara anak saya membaca Al-Qur\'an. Dalam 6 bulan, bacaannya sudah jauh lebih fasih, tajwidnya rapi, dan presensi via WA sangat memudahkan.') }}&rdquo;
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-3 border-t border-emerald-100 pt-4">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-emerald-700 text-white font-bold text-xs shadow-md">FH</div>
                        <div>
                            <h4 class="font-bold text-emerald-950 text-xs">{{ __('Ibu Fatimah H.') }}</h4>
                            <p class="text-[11px] text-zinc-500">{{ __('Wali Murid Santri Kelas 3A') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="flex flex-col justify-between rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm transition-all hover:shadow-xl">
                    <div>
                        <div class="flex gap-1 text-amber-400 text-base">★★★★★</div>
                        <p class="mt-4 italic text-zinc-600 text-xs leading-relaxed">
                            &ldquo;{{ __('Ustadz-ustadznya sangat sabar dan penuh perhatian. Anak saya yang awalnya pemalu sekarang aktif dan hafal juz amma dengan baik.') }}&rdquo;
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-3 border-t border-emerald-100 pt-4">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-emerald-700 text-white font-bold text-xs shadow-md">AR</div>
                        <div>
                            <h4 class="font-bold text-emerald-950 text-xs">{{ __('Pak Ahmad R.') }}</h4>
                            <p class="text-[11px] text-zinc-500">{{ __('Wali Murid Santri Kelas 2B') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div class="flex flex-col justify-between rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm transition-all hover:shadow-xl">
                    <div>
                        <div class="flex gap-1 text-amber-400 text-base">★★★★★</div>
                        <p class="mt-4 italic text-zinc-600 text-xs leading-relaxed">
                            &ldquo;{{ __('Saya khatam Al-Qur\'an di sini dan mendapatkan ijazah resmi dari pusat Tilawati Nurul Falah. Pengalamannya sangat berkesan dan ilmunya bermanfaat.') }}&rdquo;
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-3 border-t border-emerald-100 pt-4">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-emerald-700 text-white font-bold text-xs shadow-md">MS</div>
                        <div>
                            <h4 class="font-bold text-emerald-950 text-xs">{{ __('Muhammad S.') }}</h4>
                            <p class="text-[11px] text-zinc-500">{{ __('Alumni Munaqosyah Tilawati') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 10. Pertanyaan Umum FAQ Section (Full-Width Dark Emerald Theme #094a38) --}}
    <section class="w-full bg-[#094a38] text-white py-20 border-t border-emerald-600/20" aria-labelledby="faq-heading">
        <div class="mx-auto max-w-4xl px-6">
            <div class="mb-12 text-center">
                <span data-editable-field="faq_badge" class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-300 mb-3">
                    ✦ {{ $faqBadge }}
                </span>
                <flux:heading id="faq-heading" size="xl" class="text-3xl! font-bold text-white sm:text-4xl!" data-editable-field="faq_title">{{ $faqTitle }}</flux:heading>
                <p class="mt-2 text-sm text-emerald-100/90" data-editable-field="faq_subtitle">{{ $faqSubtitle }}</p>
            </div>

            <div class="flex flex-col gap-4">
                <details class="group rounded-2xl border border-emerald-500/30 bg-[#06382b]/90 p-5 shadow-md transition-all [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex cursor-pointer items-center justify-between font-semibold text-white text-sm">
                        <span>{{ __('Bagaimana cara mendaftarkan santri baru?') }}</span>
                        <span class="ml-4 shrink-0 transition duration-300 group-open:-rotate-180 text-emerald-300">
                            <flux:icon name="chevron-down" class="size-5" />
                        </span>
                    </summary>
                    <p class="mt-4 text-xs leading-relaxed text-emerald-100/80">
                        {{ __('Pendaftaran santri baru dapat dilakukan secara online melalui tombol "Daftar Santri Baru" di website ini, atau datang langsung ke sekretariat lembaga kami.') }}
                    </p>
                </details>

                <details class="group rounded-2xl border border-emerald-500/30 bg-[#06382b]/90 p-5 shadow-md transition-all [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex cursor-pointer items-center justify-between font-semibold text-white text-sm">
                        <span>{{ __('Apa metode membaca Al-Qur\'an yang digunakan?') }}</span>
                        <span class="ml-4 shrink-0 transition duration-300 group-open:-rotate-180 text-emerald-300">
                            <flux:icon name="chevron-down" class="size-5" />
                        </span>
                    </summary>
                    <p class="mt-4 text-xs leading-relaxed text-emerald-100/80">
                        {{ __('Kami menggunakan Metode Tilawati yang terkenal dengan pendekatan belajar Al-Qur\'an yang mudah dan menyenangkan, serta dipungkas ijazah munaqosyah resmi dari Nurul Falah Surabaya.') }}
                    </p>
                </details>

                <details class="group rounded-2xl border border-emerald-500/30 bg-[#06382b]/90 p-5 shadow-md transition-all [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex cursor-pointer items-center justify-between font-semibold text-white text-sm">
                        <span>{{ __('Apakah wali murid bisa memantau perkembangan santri?') }}</span>
                        <span class="ml-4 shrink-0 transition duration-300 group-open:-rotate-180 text-emerald-300">
                            <flux:icon name="chevron-down" class="size-5" />
                        </span>
                    </summary>
                    <p class="mt-4 text-xs leading-relaxed text-emerald-100/80">
                        {{ __('Ya, sistem informasi akademik secara digital mencatat presensi RFID harian, nilai semester, dan pembayaran SPP yang terkoneksi langsung via WhatsApp.') }}
                    </p>
                </details>
            </div>
        </div>
    </section>

    {{-- 10.5. Newsletter Subscription Section --}}
    <section class="w-full bg-[#06382b] py-16 text-white border-t border-emerald-500/20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="flex flex-col items-center justify-between gap-8 rounded-3xl bg-emerald-950/60 border border-emerald-500/30 p-8 md:p-12 lg:flex-row shadow-xl">
                <div class="space-y-2 text-center lg:text-left max-w-xl">
                    <div class="inline-flex items-center gap-2 rounded-full bg-emerald-500/20 px-3.5 py-1 text-xs font-semibold text-emerald-300 border border-emerald-400/30">
                        <flux:icon name="bell-alert" class="size-4 text-emerald-400" />
                        <span>{{ __('Berlangganan Newsletter') }}</span>
                    </div>
                    <flux:heading size="xl" class="text-2xl! font-bold text-white sm:text-3xl!">{{ __('Dapatkan Informasi & Pengumuman Terbaru') }}</flux:heading>
                    <p class="text-xs text-emerald-100/80 leading-relaxed">{{ __('Daftarkan email Anda untuk berlangganan pengumuman kegiatan santri, agenda pendaftaran baru, serta update akademik resmi.') }}</p>
                </div>
                <div class="w-full lg:max-w-md">
                    <livewire:public.newsletter-form />
                </div>
            </div>
        </div>
    </section>

    {{-- 11. CTA Banner Section (Full-Width Edge to Edge Soft Jade Mist #edf7f4) --}}
    <section class="w-full bg-[#edf7f4] py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="relative overflow-hidden flex flex-col items-center gap-6 rounded-3xl bg-gradient-to-r from-[#06382b] via-[#094a38] to-[#0d5c46] border-2 border-emerald-400/40 px-8 py-14 text-center text-white shadow-2xl sm:flex-row sm:justify-between sm:text-left">
                <div class="relative z-10 max-w-xl space-y-2">
                    <flux:heading size="xl" class="text-2xl! font-bold text-white sm:text-3xl!" data-editable-field="cta_title">{{ $ctaTitle }}</flux:heading>
                    <p class="text-xs text-emerald-100/90 leading-relaxed" data-editable-field="cta_subtitle">{{ $ctaSubtitle }}</p>
                </div>
                <div class="relative z-10 flex shrink-0 flex-wrap justify-center gap-3">
                    <flux:button variant="primary" class="bg-emerald-500! text-emerald-950! hover:bg-emerald-400! font-extrabold shadow-xl border border-emerald-300 px-6 py-3" :href="route('santri.register.form')" wire:navigate data-editable-field="cta_button_text">
                        <flux:icon name="user-plus" class="size-4 me-1.5" />
                        {{ $ctaButtonText }}
                    </flux:button>
                    <flux:button variant="ghost" class="text-white! hover:bg-white/10! border border-emerald-500/30 px-5 py-3" :href="route('contact.show')" wire:navigate>
                        <flux:icon name="chat-bubble-left-right" class="size-4 me-1.5 text-emerald-200" />
                        {{ __('Hubungi Kami') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </section>

    {{-- Hero Slider Script Handler --}}
    <script>
        function heroSliderComponent(slidesData) {
            return {
                activeSlide: 0,
                slides: slidesData || [],
                init() {
                    if (!this.slides.length) return;
                    setInterval(() => {
                        this.next();
                    }, 5000);
                },
                next() {
                    if (!this.slides.length) return;
                    this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                },
                prev() {
                    if (!this.slides.length) return;
                    this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                }
            };
        }
    </script>
</div>
