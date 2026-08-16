<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        @php
            $userRole = auth()->user()?->role;
            $isAdmin = $userRole === \App\Enums\UserRole::Admin;
            $isOperator = $userRole === \App\Enums\UserRole::Operator;
            $isGuru = $userRole === \App\Enums\UserRole::Guru;
            $isSantri = $userRole === \App\Enums\UserRole::Santri;

            $setting = \App\Models\Setting::query()->first();
            $isMasterDataActive = request()->routeIs('akademik.tahun-akademik') || request()->routeIs('akademik.kelas') || request()->routeIs('akademik.mapel') || request()->routeIs('kepegawaian.guru') || request()->routeIs('kesantrian.santri');
            $isAkademikActive = request()->routeIs('akademik.jadwal-pelajaran') || request()->routeIs('kesantrian.absensi') || request()->routeIs('kepegawaian.absensi') || request()->routeIs('keuangan.*') || request()->routeIs('kepegawaian.gaji') || request()->routeIs('kesantrian.nilai') || request()->routeIs('akademik.setting-rapor');
            $isKontenActive = request()->routeIs('konten.*');
            $isAdminActive = request()->routeIs('admin.*') || request()->routeIs('settings*') || request()->routeIs('profile.edit') || request()->routeIs('security.edit') || request()->routeIs('appearance.edit');
        @endphp
        <flux:sidebar sticky collapsible class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 flex flex-col justify-between h-screen overflow-hidden">
            {{-- Top Header Section --}}
            <div class="shrink-0">
                <flux:sidebar.header>
                    <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                    <flux:sidebar.collapse class="lg:hidden" />
                </flux:sidebar.header>
            </div>

            {{-- Middle Scrollable Nav --}}
            <flux:sidebar.nav class="flex-1 overflow-y-auto pr-1 py-2 space-y-1.5">
                {{-- 1. Platform (Dashboard) --}}
                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>

                @if (! $isSantri)
                    {{-- 2. Master Data --}}
                    @if ($setting?->hasModule('akademik'))
                        <flux:sidebar.group 
                            :heading="__('Master Data')" 
                            icon="circle-stack" 
                            expandable 
                            :expanded="$isMasterDataActive"
                        >
                            @if ($isAdmin || $isOperator)
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
                            @endif
                            
                            <flux:sidebar.item icon="users" :href="route('kesantrian.santri')" :current="request()->routeIs('kesantrian.santri')" wire:navigate>
                                {{ __('Data Santri') }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @endif

                    {{-- 3. Akademik & Operasional --}}
                    <flux:sidebar.group 
                        :heading="__('Akademik & Operasional')" 
                        icon="academic-cap" 
                        expandable 
                        :expanded="$isAkademikActive"
                    >
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

                        @if (($isAdmin || $isOperator) && $setting?->hasModule('spp'))
                            <flux:sidebar.item icon="banknotes" :href="route('keuangan.spp')" :current="request()->routeIs('keuangan.spp')" wire:navigate>
                                {{ __('Pembayaran SPP') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="sparkles" :href="route('keuangan.haflatul-imtihan')" :current="request()->routeIs('keuangan.haflatul-imtihan')" wire:navigate>
                                {{ __('Haflatul Imtihan') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="wallet" :href="route('keuangan.tabungan')" :current="request()->routeIs('keuangan.tabungan')" wire:navigate>
                                {{ __('Tabungan Santri') }}
                            </flux:sidebar.item>
                        @endif

                        @if (($isAdmin || $isOperator) && $setting?->hasModule('gaji_guru'))
                            <flux:sidebar.item icon="calculator" :href="route('kepegawaian.gaji')" :current="request()->routeIs('kepegawaian.gaji')" wire:navigate>
                                {{ __('Gaji Guru') }}
                            </flux:sidebar.item>
                        @endif

                        @if ($setting?->hasModule('nilai'))
                            <flux:sidebar.item icon="pencil-square" :href="route('kesantrian.nilai')" :current="request()->routeIs('kesantrian.nilai')" wire:navigate>
                                {{ __('Nilai Santri') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="document-text" :href="route('akademik.setting-rapor')" :current="request()->routeIs('akademik.setting-rapor')" wire:navigate>
                                {{ __('Setting Rapor') }}
                            </flux:sidebar.item>
                        @endif
                    </flux:sidebar.group>
                @endif

                @if ($isAdmin)
                    {{-- 4. Konten Website --}}
                    @if ($setting?->hasModule('konten'))
                        <flux:sidebar.group 
                            :heading="__('Konten Website')" 
                            icon="newspaper" 
                            expandable 
                            :expanded="$isKontenActive"
                        >
                            <flux:sidebar.item icon="photo" :href="route('konten.galeri')" :current="request()->routeIs('konten.galeri')" wire:navigate>
                                {{ __('Galeri') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="envelope" :href="route('konten.pesan')" :current="request()->routeIs('konten.pesan')" wire:navigate>
                                {{ __('Pesan Masuk') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="at-symbol" :href="route('konten.langganan')" :current="request()->routeIs('konten.langganan')" wire:navigate>
                                {{ __('Langganan') }}
                            </flux:sidebar.item>
                        </flux:sidebar.group>
                    @endif

                    {{-- 5. Administrasi & Sistem --}}
                    <flux:sidebar.group 
                        :heading="__('Administrasi & Sistem')" 
                        icon="cog-6-tooth" 
                        expandable 
                        :expanded="$isAdminActive"
                    >
                        @if ($setting?->is_multi_lembaga)
                            <flux:sidebar.item icon="building-office-2" :href="route('admin.lembagas')" :current="request()->routeIs('admin.lembagas')" wire:navigate>
                                {{ __('Lembaga') }}
                            </flux:sidebar.item>
                        @endif
                            <flux:sidebar.item icon="key" :href="route('admin.roles')" :current="request()->routeIs('admin.roles')" wire:navigate>
                                {{ __('Peran & Izin') }}
                            </flux:sidebar.item>
                        <flux:sidebar.item icon="shield-check" :href="route('admin.users')" :current="request()->routeIs('admin.users')" wire:navigate>
                            {{ __('Pengguna') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="chat-bubble-left-right" :href="route('admin.whatsapp')" :current="request()->routeIs('admin.whatsapp')" wire:navigate>
                            {{ __('WhatsApp') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="adjustments-horizontal" :href="route('admin.settings')" :current="request()->routeIs('admin.settings') || request()->routeIs('settings*')" wire:navigate>
                            {{ __('Pengaturan') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                @endif
            </flux:sidebar.nav>
        </flux:sidebar>

        {{-- Unified Responsive Top Header for Desktop and Mobile --}}
        <flux:header sticky class="border-b border-zinc-200/80 dark:border-zinc-700/80 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md px-3 sm:px-6 lg:px-8">
            <livewire:admin.top-bar />
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
