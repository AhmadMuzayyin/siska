@php
    $setting = $this->setting;
    $lembagaName = $setting?->lembaga ?? config('app.name');
    $lembagas = $this->lembagas;
    $programs = $this->programs;
    $pengajar = $this->pengajar;
    $galleries = $this->galleries;
    $santriCount = $this->santriAktifCount;
    $guruCount = $this->guruAktifCount;
    $kelasCount = $this->kelasCount;
    $totalLembaga = $this->totalLembagaCount;

    $heroStudentLeft = $setting?->getLandingContent('hero_student_image_left', 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=600&auto=format&fit=crop&q=80', 'pixigon');
    $heroStudentRight = $setting?->getLandingContent('hero_student_image_right', 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=600&auto=format&fit=crop&q=80', 'pixigon');
    $aboutFacilityImage = $setting?->getLandingContent('about_facility_image', 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=800&auto=format&fit=crop&q=80', 'pixigon');

    $heroBadge = $setting?->getLandingContent('hero_badge', __('Pendidikan Al-Qur\'an & Karakter Islami'), 'pixigon');
    $heroTitle = $setting?->getLandingContent('hero_title', "Membentuk Generasi\nQur'ani & Beradab", 'pixigon');
    $heroSubtitle = $setting?->getLandingContent('hero_subtitle', $setting?->meta_deskripsi ?? __('Pendidikan Islam terpadu dengan metode Tilawati, tahfidzul Qur\'an, kajian kitab kuning, dan pembinaan akhlak mulia untuk masa depan generasi berkarakter.'), 'pixigon');
    $heroCtaText = $setting?->getLandingContent('hero_cta_text', __('Daftar Santri Baru'), 'pixigon');
    $heroCtaUrl = $setting?->getLandingContent('hero_cta_url', route('santri.register.form'), 'pixigon');

    $aboutTitle = $setting?->getLandingContent('about_title', __('Tentang Lembaga & Pendidikan Kami'), 'pixigon');
    $aboutSubtitle = $setting?->getLandingContent('about_subtitle', __('Lembaga kami mendampingi santri meraih kefasihan membaca Al-Qur\'an, kedalaman pemahaman agama, serta pembiasaan akhlakul karimah dengan metode yang mudah, menyenangkan, dan bersanad.'), 'pixigon');

    $programsTitle = $setting?->getLandingContent('programs_title', __('Pilihan Program & Kurikulum Pembelajaran'), 'pixigon');
    $programsSubtitle = $setting?->getLandingContent('programs_subtitle', __('Kurikulum berjenjang dan terintegrasi dirancang agar setiap santri dapat belajar sesuai tahapan usia dan kemampuan.'), 'pixigon');

    $teachersTitle = $setting?->getLandingContent('teachers_title', __('Dewan Asatidz & Ustadzah Pengajar'), 'pixigon');
    $teachersSubtitle = $setting?->getLandingContent('teachers_subtitle', __('Tenaga pendidik bersyahadah, berdedikasi tinggi, dan telaten dalam membimbing bacaan serta akhlak santri.'), 'pixigon');

    $galleryTitle = $setting?->getLandingContent('gallery_title', __('Dokumentasi Kegiatan Santri'), 'pixigon');
    $testimonialsTitle = $setting?->getLandingContent('testimonials_title', __('Apa Kata Wali Santri & Alumni?'), 'pixigon');
    $testimonialsSubtitle = $setting?->getLandingContent('testimonials_subtitle', __('Pengalaman nyata para wali santri dan alumni dalam proses belajar mengajar di lembaga kami.'), 'pixigon');

    $ctaTitle = $setting?->getLandingContent('cta_title', __('Daftarkan Putra-Putri Anda Menjadi Generasi Pecinta Al-Qur\'an!'), 'pixigon');
    $ctaSubtitle = $setting?->getLandingContent('cta_subtitle', __('Mari bersama-sama membimbing putra-putri kita menjadi generasi Qurani yang fasih membaca Al-Qur\'an, kokoh dalam aqidah, dan santun dalam budi pekerti.'), 'pixigon');
    $ctaButtonText = $setting?->getLandingContent('cta_button_text', __('Daftar Santri Baru Sekarang'), 'pixigon');

    $testimonials = [
        [
            'name' => 'Hj. Siti Aminah',
            'role' => 'Wali Santri Madrasah Diniyah',
            'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150&auto=format&fit=crop&q=80',
            'rating' => 5,
            'text' => 'Alhamdulillah, anak saya sekarang sangat lancar membaca Al-Qur\'an dengan lagu rost Tilawati dan hafalannya bertambah setiap pekannya.',
            'rotate' => 'md:rotate-[4deg]',
        ],
        [
            'name' => 'Ustadz Ahmad Fauzi',
            'role' => 'Wali Santri Tahfidz Qur\'an',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
            'rating' => 5,
            'text' => 'Pembelajaran sangat terstruktur dan para ustadz sangat sabar. Penanaman adab dan akhlakul karimah sangat terasa dalam keseharian anak.',
            'rotate' => 'md:-rotate-[3deg]',
        ],
        [
            'name' => 'Nurul Hidayati',
            'role' => 'Alumni Program Bahasa Arab & Kitab',
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
            'rating' => 5,
            'text' => 'Bekal nahwu shorof dan kajian kitab di madin ini sangat membantu saya saat melanjutkan studi ke jenjang pesantren tinggi.',
            'rotate' => 'md:rotate-[4deg]',
        ],
        [
            'name' => 'Drs. H. M. Syaifullah',
            'role' => 'Wali Santri TPQ Sore',
            'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80',
            'rating' => 5,
            'text' => 'Sistem absensi digital dan laporan nilai yang transparan memudahkan wali santri memantau perkembangan belajar anak secara real-time.',
            'rotate' => 'md:-rotate-[4deg]',
        ],
    ];

    $learningCategories = [
        [
            'title' => 'Metode Tilawati & Tartil',
            'desc' => 'Belajar membaca Al-Qur\'an dengan lagu rost yang praktis, tepat makhraj, dan berpedoman pada kaidah tajwid resmi.',
            'courses' => '6 Jilid + Al-Qur\'an',
            'icon' => 'book-open',
            'gradient' => 'from-emerald-400 to-teal-600',
        ],
        [
            'title' => 'Tahfidzul Qur\'an & Mutaba\'ah',
            'desc' => 'Program hafalan Al-Qur\'an intensif juz 30 dan surat pilihan dengan bimbingan hafizh bersanad dan buku mutaba\'ah harian.',
            'courses' => 'Juz Amma & Ziyadah',
            'icon' => 'sparkles',
            'gradient' => 'from-lime-500 to-emerald-600',
        ],
        [
            'title' => 'Madrasah Diniyah Takmiliyah',
            'desc' => 'Pendidikan formal diniyah mendalami Fiqih, Aqidah, Hadits, Tarikh, dan Bahasa Arab untuk bekal ibadah harian.',
            'courses' => 'Tingkat Awwaliyah & Wustha',
            'icon' => 'academic-cap',
            'gradient' => 'from-teal-400 to-emerald-600',
        ],
        [
            'title' => 'Bahasa Arab & Nahwu Dasar',
            'desc' => 'Pengenalan kaidah mufrodat, percakapan praktis, dan dasar nahwu shorof untuk memahami teks Al-Qur\'an dan hadits.',
            'courses' => 'Halaqah Lughah',
            'icon' => 'language',
            'gradient' => 'from-green-500 to-lime-600',
        ],
        [
            'title' => 'Munaqosyah & Sertifikasi',
            'desc' => 'Evaluasi kelulusan santri terstandarisasi Nurul Falah dengan penerbitan ijazah resmi dan syahadah bersanad.',
            'courses' => 'Sertifikat Resmi',
            'icon' => 'trophy',
            'gradient' => 'from-emerald-500 to-teal-700',
        ],
        [
            'title' => 'Pendidikan Karakter & Adab',
            'desc' => 'Pembiasaan akhlaqul karimah kepada orang tua, guru, dan sesama teman sesuai tuntunan Rasulullah SAW.',
            'courses' => 'Program Unggulan',
            'icon' => 'user-group',
            'gradient' => 'from-cyan-400 to-emerald-500',
        ],
    ];
@endphp

<div class="flex flex-col w-full overflow-hidden font-sans bg-white text-zinc-800 selection:bg-[#6bb82d] selection:text-white">

    {{-- 1. HERO SECTION (Pixigon Soft Green Light Theme with Islamic Portraits) --}}
    <section class="relative pt-12 pb-36 lg:pt-16 lg:pb-52 bg-[#f0f8ec] lg:[clip-path:ellipse(75%_100%_at_50%_0%)] overflow-hidden">
        
        {{-- Floating Accents Matching Pixigon --}}
        <div class="hidden lg:block absolute top-14 left-24 size-6 bg-[#7cb342] rotate-12 rounded-xs shadow-xs pointer-events-none"></div>

        <div class="hidden lg:block absolute top-20 right-36 pointer-events-none">
            <svg class="size-7 text-[#10b981] -rotate-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polygon points="12 2, 22 21, 2 21" />
            </svg>
        </div>

        <div class="hidden lg:block absolute bottom-36 right-48 pointer-events-none">
            <div class="size-8 border-2 border-[#38bdf8] rotate-12 rounded-xs"></div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 relative z-10">
            <div class="grid grid-cols-12 gap-6 items-center">
                
                {{-- Left Student Illustration --}}
                <div class="hidden lg:block lg:col-span-3">
                    <div class="relative flex justify-center -mt-10">
                        <div class="size-60 xl:size-72 rounded-full bg-[#d6eda6] flex items-center justify-center relative shadow-lg overflow-hidden border-4 border-white" data-editable-image="hero_student_image_left" data-image-label="Ganti Foto Santriwati Kiri">
                            <img 
                                src="{{ $heroStudentLeft }}" 
                                alt="Santriwati Mengaji Al-Qur'an" 
                                class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-500"
                            >
                        </div>
                    </div>
                </div>

                {{-- Center Content Column --}}
                <div class="col-span-12 lg:col-span-6 text-center">
                    <div class="relative">
                        {{-- Handwritten Style Pill Badge --}}
                        <div class="inline-block">
                            <span data-editable-field="hero_badge" class="inline-flex items-center gap-2 border border-zinc-700/30 bg-white/50 rounded-full px-4 py-1 text-xs font-semibold text-zinc-800 shadow-2xs mb-6">
                                <span>✍️</span>
                                <span>{{ $heroBadge }}</span>
                            </span>
                        </div>

                        {{-- Main Headline --}}
                        <h1 data-editable-field="hero_title" class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-[#2e5b18] leading-[1.15] tracking-tight mb-6 whitespace-pre-line">
                            {{ $heroTitle }}
                        </h1>

                        {{-- Subtitle --}}
                        <p data-editable-field="hero_subtitle" class="text-zinc-600 text-sm sm:text-base leading-relaxed max-w-xl mx-auto mb-8 font-normal">
                            {{ $heroSubtitle }}
                        </p>

                        {{-- Pixigon Single Signature Pill CTA Button --}}
                        <div class="inline-block">
                            <a 
                                href="{{ $heroCtaUrl }}" 
                                wire:navigate 
                                class="inline-flex items-center gap-3 rounded-full bg-[#6bb82d] hover:bg-[#5ca828] text-white font-bold text-base py-2.5 ps-8 pe-2.5 shadow-xl shadow-lime-600/25 transition-all duration-300 transform hover:-translate-y-0.5 group"
                            >
                                <span data-editable-field="hero_cta_text">{{ $heroCtaText }}</span>
                                <span class="size-10 rounded-full bg-[#4d8f1e] group-hover:bg-[#3d7a17] flex items-center justify-center text-white transition-all">
                                    <flux:icon name="arrow-up-right" class="size-5" />
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Right Student Illustration --}}
                <div class="hidden lg:block lg:col-span-3">
                    <div class="relative flex justify-center mt-12">
                        <div class="size-60 xl:size-72 rounded-[40%_60%_70%_30%/40%_50%_60%_55%] bg-[#82b834] flex items-center justify-center relative shadow-lg overflow-hidden border-4 border-white" data-editable-image="hero_student_image_right" data-image-label="Ganti Foto Santriwan Kanan">
                            <img 
                                src="{{ $heroStudentRight }}" 
                                alt="Santriwan Belajar Kitab & Qur'an" 
                                class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-500"
                            >
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- 2. FLOATING CIRCULAR STAT COUNTERS (Locked from DB) --}}
    <section class="relative -mt-20 lg:-mt-28 z-20 pb-20">
        <div class="container mx-auto px-4 sm:px-6">
            <div data-db-locked="true" class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto p-4 rounded-3xl">
                {{-- Circle 1: Santri Aktif --}}
                <div class="flex flex-col items-center justify-center size-44 sm:size-48 rounded-full bg-white shadow-xl shadow-zinc-200/70 p-6 text-center mx-auto transform transition-all duration-300 hover:-translate-y-2 group">
                    <h3 class="text-4xl font-extrabold text-[#2e5b18] mb-1 group-hover:scale-105 transition-transform">
                        {{ $santriCount > 0 ? $santriCount : '120' }}+
                    </h3>
                    <p class="text-zinc-600 text-xs sm:text-sm font-medium">
                        {{ __('Santri Aktif') }}
                    </p>
                </div>

                {{-- Circle 2: Ustadz & Pengajar --}}
                <div class="flex flex-col items-center justify-center size-44 sm:size-48 rounded-full bg-white shadow-xl shadow-zinc-200/70 p-6 text-center mx-auto transform transition-all duration-300 hover:-translate-y-2 group mt-6 md:mt-0">
                    <h3 class="text-4xl font-extrabold text-[#2e5b18] mb-1 group-hover:scale-105 transition-transform">
                        {{ $guruCount > 0 ? $guruCount : '15' }}+
                    </h3>
                    <p class="text-zinc-600 text-xs sm:text-sm font-medium">
                        {{ __('Asatidz Bersertifikat') }}
                    </p>
                </div>

                {{-- Circle 3: Rombel / Kelas --}}
                <div class="flex flex-col items-center justify-center size-44 sm:size-48 rounded-full bg-white shadow-xl shadow-zinc-200/70 p-6 text-center mx-auto transform transition-all duration-300 hover:-translate-y-2 group">
                    <h3 class="text-4xl font-extrabold text-[#2e5b18] mb-1 group-hover:scale-105 transition-transform">
                        {{ $kelasCount > 0 ? $kelasCount : '8' }}
                    </h3>
                    <p class="text-zinc-600 text-xs sm:text-sm font-medium">
                        {{ __('Rombel Halaqah') }}
                    </p>
                </div>

                {{-- Circle 4: Unit Lembaga --}}
                <div class="flex flex-col items-center justify-center size-44 sm:size-48 rounded-full bg-white shadow-xl shadow-zinc-200/70 p-6 text-center mx-auto transform transition-all duration-300 hover:-translate-y-2 group mt-6 md:mt-0">
                    <h3 class="text-4xl font-extrabold text-[#2e5b18] mb-1 group-hover:scale-105 transition-transform">
                        {{ $totalLembaga > 0 ? $totalLembaga : '3' }}
                    </h3>
                    <p class="text-zinc-600 text-xs sm:text-sm font-medium">
                        {{ __('Jenjang Lembaga') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. ABOUT OUR ACADEMY SECTION --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6">
            {{-- Header Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center mb-14">
                <div class="lg:col-span-5">
                    <h2 data-editable-field="about_title" class="text-3xl sm:text-4xl font-extrabold text-[#2e5b18] leading-tight">
                        {{ $aboutTitle }}
                    </h2>
                </div>
                <div class="lg:col-span-6 lg:col-end-13">
                    <p data-editable-field="about_subtitle" class="text-zinc-600 text-sm sm:text-base leading-relaxed">
                        {{ $aboutSubtitle }}
                    </p>
                </div>
            </div>

            {{-- 3-Column Split Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-stretch">
                <div class="md:col-span-12 lg:col-span-4 flex flex-col justify-between p-8 rounded-3xl bg-[#f0f8ec] border border-[#d6eda6]/80 shadow-xs">
                    <div class="space-y-4">
                        <p class="text-zinc-700 text-sm leading-relaxed">
                            {{ __('Selamat datang di :lembaga, wadah mencetak generasi Qurani berwawasan luas dengan kurikulum terstruktur, bimbingan intensif ustadz/ustadzah kompeten, dan pantauan wali santri terpadu.', ['lembaga' => $lembagaName]) }}
                        </p>
                        <a 
                            href="{{ route('about') }}" 
                            wire:navigate 
                            class="inline-flex items-center gap-3 rounded-full bg-[#6bb82d] hover:bg-[#5ca828] text-white font-bold text-xs py-2 ps-6 pe-2 shadow-md transition-all group"
                        >
                            <span>{{ __('Selengkapnya') }}</span>
                            <span class="size-8 rounded-full bg-[#4d8f1e] flex items-center justify-center text-white">
                                <flux:icon name="arrow-up-right" class="size-4" />
                            </span>
                        </a>
                    </div>

                    <div class="pt-8 border-t border-[#d6eda6] mt-6 flex items-center gap-3">
                        <h3 class="text-4xl font-extrabold text-[#2e5b18]">100%</h3>
                        <p class="text-xs text-zinc-600 font-medium leading-tight">{{ __('Metode Tilawati Nurul Falah Terstandarisasi') }}</p>
                    </div>
                </div>

                <div class="md:col-span-6 lg:col-span-4 flex flex-col gap-6">
                    <div class="p-6 rounded-3xl bg-[#f0f8ec] border border-[#d6eda6]/80 shadow-xs">
                        <div class="size-10 rounded-xl bg-white text-[#2e5b18] flex items-center justify-center mb-3 shadow-xs font-black">
                            <flux:icon name="gift" class="size-5 text-[#6bb82d]" />
                        </div>
                        <h4 class="text-lg font-bold text-zinc-900 mb-2">
                            {{ __('Pendaftaran Santri Baru') }}
                        </h4>
                        <p class="text-xs text-zinc-600 leading-relaxed">
                            {{ __('Pendaftaran santri baru dibuka sepanjang tahun untuk kelas TPQ, Madin Diniyah, dan Halaqah Tahfidz dengan penempatan sesuai hasil munaqasyah awal.') }}
                        </p>
                    </div>

                    <div class="p-6 rounded-3xl bg-[#f0f8ec] border border-[#d6eda6]/80 shadow-xs">
                        <div class="flex items-center -space-x-3 mb-4">
                            @foreach($pengajar->take(4) as $guru)
                                <div class="size-11 rounded-full overflow-hidden border-2 border-white shadow-xs">
                                    <img 
                                        src="{{ $guru->foto ? asset('storage/' . $guru->foto) : 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=100&auto=format&fit=crop&q=80' }}" 
                                        alt="{{ $guru->user->name ?? 'Asatidz' }}" 
                                        class="w-full h-full object-cover"
                                    >
                                </div>
                            @endforeach
                        </div>
                        <h4 class="text-lg font-bold text-zinc-900 mb-1">
                            {{ __('Asatidz Bersertifikat') }}
                        </h4>
                        <p class="text-xs text-zinc-600 leading-relaxed">
                            {{ __('Dibimbing oleh ustadz dan ustadzah yang telah memiliki syahadah standarisasi guru Al-Qur\'an dan berpengalaman.') }}
                        </p>
                    </div>
                </div>

                <div class="md:col-span-6 lg:col-span-4 p-6 rounded-3xl bg-[#f0f8ec] border border-[#d6eda6]/80 shadow-xs flex flex-col justify-between overflow-hidden">
                    <div class="h-48 rounded-2xl overflow-hidden mb-4 relative shadow-sm" data-editable-image="about_facility_image" data-image-label="Ganti Foto Fasilitas Belajar">
                        <img 
                            src="{{ $aboutFacilityImage }}" 
                            alt="Suasana Pembelajaran Santri" 
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                        >
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-zinc-900 mb-1">
                            {{ __('Lingkungan Belajar Nyaman') }}
                        </h4>
                        <p class="text-xs text-zinc-600 leading-relaxed">
                            {{ __('Ruang kelas halaqah yang bersih, perpustakaan kitab, musholla representatif, dan sarana multimedia pembelajaran.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. PROGRAM PENDIDIKAN SECTION (Dynamic Programs from Database) --}}
    <section class="py-20 bg-gradient-to-r from-[#e8f5e1] via-[#f0f8ec] to-[#e8f5e1]">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="max-w-2xl mx-auto text-center mb-14 space-y-2">
                <h2 data-editable-field="programs_title" class="text-3xl sm:text-4xl font-extrabold text-[#2e5b18]">
                    {{ $programsTitle }}
                </h2>
                <p data-editable-field="programs_subtitle" class="text-zinc-600 text-sm sm:text-base">
                    {{ $programsSubtitle }}
                </p>
            </div>

            <div data-db-locked="true" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4 rounded-3xl">
                @forelse($programs as $prog)
                    <div class="rounded-3xl border border-white/60 bg-white/70 backdrop-blur-md p-8 text-center shadow-md hover:shadow-xl hover:bg-white transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between group" wire:key="prog-{{ $prog->id }}">
                        <div>
                            @if ($prog->gambar_url)
                                <div class="h-36 rounded-2xl overflow-hidden mb-4 shadow-sm">
                                    <img src="{{ $prog->gambar_url }}" alt="{{ $prog->nama_program }}" class="size-full object-cover group-hover:scale-105 transition duration-500" />
                                </div>
                            @endif

                            <div class="flex justify-center mb-3">
                                @if ($prog->kategori_badge)
                                    <span class="px-3 py-1 rounded-full bg-[#6bb82d] text-white text-[11px] font-bold shadow-2xs">
                                        {{ $prog->kategori_badge }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-zinc-900 mb-2">
                                {{ $prog->nama_program }}
                            </h3>

                            <p class="text-xs sm:text-sm text-zinc-600 leading-relaxed mb-6 line-clamp-3">
                                {{ $prog->deskripsi_singkat }}
                            </p>
                        </div>

                        <div>
                            <a 
                                href="{{ route('program') }}" 
                                wire:navigate 
                                class="inline-flex items-center gap-3 rounded-full border border-zinc-300 bg-white hover:bg-[#6bb82d] hover:text-white hover:border-[#6bb82d] text-zinc-800 text-xs font-bold py-1.5 ps-5 pe-1.5 transition-all duration-300 group/btn shadow-xs"
                            >
                                <span>{{ __('Detail Program') }}</span>
                                <span class="size-8 rounded-full bg-zinc-100 group-hover/btn:bg-white/20 group-hover/btn:text-white flex items-center justify-center text-zinc-700 transition-all">
                                    <flux:icon name="arrow-up-right" class="size-4" />
                                </span>
                            </a>
                        </div>
                    </div>
                @empty
                    @foreach($learningCategories as $category)
                        <div class="rounded-3xl border border-white/60 bg-white/70 backdrop-blur-md p-8 text-center shadow-md hover:shadow-xl hover:bg-white transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between group">
                            <div>
                                <div class="size-20 rounded-full bg-gradient-to-tr {{ $category['gradient'] }} text-white flex items-center justify-center mx-auto mb-6 shadow-md shadow-lime-600/20 group-hover:scale-110 transition-transform duration-300">
                                    <flux:icon :name="$category['icon']" class="size-8" />
                                </div>
                                <h3 class="text-xl font-bold text-zinc-900 mb-2">{{ $category['title'] }}</h3>
                                <p class="text-xs sm:text-sm text-zinc-600 leading-relaxed mb-6">{{ $category['desc'] }}</p>
                            </div>
                            <div>
                                <a href="{{ route('program') }}" wire:navigate class="inline-flex items-center gap-3 rounded-full border border-zinc-300 bg-white hover:bg-[#6bb82d] hover:text-white text-zinc-800 text-xs font-bold py-1.5 ps-5 pe-1.5 transition-all duration-300 shadow-xs">
                                    <span>{{ $category['courses'] }}</span>
                                    <span class="size-8 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-700">
                                        <flux:icon name="arrow-up-right" class="size-4" />
                                    </span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    {{-- 5. ASATIDZ PENGAJAR (Locked from DB) --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="max-w-2xl mx-auto text-center mb-14 space-y-2">
                <h2 data-editable-field="teachers_title" class="text-3xl sm:text-4xl font-extrabold text-[#2e5b18]">
                    {{ $teachersTitle }}
                </h2>
                <p data-editable-field="teachers_subtitle" class="text-zinc-600 text-sm sm:text-base">
                    {{ $teachersSubtitle }}
                </p>
            </div>

            <div data-db-locked="true" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-4 rounded-3xl">
                @forelse($pengajar->take(8) as $guru)
                    <div class="rounded-3xl overflow-hidden border border-zinc-200 bg-white shadow-sm hover:shadow-xl transition-all duration-300 group" wire:key="guru-{{ $guru->id }}">
                        <div class="h-64 overflow-hidden relative bg-[#f0f8ec]">
                            <img 
                                src="{{ $guru->foto ? asset('storage/' . $guru->foto) : 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=600&auto=format&fit=crop&q=80' }}" 
                                alt="{{ $guru->user->name ?? 'Asatidz' }}" 
                                class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500"
                            >
                        </div>
                        <div class="p-5 text-center">
                            <h4 class="font-bold text-base text-zinc-900 leading-tight">
                                {{ $guru->user->name ?? 'Ustadz / Ustadzah' }}
                            </h4>
                            <p class="text-xs text-[#2e5b18] font-semibold mt-1">
                                {{ $guru->pendidikan_terakhir ? 'Lulusan ' . $guru->pendidikan_terakhir : __('Pengajar Al-Qur\'an & Diniyah') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-8 text-zinc-500">
                        <p>{{ __('Data dewan asatidz akan segera diperbarui.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- 6. FULL-WIDTH CALLOUT BANNER --}}
    <section class="relative py-24 bg-[#2e5b18] text-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 relative z-10">
            <div class="max-w-3xl space-y-5">
                <h2 data-editable-field="cta_title" class="text-3xl sm:text-5xl font-extrabold leading-tight">
                    {{ $ctaTitle }}
                </h2>

                <p data-editable-field="cta_subtitle" class="text-white/85 text-base leading-relaxed">
                    {{ $ctaSubtitle }}
                </p>

                <div class="pt-2">
                    <a 
                        href="{{ route('santri.register.form') }}" 
                        wire:navigate 
                        class="inline-flex items-center gap-3 rounded-full bg-[#6bb82d] hover:bg-[#5ca828] text-white font-bold text-base py-2.5 ps-8 pe-2.5 shadow-xl transition-all duration-300 group"
                    >
                        <span data-editable-field="cta_button_text">{{ $ctaButtonText }}</span>
                        <span class="size-10 rounded-full bg-[#4d8f1e] flex items-center justify-center text-white">
                            <flux:icon name="arrow-up-right" class="size-5" />
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 7. REVIEWS & TESTIMONIALS --}}
    <section class="py-20 bg-[#f0f8ec] overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="max-w-2xl mx-auto text-center mb-14 space-y-2">
                <h2 data-editable-field="testimonials_title" class="text-3xl sm:text-4xl font-extrabold text-[#2e5b18]">
                    {{ $testimonialsTitle }}
                </h2>
                <p data-editable-field="testimonials_subtitle" class="text-zinc-600 text-sm sm:text-base">
                    {{ $testimonialsSubtitle }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
                @foreach($testimonials as $testi)
                    <div class="bg-white rounded-2xl border border-zinc-200/80 p-6 shadow-md flex flex-col justify-between {{ $testi['rotate'] }} hover:rotate-0 hover:scale-105 hover:shadow-xl transition-all duration-500 group">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="size-12 rounded-full overflow-hidden shadow-xs border-2 border-emerald-100">
                                    <img src="{{ $testi['avatar'] }}" alt="{{ $testi['name'] }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h5 class="font-bold text-sm text-zinc-900 leading-tight">{{ $testi['name'] }}</h5>
                                    <p class="text-[11px] text-zinc-500 font-medium">{{ $testi['role'] }}</p>
                                </div>
                            </div>

                            <div class="flex gap-1 text-amber-400 text-sm">
                                @for($i = 0; $i < $testi['rating']; $i++)
                                    <flux:icon name="star" class="size-4 fill-amber-400 text-amber-400" />
                                @endfor
                            </div>

                            <p class="text-xs sm:text-sm text-zinc-600 leading-relaxed">"{{ $testi['text'] }}"</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 8. DOKUMENTASI KEGIATAN & GALERI (Locked from DB) --}}
    <section class="py-20 bg-[#f0f8ec]">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <h2 data-editable-field="gallery_title" class="text-3xl sm:text-4xl font-extrabold text-[#2e5b18]">
                        {{ $galleryTitle }}
                    </h2>
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach($this->galleryTypes as $type)
                        <button
                            wire:click="setGalleryType('{{ $type->value }}')"
                            class="px-4 py-1.5 rounded-full text-xs font-bold transition-all duration-200 {{ $this->activeGalleryType === $type->value ? 'bg-[#6bb82d] text-white shadow-sm' : 'bg-white text-zinc-700 border border-zinc-200 hover:bg-[#f0f8ec]' }}"
                        >
                            {{ $type->label() }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div data-db-locked="true" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-4 rounded-3xl">
                @forelse($galleries as $gallery)
                    <div class="rounded-3xl overflow-hidden border border-zinc-200 bg-white shadow-xs group relative" wire:key="gal-{{ $gallery->id }}">
                        <div class="aspect-[4/3] overflow-hidden relative">
                            <img 
                                src="{{ $gallery->image ? asset('storage/' . $gallery->image) : 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=600&auto=format&fit=crop&q=80' }}" 
                                alt="{{ $gallery->title ?? 'Galeri' }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                                <span class="text-xs font-bold text-lime-300">{{ $gallery->type instanceof \App\Enums\GalleryType ? $gallery->type->label() : ucfirst((string) ($gallery->type ?? 'Kegiatan')) }}</span>
                                <h4 class="font-bold text-white leading-snug mt-1">{{ $gallery->title }}</h4>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-zinc-500">
                        <p>{{ __('Belum ada foto galeri untuk kategori ini.') }}</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-12">
                <a 
                    href="{{ route('galeri') }}" 
                    wire:navigate 
                    class="inline-flex items-center gap-2 rounded-full border border-zinc-300 bg-white hover:bg-[#f0f8ec] px-6 py-2.5 text-xs font-bold text-[#2e5b18] transition-all shadow-xs"
                >
                    <span>{{ __('Lihat Semua Dokumentasi Galeri') }}</span>
                    <flux:icon name="arrow-right" class="size-4" />
                </a>
            </div>
        </div>
    </section>

</div>
