<div class="flex items-center justify-between w-full gap-2 sm:gap-4">
    
    {{-- Left: Hamburger Toggle Menu Button (App logo stays in sidebar) --}}
    <div class="flex items-center">
        <flux:sidebar.toggle class="text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg p-1" icon="bars-2" inset="left" />
    </div>

    <flux:spacer />

    {{-- Right Tools (Lembaga Icon Dropdown + Notification Bell + Profile Dropdown) --}}
    <div class="flex items-center gap-2 sm:gap-3" x-data>
        
        {{-- 1. Unit Lembaga Switcher Icon (To the left of notification bell) --}}
        @if ($isMultiLembaga)
            <livewire:admin.lembaga-switcher />
        @endif

        {{-- 2. Notification Bell Button with Blue Badge (Matches Reference) --}}
        <flux:modal.trigger name="notifications-flyout">
            <button
                type="button"
                class="relative flex size-9 items-center justify-center rounded-full text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                title="{{ __('Notifikasi & Pemberitahuan') }}"
                aria-label="{{ __('Buka panel notifikasi') }}"
            >
                <flux:icon name="bell" class="size-5" />

                {{-- Blue Notification Badge --}}
                @if ($unreadCount > 0)
                    <span class="absolute top-0 right-0 flex size-4 min-w-4 px-1 items-center justify-center rounded-full bg-blue-600 text-[10px] font-black text-white shadow-xs">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </button>
        </flux:modal.trigger>

        {{-- 3. User Profile Dropdown (Circular Avatar, Green Online Dot & Right-Aligned Menu Icons) --}}
        <flux:dropdown position="bottom" align="end">
            <button
                type="button"
                class="flex items-center gap-2 p-0 bg-transparent border-0 shadow-none hover:opacity-80 transition-opacity cursor-pointer focus:outline-hidden"
                aria-label="{{ __('Menu Profil Pengguna') }}"
            >
                <div class="relative size-8 shrink-0">
                    <flux:avatar
                        circle
                        :name="auth()->user()->name"
                        :initials="auth()->user()->initials()"
                        class="size-8 rounded-full border-0! shadow-none!"
                    />
                    {{-- Active Green Dot Indicator --}}
                    <span class="absolute bottom-0 right-0 size-2.5 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-zinc-900"></span>
                </div>

                {{-- Desktop Email / Username Text (Matches Reference) --}}
                <span class="hidden md:inline-block text-xs font-semibold text-zinc-700 dark:text-zinc-300 max-w-[180px] truncate">
                    {{ auth()->user()->email }}
                </span>
            </button>

            <flux:menu class="w-56 py-1.5 shadow-xl rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-900">
                
                {{-- Edit Profile --}}
                <flux:menu.item :href="route('settings') . '?tab=profile'" wire:navigate class="flex items-center justify-between py-2 text-xs font-medium cursor-pointer">
                    <span>{{ __('Edit Profile') }}</span>
                    <flux:icon name="user-circle" class="size-4 text-zinc-400 ms-auto" />
                </flux:menu.item>

                {{-- Inbox (Khusus Admin) --}}
                @if (auth()->user()->role === \App\Enums\UserRole::Admin)
                    <flux:menu.item :href="route('konten.pesan')" wire:navigate class="flex items-center justify-between py-2 text-xs font-medium cursor-pointer">
                        <span>{{ __('Inbox') }}</span>
                        <flux:icon name="envelope" class="size-4 text-zinc-400 ms-auto" />
                    </flux:menu.item>
                @endif

                {{-- Mode Tampilan (Dark / Light) --}}
                <flux:menu.item
                    @click="$flux.appearance = $flux.appearance === 'dark' ? 'light' : 'dark'"
                    class="flex items-center justify-between py-2 text-xs font-medium cursor-pointer"
                >
                    <span x-text="$flux.appearance === 'dark' ? '{{ __('Mode Terang') }}' : '{{ __('Mode Gelap') }}'"></span>
                    <flux:icon name="sun" class="size-4 text-amber-400 ms-auto" x-show="$flux.appearance === 'dark'" style="display: none;" />
                    <flux:icon name="moon" class="size-4 text-zinc-400 ms-auto" x-show="$flux.appearance !== 'dark'" />
                </flux:menu.item>

                {{-- Setting --}}
                <flux:menu.item :href="route('settings')" wire:navigate class="flex items-center justify-between py-2 text-xs font-medium cursor-pointer">
                    <span>{{ __('Setting') }}</span>
                    <flux:icon name="wrench" class="size-4 text-zinc-400 ms-auto" />
                </flux:menu.item>

                <flux:menu.separator class="my-1" />

                {{-- Log Out --}}
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item
                        as="button"
                        type="submit"
                        class="w-full flex items-center justify-between py-2 text-xs font-medium cursor-pointer text-zinc-700 dark:text-zinc-200 hover:text-rose-600 dark:hover:text-rose-400"
                        data-test="logout-button"
                    >
                        <span>{{ __('Log Out') }}</span>
                        <flux:icon name="arrow-right-start-on-rectangle" class="size-4 text-zinc-400 ms-auto" />
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>

    </div>

    {{-- ================= SLIDE-OVER NOTIFICATION FLYOUT (RIGHT-TO-LEFT) ================= --}}
    <flux:modal name="notifications-flyout" flyout class="w-full sm:w-[28rem] space-y-6">
        {{-- Header Drawer --}}
        <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-4">
            <div class="flex items-center gap-2.5">
                <div class="size-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center justify-center font-bold">
                    <flux:icon name="bell" class="size-5" />
                </div>
                <div>
                    <flux:heading size="lg">{{ __('Notifikasi') }}</flux:heading>
                    <flux:subheading>{{ __('Pemberitahuan aktivitas sistem & permohonan.') }}</flux:subheading>
                </div>
            </div>
            @if ($unreadCount > 0)
                <button type="button" wire:click="markAllAsRead" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 hover:underline cursor-pointer shrink-0">
                    {{ __('Tandai Semua Dibaca') }}
                </button>
            @endif
        </div>

        {{-- Filter Pills --}}
        <div class="flex items-center gap-1.5 p-1 rounded-xl bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
            <button
                type="button"
                wire:click="$set('activeFilter', 'all')"
                class="flex-1 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer text-center {{ $activeFilter === 'all' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-2xs' : 'text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200' }}"
            >
                {{ __('Semua') }} ({{ $unreadCount }})
            </button>
            <button
                type="button"
                wire:click="$set('activeFilter', 'santri')"
                class="flex-1 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer text-center {{ $activeFilter === 'santri' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-2xs' : 'text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200' }}"
            >
                {{ __('Santri Baru') }} ({{ $pendingSantris->count() }})
            </button>
            <button
                type="button"
                wire:click="$set('activeFilter', 'kontak')"
                class="flex-1 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer text-center {{ $activeFilter === 'kontak' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-2xs' : 'text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200' }}"
            >
                {{ __('Pesan') }} ({{ $recentContacts->count() }})
            </button>
        </div>

        {{-- Notification Cards List --}}
        <div class="space-y-3 max-h-[calc(100vh-14rem)] overflow-y-auto pr-1">
            
            {{-- 1. Pendaftaran Calon Santri Baru --}}
            @if ($activeFilter === 'all' || $activeFilter === 'santri')
                @foreach ($pendingSantris as $santri)
                    <div class="p-3.5 rounded-2xl bg-amber-50/70 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/60 space-y-2 transition-all hover:shadow-xs">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="size-2 rounded-full bg-amber-500 shrink-0"></span>
                                <span class="text-xs font-bold text-zinc-900 dark:text-zinc-100">
                                    {{ $santri->nama_lengkap }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-zinc-500 dark:text-zinc-400 shrink-0">
                                    {{ $santri->created_at?->diffForHumans() }}
                                </span>
                                <button type="button" wire:click="markAsRead('santri', {{ $santri->id }})" title="{{ __('Tandai Dibaca') }}" class="text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 p-0.5 rounded cursor-pointer">
                                    <flux:icon name="check" class="size-3.5" />
                                </button>
                            </div>
                        </div>

                        <p class="text-xs text-zinc-600 dark:text-zinc-300 leading-relaxed">
                            {{ __('Mendaftar di unit') }} 
                            <strong class="text-zinc-900 dark:text-zinc-100">{{ $santri->lembaga?->nama ?? 'Lembaga' }}</strong>
                            @if ($santri->kelas) &bull; {{ $santri->kelas->nama }} @endif
                            ({{ __('Wali') }}: {{ $santri->telepon_wali }})
                        </p>

                        <div class="flex items-center justify-between pt-1 border-t border-amber-200/50 dark:border-amber-800/40">
                            <span class="inline-flex items-center rounded-md bg-amber-100 dark:bg-amber-900/50 px-2 py-0.5 text-[10px] font-bold text-amber-800 dark:text-amber-300">
                                {{ __('Menunggu Persetujuan') }}
                            </span>

                            <a
                                href="{{ route('kesantrian.santri') }}"
                                wire:click="markAsRead('santri', {{ $santri->id }})"
                                wire:navigate
                                class="text-xs font-bold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 hover:underline flex items-center gap-1"
                            >
                                <span>{{ __('Verifikasi') }}</span>
                                <flux:icon name="arrow-right" class="size-3" />
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- 2. Pesan Masuk Publik --}}
            @if ($activeFilter === 'all' || $activeFilter === 'kontak')
                @foreach ($recentContacts as $contact)
                    <div class="p-3.5 rounded-2xl bg-blue-50/70 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-800/60 space-y-2 transition-all hover:shadow-xs">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="size-2 rounded-full bg-blue-500 shrink-0"></span>
                                <span class="text-xs font-bold text-zinc-900 dark:text-zinc-100 truncate">
                                    {{ $contact->name ?? ($contact->nama ?? 'Pengirim') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-zinc-500 dark:text-zinc-400 shrink-0">
                                    {{ $contact->created_at?->diffForHumans() }}
                                </span>
                                <button type="button" wire:click="markAsRead('contact', {{ $contact->id }})" title="{{ __('Tandai Dibaca') }}" class="text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 p-0.5 rounded cursor-pointer">
                                    <flux:icon name="check" class="size-3.5" />
                                </button>
                            </div>
                        </div>

                        <p class="text-xs text-zinc-600 dark:text-zinc-300 line-clamp-2">
                            <strong>{{ $contact->subject ?? ($contact->subjek ?? 'Pesan') }}:</strong>
                            {{ $contact->message ?? ($contact->pesan ?? '') }}
                        </p>

                        <div class="flex items-center justify-between pt-1 border-t border-blue-200/50 dark:border-blue-800/40">
                            <span class="inline-flex items-center rounded-md bg-blue-100 dark:bg-blue-900/50 px-2 py-0.5 text-[10px] font-bold text-blue-800 dark:text-blue-300">
                                {{ __('Pesan Masuk') }}
                            </span>

                            <a
                                href="{{ route('konten.pesan') }}"
                                wire:click="markAsRead('contact', {{ $contact->id }})"
                                wire:navigate
                                class="text-xs font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 hover:underline flex items-center gap-1"
                            >
                                <span>{{ __('Buka Pesan') }}</span>
                                <flux:icon name="arrow-right" class="size-3" />
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Empty State --}}
            @if ($unreadCount === 0 || ($activeFilter === 'santri' && $pendingSantris->isEmpty()) || ($activeFilter === 'kontak' && $recentContacts->isEmpty()))
                <div class="py-12 px-4 text-center flex flex-col items-center justify-center space-y-3">
                    <div class="size-12 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <flux:icon name="check-circle" class="size-7" />
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-zinc-100">
                            {{ __('Tidak Ada Notifikasi Baru') }}
                        </h4>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 max-w-xs">
                            {{ __('Seluruh data santri dan pesan kontak masuk telah ditinjau dan mutakhir.') }}
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </flux:modal>
</div>
