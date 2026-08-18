@php
    $setting = \App\Models\Setting::query()->first();
    $theme = $setting?->landing_theme ?? 'default';
    $pageContactTitle = $setting?->getLandingContent('page_contact_title', __('Hubungi Kami'), $theme);
    $pageContactSubtitle = $setting?->getLandingContent('page_contact_subtitle', __('Ada pertanyaan seputar pendaftaran santri baru, kurikulum, atau administrasi? Kirimkan pesan Anda, kami akan segera merespons.'), $theme);
    $pageContactBannerImage = $setting?->getLandingContent('page_contact_banner_image', 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=1400&q=80&auto=format&fit=crop', $theme);
@endphp

<x-layouts::public :title="__('Kontak')">
    @if ($theme === 'pixigon')
        {{-- ================= PIXIGON THEME (SOFT GREEN LIGHT) ================= --}}
        <div class="flex flex-col w-full overflow-hidden font-sans bg-white text-zinc-800">
            
            {{-- Inner Page Hero Banner Matching Screenshot 2 --}}
            <section class="relative bg-[#f0f8ec] py-20 lg:py-28 overflow-hidden font-sans" data-editable-image="page_contact_banner_image" data-image-label="Ganti Background Banner Kontak">
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
                    <h1 data-editable-field="page_contact_title" class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-zinc-900 tracking-tight mb-3">
                        {{ $pageContactTitle }}
                    </h1>
                    
                    <p data-editable-field="page_contact_subtitle" class="text-zinc-600 text-sm sm:text-base max-w-2xl mx-auto mb-4">
                        {{ $pageContactSubtitle }}
                    </p>

                    <div class="flex items-center justify-center gap-2 text-xs sm:text-sm font-medium text-zinc-500">
                        <a href="{{ route('home') }}" wire:navigate class="hover:text-[#2e5b18] transition">{{ __('Beranda') }}</a>
                        <span>/</span>
                        <span class="text-zinc-800 font-semibold">{{ __('Kontak Sekretariat') }}</span>
                    </div>

                    <div class="mt-4 flex justify-center text-zinc-400">
                        <svg class="size-5 animate-bounce" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>
            </section>

            {{-- Contact Content Section --}}
            <section class="py-16 bg-white">
                <div class="container mx-auto px-4 sm:px-6">
                    <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 max-w-6xl mx-auto items-start">
                        
                        {{-- Form Column (7 cols) --}}
                        <div class="lg:col-span-7 rounded-3xl border border-[#d6eda6] bg-[#f0f8ec] p-8 sm:p-10 shadow-xs">
                            <h3 class="text-2xl font-extrabold text-[#2e5b18] mb-2">{{ __('Kirimkan Pesan Pertanyaan') }}</h3>
                            <p class="text-xs sm:text-sm text-zinc-600 mb-8">{{ __('Isi formulir di bawah ini untuk terhubung langsung dengan tim sekretariat kami.') }}</p>

                            @if (session('status'))
                                <div class="mb-6 rounded-2xl bg-white border border-[#6bb82d] p-4 text-[#2e5b18] flex items-center gap-3 shadow-xs">
                                    <flux:icon name="check-circle" class="size-5 text-[#6bb82d] shrink-0" />
                                    <span class="text-xs font-bold">{{ session('status') }}</span>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-300 p-4 text-xs text-rose-800 space-y-1 shadow-2xs">
                                    <div class="flex items-center gap-2 font-bold text-rose-700">
                                        <flux:icon name="exclamation-circle" class="size-4 text-rose-600 shrink-0" />
                                        <span>{{ __('Harap lengkapi formulir dengan benar:') }}</span>
                                    </div>
                                    <ul class="list-disc list-inside ps-2 text-[11px] space-y-0.5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('contact.store') }}" class="flex flex-col gap-6">
                                @csrf

                                <div class="grid gap-6 sm:grid-cols-2">
                                    {{-- Nama Lengkap --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_name" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Nama Lengkap') }} <span class="text-rose-500">*</span>
                                        </label>
                                        <input 
                                            id="pixigon_name"
                                            name="name" 
                                            type="text" 
                                            value="{{ old('name') }}" 
                                            required 
                                            placeholder="{{ __('Contoh: Abdullah Fauzi') }}"
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('name') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                        @error('name')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_email" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Email Aktif') }} <span class="text-rose-500">*</span>
                                        </label>
                                        <input 
                                            id="pixigon_email"
                                            name="email" 
                                            type="email" 
                                            value="{{ old('email') }}" 
                                            required 
                                            placeholder="{{ __('nama@email.com') }}"
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('email') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                        @error('email')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Subjek Pesan --}}
                                <div class="flex flex-col gap-2">
                                    <label for="pixigon_subject" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                        {{ __('Subjek Pesan') }} <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        id="pixigon_subject"
                                        name="subject" 
                                        type="text" 
                                        value="{{ old('subject') }}" 
                                        required 
                                        placeholder="{{ __('Contoh: Tanya Pendaftaran Santri Baru TPQ') }}"
                                        class="w-full rounded-2xl bg-white border {{ $errors->has('subject') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                    />
                                    @error('subject')
                                        <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                            <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                {{-- Isi Pesan --}}
                                <div class="flex flex-col gap-2">
                                    <label for="pixigon_message" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                        {{ __('Isi Pesan') }} <span class="text-rose-500">*</span>
                                    </label>
                                    <textarea 
                                        id="pixigon_message"
                                        name="message" 
                                        rows="5" 
                                        required 
                                        placeholder="{{ __('Tuliskan pertanyaan atau kebutuhan informasi Anda di sini...') }}"
                                        class="w-full rounded-2xl bg-white border {{ $errors->has('message') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                    >{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                            <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button 
                                        type="submit" 
                                        class="inline-flex items-center gap-2 rounded-full bg-[#6bb82d] hover:bg-[#5ca828] text-white font-bold text-sm px-8 py-3.5 shadow-lg shadow-lime-600/25 transition-all duration-300 transform hover:-translate-y-0.5"
                                    >
                                        <flux:icon name="paper-airplane" class="size-4" />
                                        <span>{{ __('Kirim Pesan Sekarang') }}</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Details & Map (5 cols) --}}
                        <div class="lg:col-span-5 flex flex-col gap-6">
                            <div class="rounded-3xl border border-[#d6eda6] bg-[#f0f8ec] p-8 sm:p-10 shadow-xs space-y-6">
                                <h3 class="text-xl font-extrabold text-[#2e5b18] border-b border-[#d6eda6] pb-4">{{ __('Informasi Sekretariat') }}</h3>

                                @if ($setting?->alamat)
                                    <div class="flex items-start gap-3.5">
                                        <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-white text-[#2e5b18] shadow-xs border border-[#d6eda6]">
                                            <flux:icon name="map-pin" class="size-5 text-[#6bb82d]" />
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-xs text-zinc-900 uppercase tracking-wide">{{ __('Alamat Lembaga') }}</h4>
                                            <p class="text-xs text-zinc-600 leading-relaxed mt-1">{{ $setting->alamat }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($setting?->telepon)
                                    <div class="flex items-center gap-3.5">
                                        <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-white text-[#2e5b18] shadow-xs border border-[#d6eda6]">
                                            <flux:icon name="phone" class="size-5 text-[#6bb82d]" />
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-xs text-zinc-900 uppercase tracking-wide">{{ __('Telepon / WhatsApp') }}</h4>
                                            <a href="tel:{{ $setting->telepon }}" class="text-sm text-[#2e5b18] font-bold hover:underline mt-0.5 block">{{ $setting->telepon }}</a>
                                        </div>
                                    </div>
                                @endif

                                @if ($setting?->email)
                                    <div class="flex items-center gap-3.5">
                                        <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-white text-[#2e5b18] shadow-xs border border-[#d6eda6]">
                                            <flux:icon name="envelope" class="size-5 text-[#6bb82d]" />
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-xs text-zinc-900 uppercase tracking-wide">{{ __('Email Resmi') }}</h4>
                                            <a href="mailto:{{ $setting->email }}" class="text-sm text-[#2e5b18] font-bold hover:underline mt-0.5 block">{{ $setting->email }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if ($setting?->google_maps_url)
                                <div class="overflow-hidden rounded-3xl border border-[#d6eda6] shadow-sm">
                                    <iframe
                                        src="{{ $setting->google_maps_url }}"
                                        class="w-full"
                                        style="height: 250px; border: 0;"
                                        allowfullscreen=""
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"
                                        aria-label="{{ __('Peta lokasi') }}"
                                    ></iframe>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </section>
        </div>
    @else
        {{-- ================= DEFAULT THEME (KLASIK EMERALD) ================= --}}
        <div class="flex flex-col w-full overflow-hidden font-sans">
            {{-- Hero Banner --}}
            <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-20 text-white border-b-2 border-emerald-500/30" data-editable-image="page_contact_banner_image" data-image-label="Ganti Background Banner Kontak">
                <img
                    src="{{ $pageContactBannerImage }}"
                    alt="Kontak"
                    class="absolute inset-0 size-full object-cover opacity-20"
                    loading="eager"
                    width="1400" height="400"
                >
                <div class="relative mx-auto max-w-7xl px-6">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                        ✦ {{ __('Layanan Informasi') }}
                    </span>
                    <h1 data-editable-field="page_contact_title" class="text-4xl sm:text-5xl font-extrabold text-white leading-tight">
                        {{ $pageContactTitle }}
                    </h1>
                    <p data-editable-field="page_contact_subtitle" class="mt-4 max-w-2xl text-sm text-emerald-100/90 leading-relaxed">
                        {{ $pageContactSubtitle }}
                    </p>
                </div>
            </section>

            {{-- Contact Form & Info Section --}}
            <section class="w-full bg-[#edf7f4] py-16 border-b border-emerald-500/20 text-zinc-800">
                <div class="mx-auto max-w-7xl px-6">
                    <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 items-start">
                        
                        {{-- Form Column (7 cols) - Solid Crisp High Contrast Inputs --}}
                        <div class="lg:col-span-7 rounded-3xl border border-emerald-600/30 bg-white p-8 sm:p-10 shadow-lg">
                            <h3 class="text-2xl font-extrabold text-emerald-950 mb-2">{{ __('Kirimkan Pesan Pertanyaan') }}</h3>
                            <p class="text-xs sm:text-sm text-zinc-600 mb-8">{{ __('Isi formulir di bawah ini untuk menghubungi sekretariat lembaga.') }}</p>

                            @if (session('status'))
                                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-500 p-4 text-emerald-900 flex items-center gap-3 shadow-xs">
                                    <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0" />
                                    <span class="text-xs font-bold">{{ session('status') }}</span>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-300 p-4 text-xs text-rose-800 space-y-1 shadow-2xs">
                                    <div class="flex items-center gap-2 font-bold text-rose-700">
                                        <flux:icon name="exclamation-circle" class="size-4 text-rose-600 shrink-0" />
                                        <span>{{ __('Harap lengkapi formulir dengan benar:') }}</span>
                                    </div>
                                    <ul class="list-disc list-inside ps-2 text-[11px] space-y-0.5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('contact.store') }}" class="flex flex-col gap-6">
                                @csrf

                                <div class="grid gap-6 sm:grid-cols-2">
                                    {{-- Nama Lengkap --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="default_name" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Nama Lengkap') }} <span class="text-rose-500">*</span>
                                        </label>
                                        <input 
                                            id="default_name"
                                            name="name" 
                                            type="text" 
                                            value="{{ old('name') }}" 
                                            required 
                                            placeholder="{{ __('Contoh: Abdullah Fauzi') }}"
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('name') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                        @error('name')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="default_email" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Email Aktif') }} <span class="text-rose-500">*</span>
                                        </label>
                                        <input 
                                            id="default_email"
                                            name="email" 
                                            type="email" 
                                            value="{{ old('email') }}" 
                                            required 
                                            placeholder="{{ __('nama@email.com') }}"
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('email') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                        @error('email')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Subjek --}}
                                <div class="flex flex-col gap-2">
                                    <label for="default_subject" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                        {{ __('Subjek Pesan') }} <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        id="default_subject"
                                        name="subject" 
                                        type="text" 
                                        value="{{ old('subject') }}" 
                                        required 
                                        placeholder="{{ __('Contoh: Tanya Pendaftaran Santri Baru TPQ') }}"
                                        class="w-full rounded-2xl bg-white border-2 {{ $errors->has('subject') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                    />
                                    @error('subject')
                                        <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                            <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                {{-- Pesan --}}
                                <div class="flex flex-col gap-2">
                                    <label for="default_message" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                        {{ __('Isi Pesan') }} <span class="text-rose-500">*</span>
                                    </label>
                                    <textarea 
                                        id="default_message"
                                        name="message" 
                                        rows="5" 
                                        required 
                                        placeholder="{{ __('Tuliskan pesan pertanyaan Anda di sini...') }}"
                                        class="w-full rounded-2xl bg-white border-2 {{ $errors->has('message') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                    >{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                            <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button 
                                        type="submit" 
                                        class="inline-flex items-center gap-2 rounded-2xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm px-8 py-3.5 shadow-lg shadow-emerald-800/25 transition-all duration-300 transform hover:-translate-y-0.5"
                                    >
                                        <flux:icon name="paper-airplane" class="size-4" />
                                        <span>{{ __('Kirim Pesan Sekarang') }}</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Contact Details & Map Column (5 cols) --}}
                        <div class="lg:col-span-5 flex flex-col gap-6">
                            <div class="rounded-3xl border border-emerald-500/20 bg-white p-8 sm:p-10 shadow-lg space-y-6">
                                <h3 class="text-xl font-extrabold text-emerald-950 border-b border-emerald-100 pb-4">{{ __('Informasi Sekretariat') }}</h3>

                                @if ($setting?->alamat)
                                    <div class="flex items-start gap-3.5">
                                        <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <flux:icon name="map-pin" class="size-5" />
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-xs text-emerald-950 uppercase tracking-wide">{{ __('Alamat Lembaga') }}</h4>
                                            <p class="text-xs text-zinc-600 leading-relaxed mt-1">{{ $setting->alamat }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($setting?->telepon)
                                    <div class="flex items-center gap-3.5">
                                        <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <flux:icon name="phone" class="size-5" />
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-xs text-emerald-950 uppercase tracking-wide">{{ __('Telepon / WhatsApp') }}</h4>
                                            <a href="tel:{{ $setting->telepon }}" class="text-sm text-emerald-700 font-bold hover:underline mt-0.5 block">{{ $setting->telepon }}</a>
                                        </div>
                                    </div>
                                @endif

                                @if ($setting?->email)
                                    <div class="flex items-center gap-3.5">
                                        <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <flux:icon name="envelope" class="size-5" />
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-xs text-emerald-950 uppercase tracking-wide">{{ __('Email Resmi') }}</h4>
                                            <a href="mailto:{{ $setting->email }}" class="text-sm text-emerald-700 font-bold hover:underline mt-0.5 block">{{ $setting->email }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if ($setting?->google_maps_url)
                                <div class="overflow-hidden rounded-3xl border-2 border-emerald-500/30 shadow-md">
                                    <iframe
                                        src="{{ $setting->google_maps_url }}"
                                        class="w-full"
                                        style="height: 250px; border: 0;"
                                        allowfullscreen=""
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"
                                        aria-label="{{ __('Peta lokasi') }}"
                                    ></iframe>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endif
</x-layouts::public>
