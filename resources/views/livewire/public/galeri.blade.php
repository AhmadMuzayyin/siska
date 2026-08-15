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
                    {{ __('Galeri Dokumentasi') }}
                </h1>
                
                <div class="flex items-center justify-center gap-2 text-xs sm:text-sm font-medium text-zinc-500">
                    <a href="{{ route('home') }}" wire:navigate class="hover:text-[#2e5b18] transition">{{ __('Beranda') }}</a>
                    <span>/</span>
                    <span class="text-zinc-800 font-semibold">{{ __('Dokumentasi Kegiatan') }}</span>
                </div>

                <div class="mt-4 flex justify-center text-zinc-400">
                    <svg class="size-5 animate-bounce" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
            </div>
        </section>

        {{-- Gallery Content Section --}}
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4 sm:px-6 max-w-6xl">
                
                {{-- Category Filter Pills --}}
                <div class="mb-10 flex flex-wrap items-center justify-between gap-4 border-b border-[#d6eda6] pb-6">
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="button"
                            wire:click="setGalleryType('semua')"
                            class="rounded-full px-5 py-2 text-xs font-bold transition-all {{ $activeGalleryType === 'semua' ? 'bg-[#6bb82d] text-white shadow-md shadow-lime-600/20' : 'bg-[#f0f8ec] text-zinc-700 hover:bg-[#e4f2dc] border border-[#d6eda6]' }}"
                        >
                            {{ __('Semua Foto') }}
                        </button>

                        @foreach ($this->galleryTypes as $type)
                            <button
                                type="button"
                                wire:click="setGalleryType('{{ $type }}')"
                                class="rounded-full px-5 py-2 text-xs font-bold transition-all {{ $activeGalleryType === $type ? 'bg-[#6bb82d] text-white shadow-md shadow-lime-600/20' : 'bg-[#f0f8ec] text-zinc-700 hover:bg-[#e4f2dc] border border-[#d6eda6]' }}"
                            >
                                {{ ucfirst($type) }}
                            </button>
                        @endforeach
                    </div>

                    <p class="text-xs text-zinc-500 font-medium">
                        {{ __('Menampilkan: :type', ['type' => $activeGalleryType === 'semua' ? 'Semua Dokumentasi' : ucfirst($activeGalleryType)]) }}
                    </p>
                </div>

                {{-- Grid --}}
                @if ($this->galleries->isEmpty())
                    <div class="py-16 text-center">
                        <flux:callout icon="photo" heading="{{ __('Belum Ada Foto') }}" text="{{ __('Dokumentasi untuk kategori ini belum tersedia.') }}" />
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($this->galleries as $gallery)
                            <div class="group overflow-hidden rounded-3xl border border-[#d6eda6] bg-white shadow-xs hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" wire:key="pixigon-galeri-{{ $gallery->id }}">
                                <div class="relative aspect-video overflow-hidden bg-zinc-100">
                                    <img
                                        src="{{ $gallery->image }}"
                                        alt="{{ $gallery->title }}"
                                        class="size-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy"
                                        width="400" height="225"
                                    >
                                    <span class="absolute top-3 right-3 rounded-full bg-[#2e5b18]/90 text-white border border-white/30 px-3 py-1 text-[10px] font-bold backdrop-blur-md shadow-xs">
                                        {{ strtoupper($gallery->type->value ?? $gallery->type) }}
                                    </span>
                                </div>
                                <div class="p-5">
                                    <h4 class="font-bold text-[#2e5b18] text-sm group-hover:text-[#6bb82d] transition">{{ $gallery->title }}</h4>
                                    @if ($gallery->description)
                                        <p class="mt-2 text-xs leading-relaxed text-zinc-600">{{ $gallery->description }}</p>
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
        <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-20 text-white border-b-2 border-emerald-500/30">
            <img
                src="https://images.unsplash.com/photo-1609220136736-443140cffec6?w=1400&q=80&auto=format&fit=crop"
                alt="Galeri Al-Hikmah"
                class="absolute inset-0 size-full object-cover opacity-20"
                loading="eager"
                width="1400" height="400"
            >
            <div class="relative mx-auto max-w-7xl px-6">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                    ✦ {{ __('Dokumentasi Lengkap') }}
                </span>
                <flux:heading size="xl" class="text-4xl! font-extrabold text-white leading-tight sm:text-5xl!">
                    {{ __('Galeri & Dokumentasi Kegiatan') }}
                </flux:heading>
                <p class="mt-4 max-w-2xl text-sm text-emerald-100/90 leading-relaxed">
                    {{ __('Dokumentasi momentum penting, kegiatan pembelajaran harian, perlombaan, dan acara keagamaan di lembaga kami.') }}
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

                {{-- Gallery Grid --}}
                @if ($this->galleries->isEmpty())
                    <div class="py-16 text-center">
                        <flux:callout icon="photo" heading="{{ __('Belum Ada Foto') }}" text="{{ __('Dokumentasi untuk kategori ini belum tersedia.') }}" />
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($this->galleries as $gallery)
                            <div class="group overflow-hidden rounded-3xl border border-emerald-500/20 bg-white/95 shadow-md backdrop-blur-sm transition-all duration-300 hover:shadow-2xl hover:-translate-y-1" wire:key="galeri-item-{{ $gallery->id }}">
                                <div class="relative aspect-video overflow-hidden bg-emerald-900">
                                    <img
                                        src="{{ $gallery->image }}"
                                        alt="{{ $gallery->title }}"
                                        class="size-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy"
                                        width="400" height="225"
                                    >
                                    <span class="absolute top-3 right-3 rounded-full bg-[#06382b]/90 border border-emerald-400/30 px-3 py-1 text-[10px] font-bold text-emerald-200 backdrop-blur-md">
                                        {{ strtoupper($gallery->type->value ?? $gallery->type) }}
                                    </span>
                                </div>
                                <div class="p-5">
                                    <h4 class="font-bold text-emerald-950 text-sm group-hover:text-emerald-700 transition">{{ $gallery->title }}</h4>
                                    @if ($gallery->description)
                                        <p class="mt-2 text-xs leading-relaxed text-zinc-600">{{ $gallery->description }}</p>
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
@endif
