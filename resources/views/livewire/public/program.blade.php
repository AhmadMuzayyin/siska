@php
    $setting = \App\Models\Setting::query()->first();
    $theme = $setting?->landing_theme ?? 'default';
    $lembagaName = $setting?->lembaga ?? config('app.name');

    $pageProgramTitle = $setting?->getLandingContent('page_program_title', $theme === 'pixigon' ? __('Program & Kurikulum') : __('Program Pendidikan & Kurikulum'), $theme);
    $pageProgramSubtitle = $setting?->getLandingContent('page_program_subtitle', $theme === 'pixigon' ? __('Pilihan jenjang pendidikan Al-Qur\'an dan Madrasah Diniyah terstruktur di :lembaga.', ['lembaga' => $lembagaName]) : __('Program pembelajaran terstruktur yang memadukan pembacaan Al-Qur\'an metode Tilawati, ilmu diniyah, serta pembinaan karakter santri.'), $theme);
    $pageProgramBannerImage = $setting?->getLandingContent('page_program_banner_image', 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=1400&q=80&auto=format&fit=crop', $theme);
@endphp

@if ($theme === 'pixigon')
    {{-- ================= PIXIGON THEME (SOFT GREEN LIGHT) ================= --}}
    <div class="flex flex-col w-full overflow-hidden font-sans bg-white text-zinc-800">
        
        {{-- Inner Page Hero Banner --}}
        <section class="relative bg-[#f0f8ec] py-20 lg:py-28 overflow-hidden font-sans" data-editable-image="page_program_banner_image" data-image-label="Ganti Background Banner Program">
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

            {{-- Right Large Circular Arc Outline --}}
            <div class="hidden lg:block absolute -right-24 -bottom-24 size-80 rounded-full border border-lime-400/40 pointer-events-none"></div>

            {{-- Center Title & Breadcrumbs --}}
            <div class="container mx-auto px-4 sm:px-6 relative z-10 text-center max-w-4xl">
                <h1 data-editable-field="page_program_title" class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-zinc-900 tracking-tight mb-3">
                    {{ $pageProgramTitle }}
                </h1>
                
                <p data-editable-field="page_program_subtitle" class="text-zinc-600 text-sm sm:text-base max-w-2xl mx-auto mb-4">
                    {{ $pageProgramSubtitle }}
                </p>

                <div class="flex items-center justify-center gap-2 text-xs sm:text-sm font-medium text-zinc-500">
                    <a href="{{ route('home') }}" wire:navigate class="hover:text-[#2e5b18] transition">{{ __('Beranda') }}</a>
                    <span>/</span>
                    <span class="text-zinc-800 font-semibold">{{ __('Program Pendidikan') }}</span>
                </div>
            </div>
        </section>

        {{-- Dynamic Program List Section --}}
        <section class="py-20 bg-white">
            <div data-db-locked="true" class="container mx-auto px-4 sm:px-6 max-w-6xl space-y-16 p-4 rounded-3xl">
                @forelse ($this->programs as $index => $program)
                    @php
                        $isEven = $index % 2 === 1;
                    @endphp
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center rounded-3xl border border-[#d6eda6] bg-[#f0f8ec] p-8 sm:p-10 shadow-xs" wire:key="prog-page-{{ $program->id }}">
                        {{-- Content --}}
                        <div class="lg:col-span-7 {{ $isEven ? 'order-1 lg:order-2' : '' }} space-y-5">
                            <div class="flex items-center gap-3">
                                <div class="size-12 rounded-2xl bg-white text-[#2e5b18] flex items-center justify-center shadow-xs">
                                    <flux:icon :name="$program->icon ?: 'book-open'" class="size-6 text-[#6bb82d]" />
                                </div>
                                <div>
                                    @if ($program->kategori_badge)
                                        <span class="px-3 py-1 rounded-full bg-[#6bb82d] text-white text-[11px] font-bold shadow-2xs">
                                            {{ $program->kategori_badge }}
                                        </span>
                                    @endif
                                    <h2 class="text-2xl sm:text-3xl font-bold text-zinc-900 mt-1">{{ $program->nama_program }}</h2>
                                </div>
                            </div>

                            <p class="text-sm text-zinc-600 leading-relaxed">
                                {{ $program->deskripsi_singkat }}
                            </p>

                            @if (!empty($program->materi_unggulan))
                                <div class="space-y-3 pt-2">
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#2e5b18]">{{ __('Materi & Kurikulum Pembelajaran:') }}</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($program->materi_unggulan as $materi)
                                            <div class="flex items-start gap-2.5 p-3 rounded-2xl bg-white border border-[#d6eda6]/70 shadow-2xs">
                                                <span class="size-5 rounded-full bg-[#6bb82d]/15 text-[#2e5b18] flex items-center justify-center text-xs font-black shrink-0 mt-0.5">✓</span>
                                                <div>
                                                    <h5 class="text-xs font-bold text-zinc-800">{{ $materi['judul'] ?? '' }}</h5>
                                                    @if(!empty($materi['deskripsi']))
                                                        <p class="text-[11px] text-zinc-500 mt-0.5 leading-tight">{{ $materi['deskripsi'] }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="pt-2">
                                <a 
                                    href="{{ route('santri.register.form') }}" 
                                    wire:navigate 
                                    class="inline-flex items-center gap-2 rounded-full bg-[#6bb82d] hover:bg-[#5ca828] text-white font-bold text-xs py-2.5 ps-6 pe-2 shadow-md transition-all group"
                                >
                                    <span>{{ __('Daftar Santri Program Ini') }}</span>
                                    <span class="size-7 rounded-full bg-[#4d8f1e] flex items-center justify-center text-white">
                                        <flux:icon name="arrow-up-right" class="size-4" />
                                    </span>
                                </a>
                            </div>
                        </div>

                        {{-- Image / Visual Illustration --}}
                        <div class="lg:col-span-5 {{ $isEven ? 'order-2 lg:order-1' : '' }}">
                            <div class="aspect-[4/3] rounded-3xl overflow-hidden shadow-lg border-4 border-white bg-white">
                                @if ($program->gambar_url)
                                    <img src="{{ $program->gambar_url }}" alt="{{ $program->nama_program }}" class="size-full object-cover hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="size-full bg-gradient-to-tr from-emerald-100 to-[#d6eda6] flex flex-col items-center justify-center text-[#2e5b18] p-6 text-center">
                                        <flux:icon :name="$program->icon ?: 'book-open'" class="size-16 mb-2 opacity-60" />
                                        <span class="text-xs font-bold">{{ $program->nama_program }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 text-zinc-500">
                        <flux:icon name="book-open" class="size-12 mx-auto mb-3 opacity-40 text-emerald-600" />
                        <p class="font-medium text-sm">{{ __('Belum ada data program pendidikan aktif yang ditambahkan.') }}</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Bottom CTA Banner --}}
        <section class="py-20 bg-[#2e5b18] text-white text-center">
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
        <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-20 text-white border-b-2 border-emerald-500/30" data-editable-image="page_program_banner_image" data-image-label="Ganti Background Banner Program">
            <img
                src="{{ $pageProgramBannerImage }}"
                alt="Program Pendidikan"
                class="absolute inset-0 size-full object-cover opacity-20"
                loading="eager"
                width="1400" height="400"
            >
            <div class="relative mx-auto max-w-7xl px-6">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                    ✦ {{ __('Kurikulum & Pendidikan') }}
                </span>
                <flux:heading size="xl" class="text-4xl! font-extrabold text-white leading-tight sm:text-5xl!" data-editable-field="page_program_title">
                    {{ $pageProgramTitle }}
                </flux:heading>
                <p class="mt-4 max-w-2xl text-sm text-emerald-100/90 leading-relaxed" data-editable-field="page_program_subtitle">
                    {{ $pageProgramSubtitle }}
                </p>
            </div>
        </section>

        {{-- Dynamic Program Detail Sections (Soft Jade Mist Theme #edf7f4) --}}
        <section class="w-full bg-[#edf7f4] py-16 border-b border-emerald-500/20">
            <div data-db-locked="true" class="mx-auto max-w-7xl px-6 space-y-16 p-4 rounded-3xl">
                @forelse ($this->programs as $index => $program)
                    @php
                        $isEven = $index % 2 === 1;
                    @endphp
                    <div class="grid grid-cols-1 gap-10 items-center lg:grid-cols-12 rounded-3xl border border-emerald-500/20 bg-white/95 p-8 shadow-md backdrop-blur-sm transition duration-300 hover:shadow-2xl" wire:key="prog-card-{{ $program->id }}">
                        {{-- Content --}}
                        <div class="lg:col-span-7 {{ $isEven ? 'order-1 lg:order-2' : '' }} space-y-5">
                            <div class="flex items-center gap-3">
                                <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                    <flux:icon :name="$program->icon ?: 'book-open'" class="size-6" />
                                </div>
                                <div>
                                    @if ($program->kategori_badge)
                                        <span class="rounded-full bg-emerald-900 px-3 py-1 text-xs font-bold text-emerald-200">
                                            {{ $program->kategori_badge }}
                                        </span>
                                    @endif
                                    <h3 class="text-2xl font-bold text-emerald-950 mt-1">{{ $program->nama_program }}</h3>
                                </div>
                            </div>

                            <p class="text-xs sm:text-sm text-zinc-600 leading-relaxed">
                                {{ $program->deskripsi_singkat }}
                            </p>

                            @if (!empty($program->materi_unggulan))
                                <div class="space-y-3 pt-2">
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-900">{{ __('Materi & Fokus Pembelajaran:') }}</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($program->materi_unggulan as $materi)
                                            <div class="flex items-start gap-2.5 p-3 rounded-2xl bg-[#edf7f4] border border-emerald-500/20">
                                                <flux:icon name="check-circle" class="size-4 text-emerald-600 shrink-0 mt-0.5" />
                                                <div>
                                                    <h5 class="text-xs font-bold text-emerald-950">{{ $materi['judul'] ?? '' }}</h5>
                                                    @if(!empty($materi['deskripsi']))
                                                        <p class="text-[11px] text-zinc-600 mt-0.5 leading-tight">{{ $materi['deskripsi'] }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="pt-2">
                                <flux:button variant="primary" class="bg-emerald-700! hover:bg-emerald-800! text-white! text-xs font-bold" :href="route('santri.register.form')" wire:navigate>
                                    <flux:icon name="user-plus" class="size-3.5 me-1.5" />
                                    {{ __('Daftar Santri Baru') }}
                                </flux:button>
                            </div>
                        </div>

                        {{-- Image / Visual Illustration --}}
                        <div class="lg:col-span-5 {{ $isEven ? 'order-2 lg:order-1' : '' }}">
                            <div class="aspect-[4/3] rounded-3xl overflow-hidden shadow-xl border-2 border-emerald-500/30 bg-emerald-900">
                                @if ($program->gambar_url)
                                    <img src="{{ $program->gambar_url }}" alt="{{ $program->nama_program }}" class="size-full object-cover hover:scale-105 transition duration-500">
                                @else
                                    <div class="size-full bg-gradient-to-br from-emerald-800 to-emerald-950 flex flex-col items-center justify-center text-emerald-200 p-6 text-center">
                                        <flux:icon :name="$program->icon ?: 'book-open'" class="size-16 mb-2 opacity-60" />
                                        <span class="text-xs font-bold">{{ $program->nama_program }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 text-zinc-500">
                        <flux:icon name="book-open" class="size-12 mx-auto mb-3 opacity-40 text-emerald-700" />
                        <p class="font-medium text-sm">{{ __('Belum ada data program pendidikan aktif yang ditambahkan.') }}</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endif
