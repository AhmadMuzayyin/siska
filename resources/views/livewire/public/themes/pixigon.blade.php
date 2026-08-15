@php
    $setting = $this->setting;
    $lembagaName = $setting?->lembaga ?? config('app.name');
    $lembagas = $this->lembagas;
    $pengajar = $this->pengajar;
    $galleries = $this->galleries;
    $santriCount = $this->santriAktifCount;
    $guruCount = $this->guruAktifCount;
    $kelasCount = $this->kelasCount;
    $totalLembaga = $this->totalLembagaCount;

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
            'title' => 'Kajian Kitab Kuning & Dirasah',
            'desc' => 'Mempelajari dasar aqidah, fiqih ibadah safinah/sulammuttaufiq, akhlaq lil banin/banat, dan dasar Bahasa Arab.',
            'courses' => 'Tingkat Ula & Wustha',
            'icon' => 'languages',
            'gradient' => 'from-amber-400 to-emerald-600',
        ],
        [
            'title' => 'Praktek Ibadah & Doa Harian',
            'desc' => 'Bimbingan wudhu, shalat fardhu & sunnah, dzikir ba\'da shalat, hafalan doa sehari-hari, serta pembiasaan sholat berjamaah.',
            'courses' => 'Harian & Praktik',
            'icon' => 'graduation-cap',
            'gradient' => 'from-teal-400 to-emerald-500',
        ],
        [
            'title' => 'Seni Islami & Khot Al-Qur\'an',
            'desc' => 'Pengembangan bakat seni hadrah/rebana, kaligrafi Islam (khat naskhi), pildacil, dan seni tilawah.',
            'courses' => 'Ekstrakurikuler',
            'icon' => 'award',
            'gradient' => 'from-emerald-500 to-teal-700',
        ],
        [
            'title' => 'Pendidikan Karakter & Adab',
            'desc' => 'Pembiasaan akhlaqul karimah kepada orang tua, guru, dan sesama teman sesuai tuntunan Rasulullah SAW.',
            'courses' => 'Program Unggulan',
            'icon' => 'users',
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

        <div class="hidden lg:block absolute bottom-32 left-28 pointer-events-none text-zinc-800">
            <svg class="size-12" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M16 4L19 13L28 16L19 19L16 28L13 19L4 16L13 13L16 4Z" fill="currentColor" fill-opacity="0.1"/>
                <path d="M36 24L38 29L43 31L38 33L36 38L34 33L29 31L34 29L36 24Z"/>
            </svg>
        </div>

        <div class="hidden lg:block absolute top-40 right-12 pointer-events-none text-zinc-700">
            <svg class="w-14 h-16" viewBox="0 0 60 70" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                <path d="M10 10C25 5 45 20 40 40C35 60 15 50 25 30C35 10 50 35 55 55"/>
            </svg>
        </div>

        <div class="container mx-auto px-4 sm:px-6 relative z-10">
            <div class="grid grid-cols-12 gap-6 items-center">
                
                {{-- Left Student Illustration (Santriwati Belajar Al-Qur'an on Pastel Lime Medallion) --}}
                <div class="hidden lg:block lg:col-span-3">
                    <div class="relative flex justify-center -mt-10">
                        <div class="size-60 xl:size-72 rounded-full bg-[#d6eda6] flex items-center justify-center relative shadow-lg overflow-hidden border-4 border-white">
                            <img 
                                src="https://images.unsplash.com/photo-1585036156171-384164a8c675?w=600&auto=format&fit=crop&q=80" 
                                alt="Santriwati Mengaji Al-Qur'an" 
                                class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-500"
                            >
                        </div>
                    </div>
                </div>

                {{-- Center Content Column --}}
                <div class="col-span-12 lg:col-span-6 text-center">
                    
                    {{-- Dot Matrix Pattern Behind Heading --}}
                    <div class="relative">
                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-48 h-32 opacity-20 pointer-events-none">
                            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="dot-pattern" width="16" height="16" patternUnits="userSpaceOnUse">
                                        <circle cx="2" cy="2" r="2" fill="#2e5b18"/>
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#dot-pattern)" />
                            </svg>
                        </div>

                        {{-- Handwritten Style Pill Badge --}}
                        <span class="inline-flex items-center gap-2 border border-zinc-700/30 bg-white/50 rounded-full px-4 py-1 text-xs font-semibold text-zinc-800 shadow-2xs mb-6">
                            <span>✍️</span>
                            <span>{{ __('Pendidikan Al-Qur\'an & Karakter Islami') }}</span>
                        </span>

                        {{-- Main Headline --}}
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-[#2e5b18] leading-[1.15] tracking-tight mb-6">
                            {{ __('Membentuk Generasi') }}<br>
                            {{ __('Qur\'ani & Beradab') }}
                        </h1>

                        {{-- Subtitle --}}
                        <p class="text-zinc-600 text-sm sm:text-base leading-relaxed max-w-xl mx-auto mb-8 font-normal">
                            {{ $setting?->meta_deskripsi ?? __('Pendidikan Islam terpadu dengan metode Tilawati, tahfidzul Qur\'an, kajian kitab kuning, dan pembinaan akhlak mulia untuk masa depan generasi berkarakter.') }}
                        </p>

                        {{-- Pixigon Single Signature Pill CTA Button --}}
                        <div>
                            <a 
                                href="{{ route('santri.register.form') }}" 
                                wire:navigate 
                                class="inline-flex items-center gap-3 rounded-full bg-[#6bb82d] hover:bg-[#5ca828] text-white font-bold text-base py-2.5 ps-8 pe-2.5 shadow-xl shadow-lime-600/25 transition-all duration-300 transform hover:-translate-y-0.5 group"
                            >
                                <span>{{ __('Daftar Santri Baru') }}</span>
                                <span class="size-10 rounded-full bg-[#4d8f1e] group-hover:bg-[#3d7a17] flex items-center justify-center text-white transition-all">
                                    <flux:icon name="arrow-up-right" class="size-5" />
                                </span>
                            </a>
                        </div>
                    </div>

                </div>

                {{-- Right Student Illustration (Santriwan Berkoko & Mushaf on Organic Pebble Blob) --}}
                <div class="hidden lg:block lg:col-span-3">
                    <div class="relative flex justify-center mt-12">
                        <div class="size-60 xl:size-72 rounded-[40%_60%_70%_30%/40%_50%_60%_55%] bg-[#82b834] flex items-center justify-center relative shadow-lg overflow-hidden border-4 border-white">
                            <img 
                                src="https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=600&auto=format&fit=crop&q=80" 
                                alt="Santriwan Belajar Kitab & Qur'an" 
                                class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-500"
                            >
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- 2. FLOATING CIRCULAR STAT COUNTERS (Staggered Wave Rhythm) --}}
    <section class="relative -mt-20 lg:-mt-28 z-20 pb-20">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto">
                
                {{-- Circle 1: Santri Aktif --}}
                <div class="flex flex-col items-center justify-center size-44 sm:size-48 rounded-full bg-white shadow-xl shadow-zinc-200/70 p-6 text-center mx-auto transform transition-all duration-300 hover:-translate-y-2 group">
                    <h3 class="text-4xl font-extrabold text-[#2e5b18] mb-1 group-hover:scale-105 transition-transform">
                        {{ $santriCount > 0 ? $santriCount : '120' }}+
                    </h3>
                    <p class="text-zinc-600 text-xs sm:text-sm font-medium">
                        {{ __('Santri Aktif') }}
                    </p>
                </div>

                {{-- Circle 2: Kelas & Halaqah --}}
                <div class="flex flex-col items-center justify-center size-44 sm:size-48 rounded-full bg-white shadow-xl shadow-zinc-200/70 p-6 text-center mx-auto lg:mt-14 transform transition-all duration-300 hover:-translate-y-2 group">
                    <h3 class="text-4xl font-extrabold text-[#2e5b18] mb-1 group-hover:scale-105 transition-transform">
                        {{ $kelasCount > 0 ? $kelasCount : '8' }}+
                    </h3>
                    <p class="text-zinc-600 text-xs sm:text-sm font-medium">
                        {{ __('Halaqah & Kelas') }}
                    </p>
                </div>

                {{-- Circle 3: Asatidz Pengajar --}}
                <div class="flex flex-col items-center justify-center size-44 sm:size-48 rounded-full bg-white shadow-xl shadow-zinc-200/70 p-6 text-center mx-auto lg:mt-14 transform transition-all duration-300 hover:-translate-y-2 group">
                    <h3 class="text-4xl font-extrabold text-[#2e5b18] mb-1 group-hover:scale-105 transition-transform">
                        {{ $guruCount > 0 ? $guruCount : '15' }}+
                    </h3>
                    <p class="text-zinc-600 text-xs sm:text-sm font-medium">
                        {{ __('Asatidz Pengajar') }}
                    </p>
                </div>

                {{-- Circle 4: Jenjang Pendidikan --}}
                <div class="flex flex-col items-center justify-center size-44 sm:size-48 rounded-full bg-white shadow-xl shadow-zinc-200/70 p-6 text-center mx-auto transform transition-all duration-300 hover:-translate-y-2 group">
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
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[#2e5b18] leading-tight">
                        {{ __('Tentang Lembaga & Pendidikan Kami') }}
                    </h2>
                </div>
                <div class="lg:col-span-6 lg:col-end-13">
                    <p class="text-zinc-600 text-sm sm:text-base leading-relaxed">
                        {{ __('Lembaga kami mendampingi santri meraih kefasihan membaca Al-Qur\'an, kedalaman pemahaman agama, serta pembiasaan akhlakul karimah dengan metode yang mudah, menyenangkan, dan bersanad.') }}
                    </p>
                </div>
            </div>

            {{-- 3-Column Split Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-stretch">
                
                {{-- Column 1: Mission & Stats --}}
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

                {{-- Column 2: Highlights (Offer + Top Faculty) --}}
                <div class="md:col-span-6 lg:col-span-4 flex flex-col gap-6">
                    
                    {{-- Card 1: Penerimaan Santri Baru --}}
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

                    {{-- Card 2: Tenaga Pengajar Asatidz --}}
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

                {{-- Column 3: Fasilitas & Lingkungan --}}
                <div class="md:col-span-6 lg:col-span-4 p-6 rounded-3xl bg-[#f0f8ec] border border-[#d6eda6]/80 shadow-xs flex flex-col justify-between overflow-hidden">
                    <div class="h-48 rounded-2xl overflow-hidden mb-4 relative shadow-sm">
                        <img 
                            src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=800&auto=format&fit=crop&q=80" 
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

    {{-- 4. RANGE OF LEARNING CATEGORIES --}}
    <section class="py-20 bg-gradient-to-r from-[#e8f5e1] via-[#f0f8ec] to-[#e8f5e1]">
        <div class="container mx-auto px-4 sm:px-6">
            
            <div class="max-w-2xl mx-auto text-center mb-14 space-y-2">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#2e5b18]">
                    {{ __('Pilihan Program & Kurikulum Pembelajaran') }}
                </h2>
                <p class="text-zinc-600 text-sm sm:text-base">
                    {{ __('Kurikulum berjenjang dan terintegrasi dirancang agar setiap santri dapat belajar sesuai tahapan usia dan kemampuan.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($learningCategories as $category)
                    <div class="rounded-3xl border border-white/60 bg-white/70 backdrop-blur-md p-8 text-center shadow-md hover:shadow-xl hover:bg-white transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between group">
                        <div>
                            <div class="size-20 rounded-full bg-gradient-to-tr {{ $category['gradient'] }} text-white flex items-center justify-center mx-auto mb-6 shadow-md shadow-lime-600/20 group-hover:scale-110 transition-transform duration-300">
                                @if($category['icon'] === 'book-open')
                                    <flux:icon name="book-open" class="size-8" />
                                @elseif($category['icon'] === 'languages')
                                    <flux:icon name="language" class="size-8" />
                                @elseif($category['icon'] === 'sparkles')
                                    <flux:icon name="sparkles" class="size-8" />
                                @elseif($category['icon'] === 'graduation-cap')
                                    <flux:icon name="academic-cap" class="size-8" />
                                @elseif($category['icon'] === 'award')
                                    <flux:icon name="trophy" class="size-8" />
                                @else
                                    <flux:icon name="user-group" class="size-8" />
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-zinc-900 mb-2">
                                {{ $category['title'] }}
                            </h3>

                            <p class="text-xs sm:text-sm text-zinc-600 leading-relaxed mb-6">
                                {{ $category['desc'] }}
                            </p>
                        </div>

                        <div>
                            <a 
                                href="{{ route('program') }}" 
                                wire:navigate 
                                class="inline-flex items-center gap-3 rounded-full border border-zinc-300 bg-white hover:bg-[#6bb82d] hover:text-white hover:border-[#6bb82d] text-zinc-800 text-xs font-bold py-1.5 ps-5 pe-1.5 transition-all duration-300 group/btn shadow-xs"
                            >
                                <span>{{ $category['courses'] }}</span>
                                <span class="size-8 rounded-full bg-zinc-100 group-hover/btn:bg-white/20 group-hover/btn:text-white flex items-center justify-center text-zinc-700 transition-all">
                                    <flux:icon name="arrow-up-right" class="size-4" />
                                </span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- 5. MOST POPULAR COURSES / JENJANG LEMBAGA --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6">
            
            <div class="max-w-2xl mx-auto text-center mb-14 space-y-2">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#2e5b18]">
                    {{ __('Jenjang Lembaga Pendidikan') }}
                </h2>
                <p class="text-zinc-600 text-sm sm:text-base">
                    {{ __('Pilihan jenjang pendidikan formal diniyah dan Al-Qur\'an di bawah naungan :lembaga.', ['lembaga' => $lembagaName]) }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($lembagas as $lembaga)
                    <div class="rounded-3xl border border-zinc-200 bg-white shadow-md overflow-hidden flex flex-col justify-between hover:shadow-xl hover:border-[#6bb82d] transition-all duration-300 group">
                        <div>
                            <div class="h-44 bg-[#f0f8ec] p-6 flex flex-col justify-between border-b border-[#d6eda6]">
                                <div class="flex items-center justify-between">
                                    <span class="px-3 py-1 rounded-full bg-[#6bb82d] text-xs font-bold text-white shadow-2xs">
                                        {{ $lembaga->jenjang ?? __('Program Unggulan') }}
                                    </span>
                                    <span class="text-xs font-bold text-[#2e5b18]">
                                        {{ $lembaga->kode }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-extrabold text-[#2e5b18] leading-tight">
                                        {{ $lembaga->nama }}
                                    </h3>
                                    @if($lembaga->kepala_lembaga)
                                        <p class="text-xs text-zinc-600 mt-1">Kepala: {{ $lembaga->kepala_lembaga }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="p-6 space-y-3">
                                <div class="grid grid-cols-2 gap-2 py-2 border-y border-zinc-100 text-xs font-semibold text-zinc-700">
                                    <div class="flex items-center gap-1.5">
                                        <flux:icon name="users" class="size-4 text-[#6bb82d]" />
                                        <span>{{ $lembaga->santris_count ?? 0 }} {{ __('Santri') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <flux:icon name="academic-cap" class="size-4 text-[#7cb342]" />
                                        <span>{{ $lembaga->kelas_count ?? 0 }} {{ __('Kelas') }}</span>
                                    </div>
                                </div>

                                <p class="text-xs text-zinc-600 leading-relaxed">
                                    {{ $lembaga->alamat ?? __('Kurikulum terstruktur untuk membentuk santri berilmu amaliyah dan berakhlakul karimah.') }}
                                </p>
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <a 
                                href="{{ route('santri.register.form') }}" 
                                wire:navigate 
                                class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-[#f0f8ec] hover:bg-[#6bb82d] hover:text-white py-3 px-4 text-xs font-bold text-[#2e5b18] transition-all duration-200 group-hover:bg-[#6bb82d] group-hover:text-white"
                            >
                                <span>{{ __('Daftar di Jenjang Ini') }}</span>
                                <flux:icon name="arrow-right" class="size-3.5" />
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-zinc-500">
                        <p>{{ __('Belum ada data program/lembaga terdaftar.') }}</p>
                    </div>
                @endforelse
            </div>

        </div>
    </section>

    {{-- 6. FULL-WIDTH CALLOUT BANNER --}}
    <section class="relative py-24 bg-[#2e5b18] text-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 relative z-10">
            <div class="max-w-3xl space-y-5">
                <h2 class="text-3xl sm:text-5xl font-extrabold leading-tight">
                    {{ __('Daftarkan Putra-Putri Anda Menjadi Generasi Pecinta Al-Qur\'an!') }}
                </h2>

                <p class="text-white/85 text-base leading-relaxed">
                    {{ __('Mari bersama-sama membimbing putra-putri kita menjadi generasi Qurani yang fasih membaca Al-Qur\'an, kokoh dalam aqidah, dan santun dalam budi pekerti.') }}
                </p>

                <div class="pt-2">
                    <a 
                        href="{{ route('santri.register.form') }}" 
                        wire:navigate 
                        class="inline-flex items-center gap-3 rounded-full bg-[#6bb82d] hover:bg-[#5ca828] text-white font-bold text-base py-2.5 ps-8 pe-2.5 shadow-xl transition-all duration-300 group"
                    >
                        <span>{{ __('Daftar Santri Baru Sekarang') }}</span>
                        <span class="size-10 rounded-full bg-[#4d8f1e] flex items-center justify-center text-white">
                            <flux:icon name="arrow-up-right" class="size-5" />
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 7. PLAYFUL TILTED REVIEWS --}}
    <section class="py-20 bg-[#f0f8ec] overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6">
            
            <div class="max-w-2xl mx-auto text-center mb-14 space-y-2">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#2e5b18]">
                    {{ __('Apa Kata Wali Santri & Alumni?') }}
                </h2>
                <p class="text-zinc-600 text-sm sm:text-base">
                    {{ __('Pengalaman nyata para wali santri dan alumni dalam proses belajar mengajar di lembaga kami.') }}
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
                                    <h5 class="font-bold text-sm text-zinc-900 leading-tight">
                                        {{ $testi['name'] }}
                                    </h5>
                                    <p class="text-[11px] text-zinc-500 font-medium">
                                        {{ $testi['role'] }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-1 text-amber-400 text-sm">
                                @for($i = 0; $i < $testi['rating']; $i++)
                                    <flux:icon name="star" class="size-4 fill-amber-400 text-amber-400" />
                                @endfor
                            </div>

                            <p class="text-xs sm:text-sm text-zinc-600 leading-relaxed">
                                "{{ $testi['text'] }}"
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- 8. ASATIDZ PENGAJAR --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6">
            
            <div class="max-w-2xl mx-auto text-center mb-14 space-y-2">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#2e5b18]">
                    {{ __('Dewan Asatidz & Ustadzah Pengajar') }}
                </h2>
                <p class="text-zinc-600 text-sm sm:text-base">
                    {{ __('Tenaga pendidik bersyahadah, berdedikasi tinggi, dan telaten dalam membimbing bacaan serta akhlak santri.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($pengajar->take(8) as $guru)
                    <div class="rounded-3xl overflow-hidden border border-zinc-200 bg-white shadow-sm hover:shadow-xl transition-all duration-300 group">
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

    {{-- 9. DOKUMENTASI KEGIATAN & GALERI --}}
    <section class="py-20 bg-[#f0f8ec]">
        <div class="container mx-auto px-4 sm:px-6">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[#2e5b18]">
                        {{ __('Dokumentasi Kegiatan Santri') }}
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($galleries as $gallery)
                    <div class="rounded-3xl overflow-hidden border border-zinc-200 bg-white shadow-xs group relative">
                        <div class="aspect-[4/3] overflow-hidden relative">
                            <img 
                                src="{{ $gallery->image ? asset('storage/' . $gallery->image) : 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=600&auto=format&fit=crop&q=80' }}" 
                                alt="{{ $gallery->title ?? 'Galeri' }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                                <span class="text-xs font-bold text-lime-300">{{ $gallery->type instanceof \App\Enums\GalleryType ? $gallery->type->label() : ucfirst((string) ($gallery->type ?? 'Kegiatan')) }}</span>
                                <h4 class="text-base font-bold text-white leading-snug mt-1">{{ $gallery->title }}</h4>
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
