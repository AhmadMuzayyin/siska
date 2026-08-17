<div class="w-full max-w-5xl space-y-6">
    {{-- Page Header --}}
    <div>
        <flux:heading size="xl" level="1">{{ __('Pengaturan') }}</flux:heading>
        <flux:subheading size="lg">{{ __('Kelola seluruh konfigurasi aplikasi, tampilan, profil akun, dan keamanan dalam satu halaman.') }}</flux:subheading>
    </div>

    {{-- Clean Top Tabs Navigation Bar --}}
    <div class="border-b border-zinc-200 dark:border-zinc-700">
        <nav class="-mb-px flex space-x-2 sm:space-x-4 overflow-x-auto pb-1" aria-label="Tabs">
            @if ($isAdmin)
                <button
                    type="button"
                    wire:click="$set('tab', 'general')"
                    class="group inline-flex items-center gap-2 border-b-2 py-3 px-3 sm:px-4 text-xs sm:text-sm font-bold transition-all cursor-pointer whitespace-nowrap {{ $tab === 'general' ? 'border-emerald-600 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400' : 'border-transparent text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200' }}"
                >
                    <flux:icon name="building-office-2" class="size-4 shrink-0" />
                    <span>{{ __('Informasi Lembaga & Sistem') }}</span>
                </button>

                <button
                    type="button"
                    wire:click="$set('tab', 'appearance')"
                    class="group inline-flex items-center gap-2 border-b-2 py-3 px-3 sm:px-4 text-xs sm:text-sm font-bold transition-all cursor-pointer whitespace-nowrap {{ $tab === 'appearance' ? 'border-emerald-600 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400' : 'border-transparent text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200' }}"
                >
                    <flux:icon name="paint-brush" class="size-4 shrink-0" />
                    <span>{{ __('Tampilan (Appearance)') }}</span>
                </button>
            @endif

            <button
                type="button"
                wire:click="$set('tab', 'profile')"
                class="group inline-flex items-center gap-2 border-b-2 py-3 px-3 sm:px-4 text-xs sm:text-sm font-bold transition-all cursor-pointer whitespace-nowrap {{ $tab === 'profile' ? 'border-emerald-600 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400' : 'border-transparent text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200' }}"
            >
                <flux:icon name="user" class="size-4 shrink-0" />
                <span>{{ __('Profil Akun') }}</span>
            </button>

            <button
                type="button"
                wire:click="$set('tab', 'security')"
                class="group inline-flex items-center gap-2 border-b-2 py-3 px-3 sm:px-4 text-xs sm:text-sm font-bold transition-all cursor-pointer whitespace-nowrap {{ $tab === 'security' ? 'border-emerald-600 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400' : 'border-transparent text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200' }}"
            >
                <flux:icon name="shield-check" class="size-4 shrink-0" />
                <span>{{ __('Keamanan & Sandi') }}</span>
            </button>
        </nav>
    </div>

    {{-- ================= TAB 1: INFORMASI LEMBAGA & SISTEM (ADMIN) ================= --}}
    @if ($tab === 'general' && $isAdmin)
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-xs">
            <form wire:submit="saveGeneral" class="flex flex-col gap-6 max-w-3xl">
                <div>
                    <flux:heading size="lg">{{ __('Informasi Pokok Lembaga') }}</flux:heading>
                    <flux:subheading>{{ __('Data identitas lembaga, logo/favicon, dan kontak operasional.') }}</flux:subheading>
                </div>

                {{-- Upload Logo & Favicon Section --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-zinc-800/40">
                    {{-- 1. Logo Lembaga --}}
                    <div class="space-y-3">
                        <flux:heading size="sm" level="3">{{ __('Logo Lembaga') }}</flux:heading>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">
                            {{ __('Format: PNG, JPG, JPEG, SVG, WebP. Maksimal 2MB. Logo akan ditampilkan pada header, sidebar, dan cetakan dokumen.') }}
                        </p>

                        <div class="flex items-center gap-4">
                            <div class="size-16 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2 flex items-center justify-center shrink-0 overflow-hidden shadow-2xs">
                                @if ($logo_upload)
                                    <img src="{{ $logo_upload->temporaryUrl() }}" alt="Preview Logo" class="max-h-full max-w-full object-contain" />
                                @elseif ($setting?->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($setting->logo))
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($setting->logo) }}" alt="Logo Lembaga" class="max-h-full max-w-full object-contain" />
                                @else
                                    <x-app-logo-icon class="size-8 text-emerald-600 dark:text-emerald-400 fill-current" />
                                @endif
                            </div>

                            <div class="flex-1 space-y-2">
                                <flux:input type="file" wire:model="logo_upload" accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp" size="sm" />
                                @error('logo_upload') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror

                                @if ($setting?->logo)
                                    <flux:button type="button" wire:click="removeLogo" variant="danger" size="xs" icon="trash">
                                        {{ __('Hapus Logo') }}
                                    </flux:button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- 2. Favicon Lembaga --}}
                    <div class="space-y-3">
                        <flux:heading size="sm" level="3">{{ __('Favicon Browser') }}</flux:heading>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">
                            {{ __('Format: ICO, PNG, SVG. Maksimal 1MB. Icon yang muncul pada tab browser.') }}
                        </p>

                        <div class="flex items-center gap-4">
                            <div class="size-16 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2 flex items-center justify-center shrink-0 overflow-hidden shadow-2xs">
                                @if ($favicon_upload)
                                    <img src="{{ $favicon_upload->temporaryUrl() }}" alt="Preview Favicon" class="size-8 object-contain" />
                                @elseif ($setting?->favicon && \Illuminate\Support\Facades\Storage::disk('public')->exists($setting->favicon))
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($setting->favicon) }}" alt="Favicon Lembaga" class="size-8 object-contain" />
                                @else
                                    <img src="/favicon.ico" alt="Default Favicon" class="size-8 object-contain" />
                                @endif
                            </div>

                            <div class="flex-1 space-y-2">
                                <flux:input type="file" wire:model="favicon_upload" accept=".ico,image/png,image/jpeg,image/svg+xml" size="sm" />
                                @error('favicon_upload') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror

                                @if ($setting?->favicon)
                                    <flux:button type="button" wire:click="removeFavicon" variant="danger" size="xs" icon="trash">
                                        {{ __('Hapus Favicon') }}
                                    </flux:button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <flux:input wire:model="lembaga" :label="__('Nama Lembaga')" required />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input wire:model="nsm" :label="__('NSM / Nomor Statistik (opsional)')" />
                    <flux:input wire:model="telepon" :label="__('Nomor Telepon / Hotline')" required />
                </div>

                <flux:input wire:model="email_lembaga" type="email" :label="__('Email Lembaga')" required />
                <flux:textarea wire:model="alamat" :label="__('Alamat Lengkap Lembaga')" rows="2" required />
                
                <flux:input 
                    wire:model="google_maps_url" 
                    type="url" 
                    :label="__('URL Google Maps Embed')" 
                    placeholder="https://www.google.com/maps/embed?pb=..." 
                    description="{{ __('Salin URL embed dari Google Maps → Bagikan → Sematkan peta') }}" 
                />

                <flux:separator />

                <div>
                    <flux:heading size="md">{{ __('Search Engine Optimization (SEO)') }}</flux:heading>
                    <flux:subheading>{{ __('Optimasi pencarian Google untuk halaman publik lembaga.') }}</flux:subheading>
                </div>
                <flux:textarea wire:model="meta_deskripsi" :label="__('Meta Deskripsi')" rows="2" />
                <flux:input wire:model="meta_keyword" :label="__('Meta Keyword')" placeholder="pesantren, madrasah, tahfidz, spmb" />

                <flux:separator />

                <div>
                    <flux:heading size="md">{{ __('Penggajian Guru & Karyawan') }}</flux:heading>
                    <flux:subheading>{{ __('Aturan tanggal cutoff rekapitulasi penggajian bulanan.') }}</flux:subheading>
                </div>
                <div class="max-w-xs">
                    <flux:input wire:model="payroll_cutoff_day" type="number" min="1" max="31" :label="__('Tanggal Cutoff Penggajian')" required />
                </div>

                <flux:separator />

                <div>
                    <flux:heading size="md">{{ __('Notifikasi WhatsApp Otomatis') }}</flux:heading>
                    <flux:subheading>{{ __('Template pesan broadcast dan notifikasi ke wali santri.') }}</flux:subheading>
                </div>
                <flux:switch wire:model="fitur_pesan_whatsapp" :label="__('Aktifkan modul pesan WhatsApp')" />
                <flux:textarea wire:model="pesan_whatsapp" :label="__('Template Pesan Default')" rows="3" />

                <div class="flex justify-end pt-4 border-t border-zinc-100 dark:border-zinc-800">
                    <flux:button type="submit" variant="primary" size="base">
                        {{ __('Simpan Pengaturan Lembaga') }}
                    </flux:button>
                </div>
            </form>
        </div>
    @endif

    {{-- ================= TAB 2: TAMPILAN (APPEARANCE) ================= --}}
    @if ($tab === 'appearance' && $isAdmin)
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-xs space-y-8 max-w-4xl">
            {{-- 1. Mode Tampilan Aplikasi --}}
            <div class="space-y-4">
                <div>
                    <flux:heading size="lg">{{ __('Mode Tampilan Dashboard') }}</flux:heading>
                    <flux:subheading>{{ __('Pilih mode tema terang (light), gelap (dark), atau mengikuti preferensi sistem.') }}</flux:subheading>
                </div>

                <div class="max-w-md">
                    <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                        <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                        <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                        <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
                    </flux:radio.group>
                </div>
            </div>

            <flux:separator />

            {{-- 2. Pengaturan Tema Website / Landing Page (Khusus Admin) --}}
            <div class="space-y-5">
                <div>
                    <div class="flex items-center gap-2">
                        <flux:heading size="lg">{{ __('Tema Website Publik & Landing Page') }}</flux:heading>
                        <span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/60 px-2 py-0.5 text-[10px] font-bold text-emerald-800 dark:text-emerald-300">
                            {{ __('Admin Only') }}
                        </span>
                    </div>
                    <flux:subheading>{{ __('Pilih desain tema aktif untuk halaman utama, program, galeri, kontak, dan login publik.') }}</flux:subheading>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Opsi 1: Tema Default (Klasik Emerald) --}}
                    <label class="relative flex flex-col p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 {{ $landing_theme === 'default' ? 'border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/20 dark:border-emerald-500 shadow-md ring-2 ring-emerald-500/20' : 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="size-3 rounded-full bg-emerald-500"></span>
                                <span class="font-bold text-sm text-zinc-900 dark:text-zinc-100">{{ __('Tema Default') }}</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">{{ __('Klasik Emerald') }}</span>
                            </div>
                            <input type="radio" wire:model.live="landing_theme" value="default" class="text-emerald-600 focus:ring-emerald-500 size-4">
                        </div>
                        
                        {{-- Mini Preview Box --}}
                        <div class="h-28 w-full rounded-xl bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-950 p-3 flex flex-col justify-between overflow-hidden shadow-inner border border-emerald-700/50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <span class="size-2 rounded-full bg-emerald-400"></span>
                                    <span class="h-1.5 w-14 bg-white/50 rounded"></span>
                                </div>
                                <span class="h-2 w-8 bg-emerald-400/80 rounded-full"></span>
                            </div>
                            <div class="space-y-1">
                                <div class="h-2.5 w-32 bg-white/90 rounded font-semibold text-[8px] text-white">Hero Slider & Statistik</div>
                                <div class="h-1.5 w-40 bg-white/40 rounded"></div>
                            </div>
                            <div class="flex gap-1.5">
                                <div class="h-4 w-12 bg-emerald-500/70 rounded-md"></div>
                                <div class="h-4 w-12 bg-white/20 rounded-md"></div>
                            </div>
                        </div>

                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-3 leading-relaxed">
                            {{ __('Desain klasik pesantren nuansa hijau emerald dengan hero slider interaktif, kartu program tebal, dan kartu login split.') }}
                        </p>
                    </label>

                    {{-- Opsi 2: Tema Pixigon (Soft Green Light) --}}
                    <label class="relative flex flex-col p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 {{ $landing_theme === 'pixigon' ? 'border-[#6bb82d] bg-[#f0f8ec]/60 dark:bg-lime-950/20 dark:border-[#6bb82d] shadow-md ring-2 ring-lime-500/20' : 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="size-3 rounded-full bg-[#6bb82d]"></span>
                                <span class="font-bold text-sm text-zinc-900 dark:text-zinc-100">{{ __('Tema Pixigon') }}</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-lime-100 text-lime-800 dark:bg-lime-900/60 dark:text-lime-300">{{ __('Soft Green Light') }}</span>
                            </div>
                            <input type="radio" wire:model.live="landing_theme" value="pixigon" class="text-[#6bb82d] focus:ring-[#6bb82d] size-4">
                        </div>

                        {{-- Mini Preview Box --}}
                        <div class="h-28 w-full rounded-xl bg-[#f0f8ec] p-3 flex flex-col justify-between overflow-hidden shadow-inner border border-[#d6eda6]">
                            <div class="flex items-center justify-between">
                                <span class="h-2 w-16 bg-[#2e5b18] rounded"></span>
                                <span class="h-2.5 w-10 bg-[#536dfe] rounded-full"></span>
                            </div>
                            <div class="text-center py-1">
                                <span class="text-[9px] font-extrabold text-[#2e5b18]">Ellipse Curve Hero & Botanical Doodles</span>
                            </div>
                            <div class="flex justify-around items-center">
                                <span class="size-4 rounded-full bg-white border border-[#d6eda6] shadow-2xs"></span>
                                <span class="size-4 rounded-full bg-white border border-[#d6eda6] shadow-2xs"></span>
                                <span class="size-4 rounded-full bg-white border border-[#d6eda6] shadow-2xs"></span>
                                <span class="size-4 rounded-full bg-white border border-[#d6eda6] shadow-2xs"></span>
                            </div>
                        </div>

                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-3 leading-relaxed">
                            {{ __('Desain modern academy nuansa soft green light dengan doodle botani, navbar seamless, kartu review miring, dan slider login & daftar.') }}
                        </p>
                    </label>
                </div>
            </div>
        </div>
    @endif

    {{-- ================= TAB 3: PROFIL PENGGUNA ================= --}}
    @if ($tab === 'profile')
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-xs space-y-6 max-w-2xl">
            <div>
                <flux:heading size="lg">{{ __('Informasi Akun Anda') }}</flux:heading>
                <flux:subheading>{{ __('Perbarui nama lengkap dan alamat email yang digunakan untuk masuk ke sistem.') }}</flux:subheading>
            </div>

            <form wire:submit="updateProfileInformation" class="space-y-5">
                <flux:input wire:model="name" :label="__('Nama Lengkap')" type="text" required autocomplete="name" />
                <flux:input wire:model="email" :label="__('Alamat Email')" type="email" required autocomplete="email" />

                <div class="pt-2">
                    <flux:button variant="primary" type="submit">{{ __('Simpan Perubahan Profil') }}</flux:button>
                </div>
            </form>

            <flux:separator />

            <div>
                <livewire:settings.delete-user-form />
            </div>
        </div>
    @endif

    {{-- ================= TAB 4: KEAMANAN & KATA SANDI ================= --}}
    @if ($tab === 'security')
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-xs space-y-8 max-w-2xl">
            {{-- 1. Update Password --}}
            <div class="space-y-5">
                <div>
                    <flux:heading size="lg">{{ __('Perbarui Kata Sandi') }}</flux:heading>
                    <flux:subheading>{{ __('Gunakan kata sandi yang kuat dan acak untuk menjaga keamanan akun Anda.') }}</flux:subheading>
                </div>

                <form wire:submit="updatePassword" class="space-y-4">
                    <flux:input
                        wire:model="current_password"
                        :label="__('Kata Sandi Saat Ini')"
                        type="password"
                        required
                        autocomplete="current-password"
                        viewable
                    />
                    <flux:input
                        wire:model="password"
                        :label="__('Kata Sandi Baru')"
                        type="password"
                        required
                        autocomplete="new-password"
                        passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                        viewable
                    />
                    <flux:input
                        wire:model="password_confirmation"
                        :label="__('Konfirmasi Kata Sandi Baru')"
                        type="password"
                        required
                        autocomplete="new-password"
                        passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                        viewable
                    />

                    <div class="pt-2">
                        <flux:button variant="primary" type="submit">{{ __('Simpan Kata Sandi') }}</flux:button>
                    </div>
                </form>
            </div>

            <flux:separator />

            {{-- 2. Two-Factor Authentication (2FA) --}}
            <div class="space-y-4">
                <div>
                    <flux:heading size="md">{{ __('Autentikasi Dua Faktor (2FA)') }}</flux:heading>
                    <flux:subheading>{{ __('Tambahkan lapisan keamanan ekstra dengan verifikasi kode OTP dari aplikasi autentikator.') }}</flux:subheading>
                </div>

                @if (auth()->user()->two_factor_confirmed_at)
                    <div class="space-y-4 p-4 rounded-xl bg-emerald-50/60 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800">
                        <flux:badge variant="solid" color="green" class="inline-flex items-center gap-1.5">
                            <flux:icon name="check-circle" class="size-3.5" />
                            <span>{{ __('Autentikasi Dua Faktor Aktif') }}</span>
                        </flux:badge>

                        <p class="text-xs text-zinc-600 dark:text-zinc-400">
                            {{ __('Akun Anda terlindungi dengan kode autentikator satu kali pakai.') }}
                        </p>

                        {{-- Recovery codes --}}
                        <details class="group">
                            <summary class="cursor-pointer text-xs font-semibold text-emerald-700 dark:text-emerald-400 hover:underline">
                                {{ __('Tampilkan Kode Pemulihan (Recovery Codes)') }}
                            </summary>
                            <div class="mt-2 p-3 bg-white dark:bg-zinc-800 rounded-lg font-mono text-xs space-y-1 border border-zinc-200 dark:border-zinc-700">
                                @foreach ((array) auth()->user()->recoveryCodes() as $code)
                                    <div>{{ $code }}</div>
                                @endforeach
                            </div>
                        </details>

                        <form id="disable-2fa-index-form" method="POST" action="/user/two-factor-authentication">
                            @csrf
                            @method('DELETE')
                            <flux:button size="sm" variant="danger" type="button" x-on:click="$flux.modal('confirm-disable-2fa-index-modal').show()">
                                {{ __('Nonaktifkan 2FA') }}
                            </flux:button>
                        </form>
                    </div>

                @elseif (session('status') === 'two-factor-authentication-enabled')
                    <div class="space-y-4 p-4 rounded-xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800">
                        <flux:callout variant="warning" icon="exclamation-triangle">
                            {{ __('Selesaikan konfigurasi 2FA dengan memindai kode QR di bawah ini.') }}
                        </flux:callout>

                        <div class="p-4 bg-white rounded-xl inline-block border border-zinc-200">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>

                        <p class="text-xs font-medium text-zinc-700 dark:text-zinc-300">
                            {{ __('Kunci Pengaturan (Setup Key):') }}
                            <span class="font-mono font-bold">{{ decrypt(auth()->user()->two_factor_secret) }}</span>
                        </p>

                        <form method="POST" action="/user/confirmed-two-factor-authentication" class="flex gap-3 items-end">
                            @csrf
                            <flux:input
                                name="code"
                                :label="__('Masukkan 6-digit kode dari aplikasi')"
                                type="text"
                                inputmode="numeric"
                                autocomplete="one-time-code"
                                placeholder="123456"
                                required
                            />
                            <flux:button variant="primary" type="submit">
                                {{ __('Konfirmasi') }}
                            </flux:button>
                        </form>
                    </div>
                @else
                    <div class="space-y-3">
                        <p class="text-xs text-zinc-600 dark:text-zinc-400">
                            {{ __('Autentikasi dua faktor belum aktif pada akun Anda. Aktifkan untuk mencegah akses tidak sah.') }}
                        </p>

                        <form method="POST" action="/user/two-factor-authentication">
                            @csrf
                            <flux:button size="sm" variant="filled" type="submit">
                                {{ __('Aktifkan Autentikasi Dua Faktor') }}
                            </flux:button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Confirm Disable 2FA Modal --}}
    <flux:modal name="confirm-disable-2fa-index-modal" class="md:w-96 space-y-6">
        <div class="space-y-2">
            <flux:heading size="lg">{{ __('Nonaktifkan 2FA') }}</flux:heading>
            <flux:subheading>{{ __('Apakah Anda yakin ingin menonaktifkan Two-Factor Authentication?') }}</flux:subheading>
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
            </flux:modal.close>

            <flux:button 
                variant="filled" 
                onclick="document.getElementById('disable-2fa-index-form').submit()"
                class="bg-rose-600! hover:bg-rose-700! text-white! font-bold"
            >
                {{ __('Ya, Nonaktifkan') }}
            </flux:button>
        </div>
    </flux:modal>
</div>
