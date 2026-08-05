<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        @php
            $setting = \App\Models\Setting::query()->first();
        @endphp
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            @if ($setting?->is_multi_lembaga)
                <div class="px-2 py-1.5">
                    <livewire:admin.lembaga-switcher />
                </div>
            @endif

            <flux:sidebar.nav>
                {{-- 1. Platform --}}
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                {{-- 2. Master Data --}}
                @if ($setting?->hasModule('akademik'))
                    <flux:sidebar.group :heading="__('Master Data')" class="grid">
                        <flux:sidebar.item icon="calendar-days" :href="route('akademik.tahun-akademik')" :current="request()->routeIs('akademik.tahun-akademik')" wire:navigate>
                            {{ __('Tahun Akademik') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="rectangle-group" :href="route('akademik.kelas')" :current="request()->routeIs('akademik.kelas')" wire:navigate>
                            {{ __('Kelas') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="book-open" :href="route('akademik.mapel')" :current="request()->routeIs('akademik.mapel')" wire:navigate>
                            {{ __('Mata Pelajaran') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="user-circle" :href="route('kepegawaian.guru')" :current="request()->routeIs('kepegawaian.guru')" wire:navigate>
                            {{ __('Data Guru') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="users" :href="route('kesantrian.santri')" :current="request()->routeIs('kesantrian.santri')" wire:navigate>
                            {{ __('Data Santri') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                @endif

                {{-- 3. Akademik, Keuangan & Operasional --}}
                <flux:sidebar.group :heading="__('Akademik & Operasional')" class="grid">
                    @if ($setting?->hasModule('jadwal_absensi'))
                        <flux:sidebar.item icon="clock" :href="route('akademik.jadwal-pelajaran')" :current="request()->routeIs('akademik.jadwal-pelajaran')" wire:navigate>
                            {{ __('Jadwal Pelajaran') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="clipboard-document-check" :href="route('kesantrian.absensi')" :current="request()->routeIs('kesantrian.absensi')" wire:navigate>
                            {{ __('Absensi Santri') }}
                        </flux:sidebar.item>
                    @endif

                    @if ($setting?->hasModule('absensi_guru'))
                        <flux:sidebar.item icon="clipboard-document-check" :href="route('kepegawaian.absensi')" :current="request()->routeIs('kepegawaian.absensi')" wire:navigate>
                            {{ __('Absensi Guru') }}
                        </flux:sidebar.item>
                    @endif

                    @if ($setting?->hasModule('spp'))
                        <flux:sidebar.item icon="banknotes" :href="route('keuangan.spp')" :current="request()->routeIs('keuangan.spp')" wire:navigate>
                            {{ __('Pembayaran SPP') }}
                        </flux:sidebar.item>
                    @endif

                    @if ($setting?->hasModule('gaji_guru'))
                        <flux:sidebar.item icon="calculator" :href="route('kepegawaian.gaji')" :current="request()->routeIs('kepegawaian.gaji')" wire:navigate>
                            {{ __('Gaji Guru') }}
                        </flux:sidebar.item>
                    @endif

                    @if ($setting?->hasModule('nilai'))
                        <flux:sidebar.item icon="pencil-square" :href="route('kesantrian.nilai')" :current="request()->routeIs('kesantrian.nilai')" wire:navigate>
                            {{ __('Nilai Santri') }}
                        </flux:sidebar.item>
                    @endif
                </flux:sidebar.group>

                @if (auth()->user()->role === \App\Enums\UserRole::Admin)
                    {{-- 4. Konten --}}
                    @if ($setting?->hasModule('konten'))
                        <flux:sidebar.group :heading="__('Konten')" class="grid">
                            <flux:sidebar.item icon="photo" :href="route('konten.galeri')" :current="request()->routeIs('konten.galeri')" wire:navigate>
                                {{ __('Galeri') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="envelope" :href="route('konten.pesan')" :current="request()->routeIs('konten.pesan')" wire:navigate>
                                {{ __('Pesan Masuk') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="at-symbol" :href="route('konten.langganan')" :current="request()->routeIs('konten.langganan')" wire:navigate>
                                {{ __('Langganan Newsletter') }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @endif

                    {{-- 5. Administrasi --}}
                    <flux:sidebar.group :heading="__('Administrasi')" class="grid">
                        @if ($setting?->is_multi_lembaga)
                            <flux:sidebar.item icon="building-office-2" :href="route('admin.lembagas')" :current="request()->routeIs('admin.lembagas')" wire:navigate>
                                {{ __('Lembaga Pendidikan') }}
                            </flux:sidebar.item>
                        @endif
                        <flux:sidebar.item icon="shield-check" :href="route('admin.users')" :current="request()->routeIs('admin.users')" wire:navigate>
                            {{ __('Manajemen Pengguna') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="chat-bubble-left-right" :href="route('admin.whatsapp')" :current="request()->routeIs('admin.whatsapp')" wire:navigate>
                            {{ __('WhatsApp Broadcast') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="cog-6-tooth" :href="route('admin.settings')" :current="request()->routeIs('admin.settings')" wire:navigate>
                            {{ __('Pengaturan Aplikasi') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                @endif
            </flux:sidebar.nav>

            <flux:spacer />

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
