@php
    $setting = \App\Models\Setting::query()->first();
    $theme = $setting?->landing_theme ?? 'default';

    $pageGalleryTitle = $setting?->getLandingContent('page_gallery_title', $theme === 'pixigon' ? __('Galeri Dokumentasi') : __('Galeri & Dokumentasi Kegiatan'), $theme);
    $pageGallerySubtitle = $setting?->getLandingContent('page_gallery_subtitle', $theme === 'pixigon' ? __('Dokumentasi aktivitas, prestasi, dan momentum berharga santri lembaga kami.') : __('Dokumentasi momentum penting, kegiatan pembelajaran harian, perlombaan, dan acara keagamaan di lembaga kami.'), $theme);
    $pageGalleryBannerImage = $setting?->getLandingContent('page_gallery_banner_image', 'https://images.unsplash.com/photo-1609220136736-443140cffec6?w=1400&q=80&auto=format&fit=crop', $theme);
@endphp

@if ($theme === 'pixigon')
    {{-- ================= PIXIGON THEME (SOFT GREEN LIGHT) ================= --}}
    <div class="flex flex-col w-full overflow-hidden font-sans bg-white text-zinc-800">
        
        {{-- Inner Page Hero Banner Matching Screenshot 2 --}}
        <section class="relative bg-[#f0f8ec] py-20 lg:py-28 overflow-hidden font-sans" data-editable-image="page_gallery_banner_image" data-image-label="Ganti Background Banner Galeri">
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
                <h1 data-editable-field="page_gallery_title" class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-zinc-900 tracking-tight mb-3">
                    {{ $pageGalleryTitle }}
                </h1>
                
                <p data-editable-field="page_gallery_subtitle" class="text-zinc-600 text-sm sm:text-base max-w-2xl mx-auto mb-4">
                    {{ $pageGallerySubtitle }}
                </p>

                <div class="flex items-center justify-center gap-2 text-xs sm:text-sm font-medium text-zinc-500">
                    <a href="{{ route('home') }}" wire:navigate class="hover:text-[#2e5b18] transition">{{ __('Beranda') }}</a>
                    <span>/</span>
                    <span class="text-zinc-800 font-semibold">{{ __('Dokumentasi Kegiatan') }}</span>
                </div>
            </div>
        </section>

        {{-- Gallery Content Section --}}
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4 sm:px-6">
                {{-- Categories Pill Filter --}}
                <div class="flex flex-wrap items-center justify-center gap-2 mb-12">
                    <button
                        wire:click="setGalleryType('semua')"
                        class="px-5 py-2 rounded-full text-xs font-bold transition-all duration-200 {{ $activeGalleryType === 'semua' ? 'bg-[#6bb82d] text-white shadow-md' : 'bg-[#f0f8ec] text-zinc-700 hover:bg-[#d6eda6]' }}"
                    >
                        {{ __('Semua Dokumentasi') }}
                    </button>

                    @foreach($this->galleryTypes as $type)
                        <button
                            wire:click="setGalleryType('{{ $type }}')"
                            class="px-5 py-2 rounded-full text-xs font-bold transition-all duration-200 {{ $activeGalleryType === $type ? 'bg-[#6bb82d] text-white shadow-md' : 'bg-[#f0f8ec] text-zinc-700 hover:bg-[#d6eda6]' }}"
                        >
                            {{ ucfirst($type) }}
                        </button>
                    @endforeach
                </div>

                @if ($this->galleries->isEmpty())
                    <div class="text-center py-16 text-zinc-500">
                        <flux:icon name="photo" class="size-12 mx-auto mb-3 opacity-40 text-emerald-600" />
                        <p class="font-medium text-sm">{{ __('Belum ada dokumentasi foto untuk kategori ini.') }}</p>
                    </div>
                @else
                    {{-- 3-Column Masonry/Grid of Cards --}}
                    <div data-db-locked="true" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 p-4 rounded-3xl">
                        @foreach ($this->galleries as $gallery)
                            <div class="group rounded-3xl overflow-hidden border border-zinc-200/80 bg-white shadow-xs hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" wire:key="galeri-{{ $gallery->id }}">
                                <div class="aspect-[4/3] overflow-hidden relative bg-[#f0f8ec]">
                                    <img 
                                        src="{{ $gallery->image ? asset('storage/' . $gallery->image) : 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=600&auto=format&fit=crop&q=80' }}" 
                                        alt="{{ $gallery->title }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        loading="lazy"
                                    >
                                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full bg-white/90 backdrop-blur-xs text-[11px] font-bold text-[#2e5b18] shadow-xs">
                                        {{ $gallery->type instanceof \App\Enums\GalleryType ? $gallery->type->label() : ucfirst((string) ($gallery->type ?? 'Kegiatan')) }}
                                    </span>
                                </div>
                                <div class="p-6">
                                    <h4 class="text-base font-bold text-zinc-900 group-hover:text-[#2e5b18] transition-colors mb-2 leading-snug">
                                        {{ $gallery->title }}
                                    </h4>
                                    @if ($gallery->description)
                                        <p class="text-xs text-zinc-600 leading-relaxed line-clamp-2">
                                            {{ $gallery->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $this->galleries->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
@else
    {{-- ================= DEFAULT THEME (KLASIK EMERALD) ================= --}}
    <div class="flex flex-col w-full overflow-hidden">
        {{-- Hero Banner --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-20 text-white border-b-2 border-emerald-500/30" data-editable-image="page_gallery_banner_image" data-image-label="Ganti Background Banner Galeri">
            <img
                src="{{ $pageGalleryBannerImage }}"
                alt="Galeri"
                class="absolute inset-0 size-full object-cover opacity-20"
                loading="eager"
                width="1400" height="400"
            >
            <div class="relative mx-auto max-w-7xl px-6">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                    ✦ {{ __('Dokumentasi Lengkap') }}
                </span>
                <flux:heading size="xl" class="text-4xl! font-extrabold text-white leading-tight sm:text-5xl!" data-editable-field="page_gallery_title">
                    {{ $pageGalleryTitle }}
                </flux:heading>
                <p class="mt-4 max-w-2xl text-sm text-emerald-100/90 leading-relaxed" data-editable-field="page_gallery_subtitle">
                    {{ $pageGallerySubtitle }}
                </p>
            </div>
        </section>

        {{-- Main Gallery Content (Soft Jade Mist Theme #edf7f4) --}}
        <section class="w-full bg-[#edf7f4] py-16 border-b border-emerald-500/20">
            <div class="mx-auto max-w-7xl px-6">
                {{-- Category Filters --}}
                <div class="mb-10 flex flex-wrap items-center justify-between gap-4 border-b border-emerald-500/20 pb-6">
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="button"
                            wire:click="setGalleryType('semua')"
                            class="rounded-full px-4 py-2 text-xs font-semibold transition {{ $activeGalleryType === 'semua' ? 'bg-emerald-700 text-white shadow-md font-bold' : 'bg-white/90 text-emerald-950 hover:bg-white border border-emerald-500/20' }}"
                        >
                            {{ __('Semua Foto') }}
                        </button>

                        @foreach ($this->galleryTypes as $type)
                            <button
                                type="button"
                                wire:click="setGalleryType('{{ $type }}')"
                                class="rounded-full px-4 py-2 text-xs font-semibold transition {{ $activeGalleryType === $type ? 'bg-emerald-700 text-white shadow-md font-bold' : 'bg-white/90 text-emerald-950 hover:bg-white border border-emerald-500/20' }}"
                            >
                                {{ ucfirst($type) }}
                            </button>
                        @endforeach
                    </div>

                    <p class="text-xs text-emerald-900/80">
                        {{ __('Menampilkan dokumentasi :type', ['type' => $activeGalleryType === 'semua' ? 'seluruh kegiatan' : $activeGalleryType]) }}
                    </p>
                </div>

                @if ($this->galleries->isEmpty())
                    <flux:callout icon="photo" heading="{{ __('Belum ada galeri') }}" text="{{ __('Dokumentasi untuk kategori ini akan segera diperbarui.') }}" />
                @else
                    <div data-db-locked="true" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 p-4 rounded-3xl">
                        @foreach ($this->galleries as $gallery)
                            <div class="group overflow-hidden rounded-3xl border border-emerald-500/20 bg-white/95 shadow-md backdrop-blur-sm transition-all duration-300 hover:shadow-2xl" wire:key="galeri-{{ $gallery->id }}">
                                <div class="relative aspect-video overflow-hidden bg-emerald-900">
                                    <img
                                        src="{{ $gallery->image ? asset('storage/' . $gallery->image) : 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=400&q=80&auto=format&fit=crop' }}"
                                        alt="{{ $gallery->title }}"
                                        class="size-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy"
                                        width="400"
                                        height="225"
                                    >
                                    <span class="absolute top-3 right-3 rounded-full bg-emerald-950/80 border border-emerald-400/30 px-3 py-1 text-[10px] font-bold text-emerald-200 backdrop-blur-md">
                                        {{ $gallery->type instanceof \App\Enums\GalleryType ? $gallery->type->label() : ucfirst((string) ($gallery->type ?? 'Kegiatan')) }}
                                    </span>
                                </div>
                                <div class="p-5">
                                    <h3 class="font-bold text-emerald-950 text-base group-hover:text-emerald-700 transition">{{ $gallery->title }}</h3>
                                    @if ($gallery->description)
                                        <p class="mt-2 text-xs leading-relaxed text-zinc-600">{{ $gallery->description }}</p>
                                    @endif
                                    <span class="mt-4 block text-[11px] text-zinc-500">
                                        {{ $gallery->created_at->translatedFormat('d F Y') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $this->galleries->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
@endif
