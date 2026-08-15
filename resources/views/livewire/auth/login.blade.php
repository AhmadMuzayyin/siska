<div>
@if ($theme === 'pixigon')
    {{-- ================= PIXIGON THEME: LIVEWIRE MODERN SLIDER AUTH ================= --}}
    <div class="min-h-screen bg-[#f0f8ec] text-zinc-800 antialiased font-sans flex flex-col justify-between selection:bg-[#6bb82d] selection:text-white">
        
        {{-- Top Minimal Nav --}}
        <header class="w-full py-6 px-6 sm:px-12 flex items-center justify-between z-20">
            <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2.5 group">
                <div class="size-9 rounded-xl bg-[#6bb82d] text-white flex items-center justify-center font-black text-lg shadow-sm group-hover:scale-105 transition-transform">
                    <svg class="size-5 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2L15 9H22L16.5 13.5L18.5 20.5L12 16L5.5 20.5L7.5 13.5L2 9H9L12 2Z"/>
                    </svg>
                </div>
                <span class="font-extrabold text-xl tracking-tight text-[#2e5b18] uppercase">
                    {{ $lembagaName }}
                </span>
            </a>

            <a 
                href="{{ route('home') }}" 
                wire:navigate 
                class="inline-flex items-center gap-2 rounded-full border border-[#d6eda6] bg-white/80 hover:bg-white text-[#2e5b18] font-bold text-xs px-5 py-2 shadow-2xs transition-all"
            >
                <flux:icon name="arrow-left" class="size-3.5" />
                <span>{{ __('Kembali ke Beranda') }}</span>
            </a>
        </header>

        {{-- Main Slider Auth Container --}}
        <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-10 relative overflow-hidden">
            
            {{-- Decorative Botanical Doodles --}}
            <div class="hidden xl:block absolute left-8 top-1/4 pointer-events-none opacity-30 text-emerald-800">
                <svg class="w-28 h-36" viewBox="0 0 120 160" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M60 150 C60 110, 40 70, 20 20" />
                    <path d="M45 90 C30 80, 20 85, 25 70 C30 55, 45 70, 45 90 Z" fill="currentColor" fill-opacity="0.1" />
                    <path d="M52 115 C68 105, 78 110, 73 95 C68 80, 52 95, 52 115 Z" fill="currentColor" fill-opacity="0.1" />
                </svg>
            </div>

            <div class="hidden xl:block absolute right-12 bottom-16 pointer-events-none text-zinc-400">
                <svg class="w-12 h-14" viewBox="0 0 50 60" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M5 10 C20 5, 35 15, 30 30 C25 45, 10 35, 20 20 C30 5, 40 25, 45 40"/>
                </svg>
            </div>

            <div 
                x-data="{ showPassword: false }"
                class="w-full max-w-5xl rounded-3xl bg-white border border-[#d6eda6] shadow-2xl shadow-emerald-900/5 overflow-hidden grid grid-cols-1 lg:grid-cols-12 relative z-10"
            >
                {{-- Left Showcase Column (Desktop Only) --}}
                <div class="hidden lg:flex lg:col-span-5 relative bg-[#183207] text-white p-10 flex-col justify-between overflow-hidden">
                    <img 
                        src="https://images.unsplash.com/photo-1585036156171-384164a8c675?w=800&auto=format&fit=crop&q=80" 
                        alt="Santri Belajar" 
                        class="absolute inset-0 size-full object-cover opacity-25"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-[#122604] via-[#183207]/80 to-transparent"></div>

                    {{-- Top Tag --}}
                    <div class="relative z-10 space-y-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#7cb342]/20 border border-[#7cb342]/40 px-3.5 py-1 text-xs font-bold text-lime-300">
                            <span>✦</span> {{ __('Portal Sistem Informasi Santri') }}
                        </span>
                        <h3 class="text-2xl font-extrabold text-white leading-tight">
                            {{ $lembagaName }}
                        </h3>
                    </div>

                    {{-- Middle Quote --}}
                    <div class="relative z-10 my-auto py-8">
                        <blockquote class="space-y-3">
                            <p class="text-sm text-zinc-200 leading-relaxed italic">
                                "Barangsiapa menempuh jalan untuk menuntut ilmu, maka Allah akan mudahkan baginya jalan menuju surga."
                            </p>
                            <footer class="text-xs text-lime-400 font-bold uppercase tracking-wider">
                                &mdash; HR. Muslim
                            </footer>
                        </blockquote>

                        <div class="grid grid-cols-2 gap-2 mt-6 pt-6 border-t border-white/10 text-xs text-zinc-300">
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-lime-400" />
                                <span>{{ __('Metode Tilawati') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-lime-400" />
                                <span>{{ __('Presensi Realtime') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-lime-400" />
                                <span>{{ __('Mutaba\'ah Hafalan') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-lime-400" />
                                <span>{{ __('Laporan Keuangan') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Footer Note --}}
                    <div class="relative z-10 text-[11px] text-zinc-400">
                        &copy; {{ date('Y') }} {{ $lembagaName }}. Seluruh hak cipta dilindungi.
                    </div>
                </div>

                {{-- Right Interactive Slider Form Column --}}
                <div class="col-span-12 lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center">
                    
                    {{-- Animated Slider Tab Switcher (Login vs Daftar) --}}
                    <div class="p-1 rounded-full bg-[#f0f8ec] border border-[#d6eda6] flex items-center mb-8 max-w-md mx-auto w-full relative shadow-inner">
                        <button 
                            type="button" 
                            wire:click="$set('mode', 'login')"
                            class="flex-1 py-2.5 rounded-full text-xs font-bold transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer {{ $mode === 'login' ? 'bg-[#6bb82d] text-white shadow-md' : 'text-zinc-600 hover:text-zinc-900' }}"
                        >
                            <flux:icon name="arrow-right-end-on-rectangle" class="size-4" />
                            <span>{{ __('Masuk Portal') }}</span>
                        </button>

                        <button 
                            type="button" 
                            wire:click="$set('mode', 'register')"
                            class="flex-1 py-2.5 rounded-full text-xs font-bold transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer {{ $mode === 'register' ? 'bg-[#6bb82d] text-white shadow-md' : 'text-zinc-600 hover:text-zinc-900' }}"
                        >
                            <flux:icon name="user-plus" class="size-4" />
                            <span>{{ __('Pendaftaran Santri') }}</span>
                        </button>
                    </div>

                    {{-- Slide 1: Livewire Login Form --}}
                    @if ($mode === 'login')
                        <div class="space-y-6">
                            <div>
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#2e5b18] tracking-tight">
                                    {{ __('Selamat Datang!') }}
                                </h2>
                                <p class="text-xs sm:text-sm text-zinc-600 mt-1">
                                    {{ __('Masukkan email dan password akun Anda untuk masuk ke sistem.') }}
                                </p>
                            </div>

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="rounded-2xl bg-[#f0f8ec] border border-[#6bb82d] p-3.5 text-xs font-semibold text-[#2e5b18] flex items-center gap-2.5">
                                    <flux:icon name="check-circle" class="size-5 text-[#6bb82d] shrink-0" />
                                    <span>{{ session('status') }}</span>
                                </div>
                            @endif

                            <!-- General Errors Alert -->
                            @if ($errors->any())
                                <div class="rounded-2xl bg-rose-50 border border-rose-300 p-4 text-xs text-rose-800 space-y-1.5 shadow-2xs">
                                    <div class="flex items-center gap-2 font-bold text-rose-700">
                                        <flux:icon name="exclamation-circle" class="size-4 text-rose-600 shrink-0" />
                                        <span>{{ __('Gagal Masuk Akun') }}</span>
                                    </div>
                                    <p class="text-[11px] leading-relaxed ps-6">
                                        {{ $errors->first('email') ?: ($errors->first('password') ?: $errors->first()) }}
                                    </p>
                                </div>
                            @endif

                            <form wire:submit="login" class="space-y-4">
                                {{-- Email Input --}}
                                <div class="space-y-1.5">
                                    <label for="pixigon_email" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                        {{ __('Email Akun') }} <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input 
                                            id="pixigon_email" 
                                            wire:model="email" 
                                            type="email" 
                                            required 
                                            autocomplete="email" 
                                            placeholder="nama@email.com"
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('email') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                    </div>
                                    @error('email')
                                        <div class="flex items-center gap-1.5 mt-1 text-xs font-bold text-rose-600">
                                            <flux:icon name="exclamation-circle" class="size-3.5 shrink-0 text-rose-500" />
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                {{-- Password Input --}}
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <label for="pixigon_password" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Kata Sandi') }} <span class="text-rose-500">*</span>
                                        </label>
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" wire:navigate class="text-xs font-semibold text-[#6bb82d] hover:text-[#2e5b18] hover:underline">
                                                {{ __('Lupa password?') }}
                                            </a>
                                        @endif
                                    </div>
                                    <div class="relative">
                                        <input 
                                            id="pixigon_password" 
                                            wire:model="password" 
                                            :type="showPassword ? 'text' : 'password'" 
                                            required 
                                            autocomplete="current-password" 
                                            placeholder="••••••••"
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('password') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all pr-12"
                                        />
                                        <button 
                                            type="button" 
                                            @click="showPassword = !showPassword"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-700 focus:outline-none"
                                            aria-label="{{ __('Tampilkan kata sandi') }}"
                                        >
                                            <flux:icon name="eye" class="size-4" x-show="!showPassword" />
                                            <flux:icon name="eye-slash" class="size-4" x-show="showPassword" style="display: none;" />
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="flex items-center gap-1.5 mt-1 text-xs font-bold text-rose-600">
                                            <flux:icon name="exclamation-circle" class="size-3.5 shrink-0 text-rose-500" />
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                {{-- Remember Me --}}
                                <div class="flex items-center justify-between pt-1">
                                    <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-zinc-700">
                                        <input 
                                            type="checkbox" 
                                            wire:model="remember" 
                                            class="rounded border-[#d6eda6] text-[#6bb82d] focus:ring-[#6bb82d] size-4"
                                        />
                                        <span>{{ __('Ingat saya di perangkat ini') }}</span>
                                    </label>
                                </div>

                                {{-- Submit Button --}}
                                <div class="pt-2">
                                    <button 
                                        type="submit" 
                                        wire:loading.attr="disabled"
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-[#6bb82d] hover:bg-[#5ca828] text-white font-bold text-sm py-3.5 px-8 shadow-lg shadow-lime-600/25 transition-all duration-300 transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-60"
                                    >
                                        <span wire:loading.remove wire:target="login">{{ __('Masuk ke Portal') }}</span>
                                        <span wire:loading wire:target="login" class="flex items-center gap-2">
                                            <flux:icon name="arrow-path" class="size-4 animate-spin" />
                                            <span>{{ __('Memproses...') }}</span>
                                        </span>
                                        <flux:icon name="arrow-right" class="size-4" wire:loading.remove wire:target="login" />
                                    </button>
                                </div>
                            </form>

                            {{-- Quick Switch Footer --}}
                            <div class="text-center pt-2 border-t border-zinc-100">
                                <p class="text-xs text-zinc-600">
                                    {{ __('Ingin mendaftarkan santri baru?') }}
                                    <button type="button" wire:click="$set('mode', 'register')" class="font-bold text-[#6bb82d] hover:text-[#2e5b18] underline ml-1 cursor-pointer">
                                        {{ __('Buka Pendaftaran') }}
                                    </button>
                                </p>
                            </div>
                        </div>
                    @else
                        {{-- Slide 2: Pendaftaran Santri Baru Interactive Card --}}
                        <div class="space-y-6">
                            <div>
                                <span class="px-3 py-1 rounded-full bg-[#6bb82d]/15 text-[#2e5b18] text-[11px] font-bold">
                                    ✦ {{ __('Penerimaan Santri Baru') }}
                                </span>
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#2e5b18] tracking-tight mt-2">
                                    {{ __('Daftar Santri Baru') }}
                                </h2>
                                <p class="text-xs sm:text-sm text-zinc-600 mt-1">
                                    {{ __('Pendaftaran online mudah dan cepat untuk kelas TPQ, Madin, dan Halaqah Tahfidz.') }}
                                </p>
                            </div>

                            {{-- 3 Step Interactive Workflow Cards --}}
                            <div class="space-y-3">
                                <div class="p-3.5 rounded-2xl bg-[#f0f8ec] border border-[#d6eda6] flex items-center gap-3">
                                    <div class="size-8 rounded-xl bg-white text-[#2e5b18] font-black text-xs flex items-center justify-center shadow-xs shrink-0">
                                        1
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-zinc-900">{{ __('Isi Biodata Online') }}</h4>
                                        <p class="text-[11px] text-zinc-600">{{ __('Lengkapi data santri, wali, dan pilihan jenjang pendidikan.') }}</p>
                                    </div>
                                </div>

                                <div class="p-3.5 rounded-2xl bg-[#f0f8ec] border border-[#d6eda6] flex items-center gap-3">
                                    <div class="size-8 rounded-xl bg-white text-[#2e5b18] font-black text-xs flex items-center justify-center shadow-xs shrink-0">
                                        2
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-zinc-900">{{ __('Munaqasyah & Observasi') }}</h4>
                                        <p class="text-[11px] text-zinc-600">{{ __('Penilaian kemampuan awal membaca untuk penempatan jilid/kelas.') }}</p>
                                    </div>
                                </div>

                                <div class="p-3.5 rounded-2xl bg-[#f0f8ec] border border-[#d6eda6] flex items-center gap-3">
                                    <div class="size-8 rounded-xl bg-white text-[#2e5b18] font-black text-xs flex items-center justify-center shadow-xs shrink-0">
                                        3
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-zinc-900">{{ __('Mulai Belajar & Kartu Santri') }}</h4>
                                        <p class="text-[11px] text-zinc-600">{{ __('Penerbitan NIS santri dan mulai kegiatan belajar mengaji.') }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Button --}}
                            <div class="pt-2">
                                <a 
                                    href="{{ route('santri.register.form') }}" 
                                    wire:navigate 
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-[#6bb82d] hover:bg-[#5ca828] text-white font-bold text-sm py-3.5 px-8 shadow-lg shadow-lime-600/25 transition-all duration-300 transform hover:-translate-y-0.5 cursor-pointer"
                                >
                                    <span>{{ __('Buka Formulir Pendaftaran Lengkap') }}</span>
                                    <flux:icon name="arrow-up-right" class="size-4" />
                                </a>
                            </div>

                            {{-- Quick Switch Footer --}}
                            <div class="text-center pt-2 border-t border-zinc-100">
                                <p class="text-xs text-zinc-600">
                                    {{ __('Sudah memiliki akun pengurus/asatidz/wali?') }}
                                    <button type="button" wire:click="$set('mode', 'login')" class="font-bold text-[#6bb82d] hover:text-[#2e5b18] underline ml-1 cursor-pointer">
                                        {{ __('Masuk ke Portal') }}
                                    </button>
                                </p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </main>

        {{-- Footer minimal --}}
        <footer class="w-full py-4 text-center text-xs text-zinc-500">
            &copy; {{ date('Y') }} {{ $lembagaName }} &bull; Tema: Pixigon Modern Light
        </footer>
    </div>
@else
    {{-- ================= DEFAULT THEME: LIVEWIRE KLASIK EMERALD AUTH ================= --}}
    <div class="min-h-screen bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] text-white antialiased font-sans flex flex-col justify-between selection:bg-emerald-500 selection:text-white">
        
        {{-- Top Minimal Header Bar --}}
        <header class="w-full py-6 px-6 sm:px-12 flex items-center justify-between z-20">
            <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-3 group">
                <div class="size-9 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <x-app-logo-icon class="size-8 text-emerald-400 fill-current" />
                </div>
                <div>
                    <span class="font-black text-xl tracking-tight text-white block uppercase">
                        {{ $lembagaName }}
                    </span>
                    <span class="text-[10px] tracking-widest text-emerald-300 uppercase font-semibold block">
                        {{ __('Sistem Informasi Akademik') }}
                    </span>
                </div>
            </a>

            <a 
                href="{{ route('home') }}" 
                wire:navigate 
                class="inline-flex items-center gap-2 rounded-xl border border-emerald-400/30 bg-white/10 hover:bg-white/20 text-emerald-100 font-bold text-xs px-5 py-2.5 shadow-md backdrop-blur-sm transition-all"
            >
                <flux:icon name="arrow-left" class="size-3.5" />
                <span>{{ __('Kembali ke Beranda') }}</span>
            </a>
        </header>

        {{-- Main Luxury Emerald Card --}}
        <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-10 relative overflow-hidden">
            
            {{-- Islamic Star Geometry Ambient Lights --}}
            <div class="absolute -top-32 -left-32 size-96 rounded-full bg-emerald-500/15 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 size-96 rounded-full bg-emerald-400/10 blur-3xl pointer-events-none"></div>

            <div 
                x-data="{ showPassword: false }"
                class="w-full max-w-5xl rounded-3xl bg-white shadow-2xl shadow-black/40 overflow-hidden grid grid-cols-1 lg:grid-cols-12 relative z-10 border border-emerald-500/30"
            >
                {{-- Left Showcase Column (Desktop Only) --}}
                <div class="hidden lg:flex lg:col-span-5 relative bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] text-white p-10 flex-col justify-between overflow-hidden">
                    <img 
                        src="https://images.unsplash.com/photo-1585036156171-384164a8c675?w=800&auto=format&fit=crop&q=80" 
                        alt="Santri Mengaji" 
                        class="absolute inset-0 size-full object-cover opacity-20"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-[#021d16] via-[#06382b]/80 to-transparent"></div>

                    {{-- Top Tag --}}
                    <div class="relative z-10 space-y-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 px-3.5 py-1 text-xs font-bold text-emerald-200">
                            ✦ {{ __('Portal Layanan Terpadu') }}
                        </span>
                        <h3 class="text-2xl font-extrabold text-white leading-tight">
                            {{ $lembagaName }}
                        </h3>
                    </div>

                    {{-- Middle Quote --}}
                    <div class="relative z-10 my-auto py-8">
                        <blockquote class="space-y-3">
                            <p class="text-sm text-emerald-100 leading-relaxed italic">
                                "Sebaik-baik kalian adalah orang yang belajar Al-Qur'an dan mengajarkannya."
                            </p>
                            <footer class="text-xs text-amber-300 font-bold uppercase tracking-wider">
                                &mdash; HR. Al-Bukhari
                            </footer>
                        </blockquote>

                        <div class="grid grid-cols-2 gap-3 mt-6 pt-6 border-t border-emerald-500/20 text-xs text-emerald-100">
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-amber-300" />
                                <span>{{ __('Metode Tilawati') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-amber-300" />
                                <span>{{ __('Presensi Realtime') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-amber-300" />
                                <span>{{ __('Mutaba\'ah Hafalan') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-amber-300" />
                                <span>{{ __('SPP & Keuangan') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Footer Note --}}
                    <div class="relative z-10 text-[11px] text-emerald-300/80">
                        &copy; {{ date('Y') }} {{ $lembagaName }}. Seluruh hak cipta dilindungi.
                    </div>
                </div>

                {{-- Right Login Form Column --}}
                <div class="col-span-12 lg:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-white text-zinc-900">
                    
                    <div class="space-y-6 max-w-md mx-auto w-full">
                        <div>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 text-emerald-900 border border-emerald-300 px-3.5 py-1 text-xs font-bold mb-3">
                                ✦ {{ __('Autentikasi Akun') }}
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-emerald-950 tracking-tight">
                                {{ __('Masuk ke Portal Akademik') }}
                            </h2>
                            <p class="text-xs sm:text-sm text-zinc-600 mt-1">
                                {{ __('Silakan masukkan email dan kata sandi akun Anda untuk melanjutkan.') }}
                            </p>
                        </div>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="rounded-2xl bg-emerald-50 border border-emerald-500 p-4 text-xs font-bold text-emerald-900 flex items-center gap-3 shadow-xs">
                                <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0" />
                                <span>{{ session('status') }}</span>
                            </div>
                        @endif

                        <!-- General Errors Alert -->
                        @if ($errors->any())
                            <div class="rounded-2xl bg-rose-50 border border-rose-300 p-4 text-xs text-rose-800 space-y-1.5 shadow-2xs">
                                <div class="flex items-center gap-2 font-bold text-rose-700">
                                    <flux:icon name="exclamation-circle" class="size-4 text-rose-600 shrink-0" />
                                    <span>{{ __('Gagal Masuk Akun') }}</span>
                                </div>
                                <p class="text-[11px] leading-relaxed ps-6">
                                    {{ $errors->first('email') ?: ($errors->first('password') ?: $errors->first()) }}
                                </p>
                            </div>
                        @endif

                        <form wire:submit="login" class="space-y-5">
                            {{-- Email Input --}}
                            <div class="space-y-2">
                                <label for="default_email" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                    {{ __('Alamat Email') }} <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        id="default_email" 
                                        wire:model="email" 
                                        type="email" 
                                        required 
                                        autocomplete="email" 
                                        placeholder="nama@email.com"
                                        class="w-full rounded-2xl bg-white border-2 {{ $errors->has('email') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                    />
                                </div>
                                @error('email')
                                    <div class="flex items-center gap-1.5 mt-1 text-xs font-bold text-rose-600">
                                        <flux:icon name="exclamation-circle" class="size-3.5 shrink-0 text-rose-500" />
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            {{-- Password Input --}}
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="default_password" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                        {{ __('Kata Sandi') }} <span class="text-rose-500">*</span>
                                    </label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" wire:navigate class="text-xs font-bold text-emerald-700 hover:text-emerald-900 hover:underline">
                                            {{ __('Lupa password?') }}
                                        </a>
                                    @endif
                                </div>
                                <div class="relative">
                                    <input 
                                        id="default_password" 
                                        wire:model="password" 
                                        :type="showPassword ? 'text' : 'password'" 
                                        required 
                                        autocomplete="current-password" 
                                        placeholder="••••••••"
                                        class="w-full rounded-2xl bg-white border-2 {{ $errors->has('password') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all pr-12"
                                    />
                                    <button 
                                        type="button" 
                                        @click="showPassword = !showPassword"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-700 focus:outline-none"
                                        aria-label="{{ __('Tampilkan kata sandi') }}"
                                    >
                                        <flux:icon name="eye" class="size-4" x-show="!showPassword" />
                                        <flux:icon name="eye-slash" class="size-4" x-show="showPassword" style="display: none;" />
                                    </button>
                                </div>
                                @error('password')
                                    <div class="flex items-center gap-1.5 mt-1 text-xs font-bold text-rose-600">
                                        <flux:icon name="exclamation-circle" class="size-3.5 shrink-0 text-rose-500" />
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            {{-- Remember Me --}}
                            <div class="flex items-center justify-between pt-1">
                                <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-zinc-700">
                                    <input 
                                        type="checkbox" 
                                        wire:model="remember" 
                                        class="rounded border-emerald-300 text-emerald-700 focus:ring-emerald-600 size-4"
                                    />
                                    <span>{{ __('Ingat saya di perangkat ini') }}</span>
                                </label>
                            </div>

                            {{-- Submit Button --}}
                            <div class="pt-2">
                                <button 
                                    type="submit" 
                                    wire:loading.attr="disabled"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-sm py-4 px-8 shadow-xl shadow-emerald-900/25 transition-all duration-300 transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-60"
                                >
                                    <span wire:loading.remove wire:target="login">{{ __('Masuk ke Portal Akademik') }}</span>
                                    <span wire:loading wire:target="login" class="flex items-center gap-2">
                                        <flux:icon name="arrow-path" class="size-4 animate-spin" />
                                        <span>{{ __('Memproses...') }}</span>
                                    </span>
                                    <flux:icon name="arrow-right" class="size-4" wire:loading.remove wire:target="login" />
                                </button>
                            </div>
                        </form>

                        {{-- Registration Link --}}
                        <div class="text-center pt-4 border-t border-zinc-100">
                            <p class="text-xs text-zinc-600">
                                {{ __('Belum mendaftarkan putra-putri Anda?') }}
                                <a href="{{ route('santri.register.form') }}" wire:navigate class="font-bold text-emerald-700 hover:text-emerald-950 underline ml-1">
                                    {{ __('Daftar Santri Baru') }}
                                </a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        {{-- Footer Minimal --}}
        <footer class="w-full py-4 text-center text-xs text-emerald-300/70">
            &copy; {{ date('Y') }} {{ $lembagaName }} &bull; Tema: Klasik Emerald Al-Hikmah
        </footer>
    </div>
@endif
</div>
