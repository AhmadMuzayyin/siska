@props(['setting' => null])

@php
    $isHome = request()->routeIs('home');
    $isProgram = request()->routeIs('program');
    $isGaleri = request()->routeIs('galeri');
    $isAbout = request()->routeIs('about');
    $isContact = request()->routeIs('contact.show');
@endphp

<header class="sticky top-0 z-30 border-b border-emerald-600/20 bg-[#edf7f4]/90 backdrop-blur-md dark:border-emerald-600/30 dark:bg-[#021d16]/90">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-3">
        <x-app-logo :href="route('home')" :name="$setting?->lembaga ?? config('app.name')" wire:navigate class="shrink-0" />

        <flux:navbar class="hidden sm:flex" aria-label="{{ __('Navigasi utama') }}">
            <flux:navbar.item :href="route('home')" :current="$isHome" wire:navigate>{{ __('Beranda') }}</flux:navbar.item>
            <flux:navbar.item :href="route('program')" :current="$isProgram" wire:navigate>{{ __('Program') }}</flux:navbar.item>
            <flux:navbar.item :href="route('galeri')" :current="$isGaleri" wire:navigate>{{ __('Galeri') }}</flux:navbar.item>
            <flux:navbar.item :href="route('about')" :current="$isAbout" wire:navigate>{{ __('Tentang Kami') }}</flux:navbar.item>
            <flux:navbar.item :href="route('contact.show')" :current="$isContact" wire:navigate>{{ __('Kontak') }}</flux:navbar.item>
        </flux:navbar>

        <div class="hidden items-center gap-3 sm:flex">
            @auth
                <flux:button variant="primary" class="bg-emerald-700! hover:bg-emerald-800! text-white!" :href="route('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:button>
            @else
                <flux:button variant="ghost" :href="route('login')" wire:navigate class="hover:bg-emerald-500/10 dark:hover:bg-emerald-500/20">{{ __('Masuk') }}</flux:button>
                <flux:button variant="primary" class="bg-emerald-700! hover:bg-emerald-800! text-white! font-semibold shadow-md shadow-emerald-700/20 border border-emerald-500/30" :href="route('santri.register.form')" wire:navigate>
                    <flux:icon name="user-plus" class="size-4 me-1.5 text-emerald-100" />
                    {{ __('Daftar Santri Baru') }}
                </flux:button>
            @endauth
        </div>

        <details class="relative sm:hidden">
            <summary class="flex size-9 cursor-pointer list-none items-center justify-center rounded-lg border border-emerald-600/30 bg-[#dcf0ea] text-emerald-950 shadow-xs dark:border-emerald-600/30 dark:bg-emerald-950 dark:text-emerald-100">
                <flux:icon name="bars-3" class="size-5" />
                <span class="sr-only">{{ __('Buka menu') }}</span>
            </summary>

            <div class="absolute right-0 z-30 mt-2 w-60 rounded-2xl border border-emerald-600/30 bg-[#edf7f4]/98 p-3 shadow-2xl backdrop-blur-md dark:border-emerald-600/30 dark:bg-[#021d16]/98">
                <nav class="flex flex-col gap-1" aria-label="{{ __('Navigasi mobile') }}">
                    <flux:navbar.item :href="route('home')" :current="$isHome" wire:navigate>{{ __('Beranda') }}</flux:navbar.item>
                    <flux:navbar.item :href="route('program')" :current="$isProgram" wire:navigate>{{ __('Program') }}</flux:navbar.item>
                    <flux:navbar.item :href="route('galeri')" :current="$isGaleri" wire:navigate>{{ __('Galeri') }}</flux:navbar.item>
                    <flux:navbar.item :href="route('about')" :current="$isAbout" wire:navigate>{{ __('Tentang Kami') }}</flux:navbar.item>
                    <flux:navbar.item :href="route('contact.show')" :current="$isContact" wire:navigate>{{ __('Kontak') }}</flux:navbar.item>
                </nav>

                <flux:separator class="my-3" />

                <div class="flex flex-col gap-2">
                    @auth
                        <flux:button variant="primary" class="bg-emerald-700! hover:bg-emerald-800! text-white!" :href="route('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:button>
                    @else
                        <flux:button variant="ghost" :href="route('login')" wire:navigate>{{ __('Masuk') }}</flux:button>
                        <flux:button variant="primary" class="bg-emerald-700! hover:bg-emerald-800! text-white!" :href="route('santri.register.form')" wire:navigate>{{ __('Daftar Santri Baru') }}</flux:button>
                    @endauth
                </div>
            </div>
        </details>
    </div>
</header>
