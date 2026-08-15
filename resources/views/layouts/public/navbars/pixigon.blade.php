@props(['setting' => null])

@php
    $isHome = request()->routeIs('home');
    $isProgram = request()->routeIs('program');
    $isGaleri = request()->routeIs('galeri');
    $isAbout = request()->routeIs('about');
    $isContact = request()->routeIs('contact.show');
    $lembagaName = $setting?->lembaga ?? config('app.name');
@endphp

<header 
    x-data="{ mobileMenuOpen: false, scrolled: false }" 
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
    :class="scrolled ? 'bg-[#f0f8ec]/95 backdrop-blur-md shadow-xs py-4 border-b border-lime-200/50' : 'bg-[#f0f8ec] py-6'"
    class="sticky top-0 z-50 transition-all duration-300 font-sans"
>
    <div class="container mx-auto px-4 sm:px-8 flex items-center justify-between">
        
        {{-- Brand Logo (PIXIGON Typographic Style in Dark Green with Star/Leaf) --}}
        <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 group">
            <div class="size-8 rounded-lg bg-[#6bb82d] text-white flex items-center justify-center font-black text-sm shadow-xs group-hover:scale-105 transition-transform">
                <svg class="size-4.5 fill-current" viewBox="0 0 24 24">
                    <path d="M12 2L15 9H22L16.5 13.5L18.5 20.5L12 16L5.5 20.5L7.5 13.5L2 9H9L12 2Z"/>
                </svg>
            </div>
            <span class="font-extrabold text-2xl tracking-tight text-[#2e5b18] uppercase">
                {{ $lembagaName }}
            </span>
        </a>

        {{-- Desktop Navigation (Clean Links Matching Screenshot 3) --}}
        <nav class="hidden lg:flex items-center gap-9 font-semibold text-sm text-zinc-700">
            <a 
                href="{{ route('home') }}" 
                wire:navigate 
                class="transition-colors hover:text-[#2e5b18] {{ $isHome ? 'text-[#2e5b18] font-bold' : '' }}"
            >
                {{ __('Beranda') }}
            </a>
            <a 
                href="{{ route('program') }}" 
                wire:navigate 
                class="transition-colors hover:text-[#2e5b18] {{ $isProgram ? 'text-[#2e5b18] font-bold' : '' }}"
            >
                {{ __('Program') }}
            </a>
            <a 
                href="{{ route('galeri') }}" 
                wire:navigate 
                class="transition-colors hover:text-[#2e5b18] {{ $isGaleri ? 'text-[#2e5b18] font-bold' : '' }}"
            >
                {{ __('Galeri') }}
            </a>
            <a 
                href="{{ route('about') }}" 
                wire:navigate 
                class="transition-colors hover:text-[#2e5b18] {{ $isAbout ? 'text-[#2e5b18] font-bold' : '' }}"
            >
                {{ __('Tentang Kami') }}
            </a>
        </nav>

        {{-- Desktop Right Action (Single Purple/Indigo Contact Us Button + Login) --}}
        <div class="hidden lg:flex items-center gap-4">
            @auth
                <a 
                    href="{{ route('dashboard') }}" 
                    wire:navigate 
                    class="inline-flex items-center gap-2 rounded-full bg-[#2e5b18] hover:bg-[#1f3f10] text-white font-bold text-xs px-6 py-2.5 shadow-sm transition-all"
                >
                    <span>{{ __('Dashboard') }}</span>
                </a>
            @else
                <a 
                    href="{{ route('contact.show') }}" 
                    wire:navigate 
                    class="inline-flex items-center gap-2 rounded-full bg-[#536dfe] hover:bg-[#435bd8] text-white font-bold text-xs px-6 py-2.5 shadow-md shadow-indigo-500/20 transition-all transform hover:-translate-y-0.5"
                >
                    <span>{{ __('Contact Us') }}</span>
                    <flux:icon name="phone" class="size-3.5 text-white" />
                </a>

                <a 
                    href="{{ route('login') }}" 
                    wire:navigate 
                    class="text-xs font-bold text-zinc-700 hover:text-[#2e5b18] transition px-1 py-1"
                >
                    {{ __('Login') }}
                </a>
            @endauth
        </div>

        {{-- Mobile Menu Trigger --}}
        <div class="flex items-center lg:hidden">
            <button 
                type="button" 
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="size-10 rounded-xl bg-white/90 border border-zinc-200 text-zinc-800 flex items-center justify-center shadow-xs focus:outline-none"
                aria-label="{{ __('Buka Menu Navigasi') }}"
            >
                <flux:icon name="bars-3" class="size-6" x-show="!mobileMenuOpen" />
                <flux:icon name="x-mark" class="size-6" x-show="mobileMenuOpen" style="display: none;" />
            </button>
        </div>

    </div>

    {{-- Mobile Offcanvas Drawer --}}
    <div 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-zinc-900/40 backdrop-blur-xs lg:hidden"
        @click="mobileMenuOpen = false"
        style="display: none;"
    ></div>

    <div 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-50 w-72 bg-white p-6 shadow-2xl flex flex-col justify-between border-r border-zinc-200 lg:hidden"
        style="display: none;"
    >
        <div class="space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                <span class="font-black text-base text-[#2e5b18] uppercase">{{ $lembagaName }}</span>
                <button type="button" @click="mobileMenuOpen = false" class="size-8 rounded-lg bg-zinc-100 text-zinc-600 flex items-center justify-center">
                    <flux:icon name="x-mark" class="size-4" />
                </button>
            </div>

            <nav class="flex flex-col gap-1 text-sm font-semibold">
                <a href="{{ route('home') }}" wire:navigate class="px-3.5 py-2.5 rounded-xl transition {{ $isHome ? 'bg-lime-50 text-[#2e5b18] font-bold' : 'text-zinc-700 hover:bg-zinc-50' }}">{{ __('Beranda') }}</a>
                <a href="{{ route('program') }}" wire:navigate class="px-3.5 py-2.5 rounded-xl transition {{ $isProgram ? 'bg-lime-50 text-[#2e5b18] font-bold' : 'text-zinc-700 hover:bg-zinc-50' }}">{{ __('Program') }}</a>
                <a href="{{ route('galeri') }}" wire:navigate class="px-3.5 py-2.5 rounded-xl transition {{ $isGaleri ? 'bg-lime-50 text-[#2e5b18] font-bold' : 'text-zinc-700 hover:bg-zinc-50' }}">{{ __('Galeri') }}</a>
                <a href="{{ route('about') }}" wire:navigate class="px-3.5 py-2.5 rounded-xl transition {{ $isAbout ? 'bg-lime-50 text-[#2e5b18] font-bold' : 'text-zinc-700 hover:bg-zinc-50' }}">{{ __('Tentang Kami') }}</a>
                <a href="{{ route('contact.show') }}" wire:navigate class="px-3.5 py-2.5 rounded-xl transition {{ $isContact ? 'bg-lime-50 text-[#2e5b18] font-bold' : 'text-zinc-700 hover:bg-zinc-50' }}">{{ __('Kontak') }}</a>
            </nav>
        </div>

        <div class="pt-6 border-t border-zinc-100 flex flex-col gap-3">
            @auth
                <a href="{{ route('dashboard') }}" wire:navigate class="w-full text-center rounded-full bg-[#2e5b18] py-3 text-xs font-bold text-white shadow">{{ __('Dashboard') }}</a>
            @else
                <a href="{{ route('contact.show') }}" wire:navigate class="w-full text-center rounded-full bg-[#536dfe] py-3 text-xs font-bold text-white shadow-md shadow-indigo-500/20">{{ __('Contact Us') }}</a>
                <a href="{{ route('login') }}" wire:navigate class="w-full text-center rounded-full border border-zinc-200 py-2.5 text-xs font-semibold text-zinc-700">{{ __('Login') }}</a>
            @endauth
        </div>
    </div>
</header>
