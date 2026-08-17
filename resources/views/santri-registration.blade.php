@php
    $setting = \App\Models\Setting::query()->first();
    $theme = $setting?->landing_theme ?? 'default';
@endphp

<x-layouts::public :title="__('Pendaftaran Santri Baru')">
    @if ($theme === 'pixigon')
        {{-- ================= PIXIGON THEME (SOFT GREEN LIGHT) ================= --}}
        <div class="flex flex-col w-full overflow-hidden font-sans bg-[#f0f8ec] text-zinc-800">
            
            {{-- Inner Page Hero Banner Matching Screenshot 2 --}}
            <section class="relative bg-[#f0f8ec] pt-16 pb-12 lg:pt-20 lg:pb-16 overflow-hidden font-sans">
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
                <div class="hidden lg:block absolute left-36 bottom-6 pointer-events-none text-zinc-400">
                    <svg class="w-10 h-16" viewBox="0 0 40 60" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                        <path d="M10 5 C25 15, 5 30, 20 45 C30 55, 15 58, 25 60"/>
                    </svg>
                </div>

                {{-- Right Ribbon Squiggle --}}
                <div class="hidden lg:block absolute right-32 top-8 pointer-events-none text-zinc-400">
                    <svg class="w-12 h-14" viewBox="0 0 50 60" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                        <path d="M5 10 C20 5, 35 15, 30 30 C25 45, 10 35, 20 20 C30 5, 40 25, 45 40"/>
                    </svg>
                </div>

                {{-- Right Large Circular Arc Outline --}}
                <div class="hidden lg:block absolute -right-24 -bottom-24 size-80 rounded-full border border-lime-400/40 pointer-events-none"></div>

                {{-- Center Title & Breadcrumbs --}}
                <div class="container mx-auto px-4 sm:px-6 relative z-10 text-center max-w-4xl">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-[#d6eda6] bg-white px-4 py-1 text-xs font-bold text-[#2e5b18] shadow-2xs mb-3">
                        ✦ {{ __('PPDB Online') }}
                    </span>
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-[#2e5b18] tracking-tight mb-3">
                        {{ __('Pendaftaran Santri Baru') }}
                    </h1>
                    
                    <div class="flex items-center justify-center gap-2 text-xs sm:text-sm font-medium text-zinc-600">
                        <a href="{{ route('home') }}" wire:navigate class="hover:text-[#2e5b18] transition">{{ __('Beranda') }}</a>
                        <span>/</span>
                        <span class="text-zinc-900 font-semibold">{{ __('Pendaftaran Santri') }}</span>
                    </div>
                </div>
            </section>

            {{-- Form Section --}}
            <section class="pb-24 pt-4">
                <div class="container mx-auto px-4 sm:px-6 max-w-4xl">
                    <div class="rounded-3xl border border-[#d6eda6] bg-white p-8 sm:p-12 shadow-xl shadow-lime-900/5">
                        
                        @if (session('status'))
                            <div class="mb-8 rounded-2xl bg-[#f0f8ec] border border-[#6bb82d] p-5 text-[#2e5b18] flex items-center gap-4 shadow-xs">
                                <div class="size-10 rounded-xl bg-[#6bb82d] text-white flex items-center justify-center shrink-0">
                                    <flux:icon name="check-circle" class="size-6" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-[#2e5b18]">{{ __('Pendaftaran Berhasil Dikirim!') }}</h4>
                                    <p class="text-xs text-zinc-600 mt-0.5">{{ session('status') }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-8 rounded-2xl bg-rose-50 border border-rose-300 p-5 text-rose-800 shadow-xs">
                                <div class="flex items-center gap-2.5 font-bold text-sm mb-2 text-rose-700">
                                    <flux:icon name="exclamation-circle" class="size-5 text-rose-600 shrink-0" />
                                    <span>{{ __('Harap perbaiki beberapa isian berikut:') }}</span>
                                </div>
                                <ul class="list-disc list-inside text-xs space-y-1 text-rose-700 ps-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (! ($isPpdbOpen ?? true))
                            <div class="rounded-2xl bg-amber-50 border border-amber-300 p-8 text-amber-900 flex flex-col items-center justify-center text-center space-y-3 shadow-xs">
                                <div class="size-12 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                                    <flux:icon name="lock-closed" class="size-6" />
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-base text-amber-950">{{ __('Pendaftaran PPDB Online Saat Ini Dikunci') }}</h4>
                                    <p class="text-xs text-amber-800 mt-1 max-w-md">
                                        {{ __('Pendaftaran Santri Baru Online (PPDB) belum dibuka atau telah dikunci oleh Administrator. Silakan hubungi pengurus lembaga untuk informasi lebih lanjut.') }}
                                    </p>
                                </div>
                            </div>
                        @else
                        <form method="POST" action="{{ route('santri.register') }}" class="flex flex-col gap-10">
                            @csrf

                            {{-- Bagian 1: Identitas Santri --}}
                            <div class="space-y-6">
                                <div class="flex items-center gap-3 border-b border-[#d6eda6] pb-3">
                                    <span class="flex size-7 items-center justify-center rounded-lg bg-[#6bb82d] text-xs font-black text-white">1</span>
                                    <div>
                                        <h3 class="text-lg font-extrabold text-[#2e5b18]">{{ __('Identitas Calon Santri') }}</h3>
                                        <p class="text-xs text-zinc-500">{{ __('Data pribadi calon santri sesuai kartu keluarga / akta kelahiran.') }}</p>
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    {{-- Nama Lengkap --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_nama_lengkap" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Nama Lengkap Santri') }} <span class="text-rose-500">*</span>
                                        </label>
                                        <input 
                                            id="pixigon_nama_lengkap" 
                                            name="nama_lengkap" 
                                            type="text" 
                                            value="{{ old('nama_lengkap') }}" 
                                            required 
                                            placeholder="{{ __('Contoh: Muhammad Rayhan') }}"
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('nama_lengkap') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                        @error('nama_lengkap')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Nama Panggilan --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_nama_panggilan" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Nama Panggilan') }}
                                        </label>
                                        <input 
                                            id="pixigon_nama_panggilan" 
                                            name="nama_panggilan" 
                                            type="text" 
                                            value="{{ old('nama_panggilan') }}" 
                                            placeholder="{{ __('Contoh: Rayhan') }}"
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('nama_panggilan') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                        @error('nama_panggilan')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    {{-- Jenis Kelamin --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_jenis_kelamin" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Jenis Kelamin') }} <span class="text-rose-500">*</span>
                                        </label>
                                        <select 
                                            id="pixigon_jenis_kelamin" 
                                            name="jenis_kelamin" 
                                            required 
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('jenis_kelamin') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 pr-10 text-sm text-zinc-900 shadow-2xs outline-none transition-all appearance-none cursor-pointer bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%232e5b18%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_1rem_center] bg-no-repeat"
                                        >
                                            <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }} class="bg-white text-zinc-400 py-2">{{ __('Pilih Jenis Kelamin') }}</option>
                                            @foreach ($genders as $gender)
                                                <option value="{{ $gender->value }}" {{ old('jenis_kelamin') === $gender->value ? 'selected' : '' }} class="bg-white text-zinc-900 py-2">
                                                    {{ $gender->value === 'laki_laki' ? __('Laki-laki') : __('Perempuan') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('jenis_kelamin')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Anak Ke --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_anak_ke" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Anak Ke-') }}
                                        </label>
                                        <input 
                                            id="pixigon_anak_ke" 
                                            name="anak_ke" 
                                            type="number" 
                                            min="1" 
                                            value="{{ old('anak_ke', 1) }}" 
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('anak_ke') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 shadow-2xs outline-none transition-all"
                                        />
                                        @error('anak_ke')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    {{-- Tempat Lahir --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_tempat_lahir" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Tempat Lahir') }}
                                        </label>
                                        <input 
                                            id="pixigon_tempat_lahir" 
                                            name="tempat_lahir" 
                                            type="text" 
                                            value="{{ old('tempat_lahir') }}" 
                                            placeholder="{{ __('Contoh: Surabaya') }}"
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('tempat_lahir') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                        @error('tempat_lahir')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Tanggal Lahir --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_tanggal_lahir" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Tanggal Lahir') }}
                                        </label>
                                        <input 
                                            id="pixigon_tanggal_lahir" 
                                            name="tanggal_lahir" 
                                            type="date" 
                                            value="{{ old('tanggal_lahir') }}" 
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('tanggal_lahir') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 shadow-2xs outline-none transition-all"
                                        />
                                        @error('tanggal_lahir')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Bagian 2: Pilihan Jenjang & Kelas --}}
                            <div class="space-y-6">
                                <div class="flex items-center gap-3 border-b border-[#d6eda6] pb-3">
                                    <span class="flex size-7 items-center justify-center rounded-lg bg-[#6bb82d] text-xs font-black text-white">2</span>
                                    <div>
                                        <h3 class="text-lg font-extrabold text-[#2e5b18]">{{ __('Pilihan Lembaga & Kelas') }}</h3>
                                        <p class="text-xs text-zinc-500">{{ __('Tentukan unit lembaga dan kelas belajar santri.') }}</p>
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    {{-- Unit Lembaga --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_lembaga_id" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Unit Lembaga Tujuan') }}
                                        </label>
                                        <select 
                                            id="pixigon_lembaga_id" 
                                            name="lembaga_id" 
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('lembaga_id') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 pr-10 text-sm text-zinc-900 shadow-2xs outline-none transition-all appearance-none cursor-pointer bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%232e5b18%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_1rem_center] bg-no-repeat"
                                        >
                                            <option value="" disabled {{ old('lembaga_id') ? '' : 'selected' }} class="bg-white text-zinc-400 py-2">{{ __('Pilih Unit Lembaga') }}</option>
                                            @foreach ($lembagas as $lembaga)
                                                <option value="{{ $lembaga->id }}" {{ old('lembaga_id') == $lembaga->id ? 'selected' : '' }} class="bg-white text-zinc-900 py-2">
                                                    {{ $lembaga->nama }} ({{ $lembaga->jenjang }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('lembaga_id')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Kelas Tujuan --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_kelas_id" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Pilihan Kelas / Kelompok') }}
                                        </label>
                                        <select 
                                            id="pixigon_kelas_id" 
                                            name="kelas_id" 
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('kelas_id') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 pr-10 text-sm text-zinc-900 shadow-2xs outline-none transition-all appearance-none cursor-pointer bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%232e5b18%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_1rem_center] bg-no-repeat"
                                        >
                                            <option value="" disabled {{ old('kelas_id') ? '' : 'selected' }} class="bg-white text-zinc-400 py-2">{{ __('Pilih Kelas') }}</option>
                                            @foreach ($kelasList as $kelas)
                                                <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }} class="bg-white text-zinc-900 py-2">
                                                    {{ $kelas->nama }} @if($kelas->lembaga) ({{ $kelas->lembaga->jenjang }}) @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kelas_id')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- NISN / No Induk --}}
                                <div class="flex flex-col gap-2">
                                    <label for="pixigon_noinduk" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                        {{ __('No. Induk Santri / NISN (Opsional)') }}
                                    </label>
                                    <input 
                                        id="pixigon_noinduk" 
                                        name="noinduk" 
                                        type="text" 
                                        value="{{ old('noinduk') }}" 
                                        placeholder="{{ __('Kosongkan jika belum memiliki NISN') }}"
                                        class="w-full rounded-2xl bg-white border {{ $errors->has('noinduk') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                    />
                                    @error('noinduk')
                                        <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                            <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Bagian 3: Data Orang Tua / Wali --}}
                            <div class="space-y-6">
                                <div class="flex items-center gap-3 border-b border-[#d6eda6] pb-3">
                                    <span class="flex size-7 items-center justify-center rounded-lg bg-[#6bb82d] text-xs font-black text-white">3</span>
                                    <div>
                                        <h3 class="text-lg font-extrabold text-[#2e5b18]">{{ __('Data Orang Tua & Kontak Wali') }}</h3>
                                        <p class="text-xs text-zinc-500">{{ __('Informasi untuk konfirmasi dan administrasi santri.') }}</p>
                                    </div>
                                </div>

                                {{-- WhatsApp Wali (WAJIB) --}}
                                <div class="flex flex-col gap-2">
                                    <label for="pixigon_telepon_wali" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                        {{ __('No. WhatsApp Aktif Wali') }} <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        id="pixigon_telepon_wali" 
                                        name="telepon_wali" 
                                        type="text" 
                                        required 
                                        value="{{ old('telepon_wali') }}" 
                                        placeholder="08xxxxxxxxxx"
                                        class="w-full rounded-2xl bg-white border {{ $errors->has('telepon_wali') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                    />
                                    @error('telepon_wali')
                                        <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                            <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                {{-- Ayah --}}
                                <div class="grid gap-6 sm:grid-cols-3">
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_nama_ayah" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Nama Ayah') }}
                                        </label>
                                        <input 
                                            id="pixigon_nama_ayah" 
                                            name="nama_ayah" 
                                            type="text" 
                                            value="{{ old('nama_ayah') }}" 
                                            placeholder="{{ __('Nama ayah') }}"
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('nama_ayah') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                        @error('nama_ayah')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_pendidikan_ayah" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Pendidikan Ayah') }}
                                        </label>
                                        <input 
                                            id="pixigon_pendidikan_ayah" 
                                            name="pendidikan_ayah" 
                                            type="text" 
                                            value="{{ old('pendidikan_ayah') }}" 
                                            placeholder="{{ __('Contoh: S1') }}"
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('pendidikan_ayah') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_pekerjaan_ayah" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Pekerjaan Ayah') }}
                                        </label>
                                        <input 
                                            id="pixigon_pekerjaan_ayah" 
                                            name="pekerjaan_ayah" 
                                            type="text" 
                                            value="{{ old('pekerjaan_ayah') }}" 
                                            placeholder="{{ __('Contoh: Wiraswasta') }}"
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('pekerjaan_ayah') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                    </div>
                                </div>

                                {{-- Ibu --}}
                                <div class="grid gap-6 sm:grid-cols-3">
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_nama_ibu" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Nama Ibu') }}
                                        </label>
                                        <input 
                                            id="pixigon_nama_ibu" 
                                            name="nama_ibu" 
                                            type="text" 
                                            value="{{ old('nama_ibu') }}" 
                                            placeholder="{{ __('Nama ibu') }}"
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('nama_ibu') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                        @error('nama_ibu')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_pendidikan_ibu" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Pendidikan Ibu') }}
                                        </label>
                                        <input 
                                            id="pixigon_pendidikan_ibu" 
                                            name="pendidikan_ibu" 
                                            type="text" 
                                            value="{{ old('pendidikan_ibu') }}" 
                                            placeholder="{{ __('Contoh: SMA/D3') }}"
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('pendidikan_ibu') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="pixigon_pekerjaan_ibu" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                            {{ __('Pekerjaan Ibu') }}
                                        </label>
                                        <input 
                                            id="pixigon_pekerjaan_ibu" 
                                            name="pekerjaan_ibu" 
                                            type="text" 
                                            value="{{ old('pekerjaan_ibu') }}" 
                                            placeholder="{{ __('Contoh: Ibu Rumah Tangga') }}"
                                            class="w-full rounded-2xl bg-white border {{ $errors->has('pekerjaan_ibu') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                    </div>
                                </div>

                                {{-- Alamat --}}
                                <div class="flex flex-col gap-2">
                                    <label for="pixigon_alamat" class="text-xs font-bold text-zinc-800 uppercase tracking-wider">
                                        {{ __('Alamat Lengkap') }}
                                    </label>
                                    <textarea 
                                        id="pixigon_alamat" 
                                        name="alamat" 
                                        rows="3" 
                                        placeholder="{{ __('Tuliskan alamat domisili santri...') }}"
                                        class="w-full rounded-2xl bg-white border {{ $errors->has('alamat') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-[#d6eda6] focus:border-[#6bb82d] focus:ring-4 focus:ring-[#6bb82d]/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                    >{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                            <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="flex justify-end pt-6 border-t border-[#d6eda6]">
                                <button 
                                    type="submit" 
                                    class="inline-flex items-center gap-2.5 rounded-full bg-[#6bb82d] hover:bg-[#5ca828] text-white font-extrabold text-sm px-10 py-4 shadow-xl shadow-lime-600/30 transition-all duration-300 transform hover:-translate-y-0.5 cursor-pointer"
                                >
                                    <flux:icon name="user-plus" class="size-4" />
                                    <span>{{ __('Kirim Formulir Pendaftaran') }}</span>
                                </button>
                            </div>

                        </form>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    @else
        {{-- ================= DEFAULT THEME (KLASIK EMERALD) ================= --}}
        <div class="flex flex-col w-full overflow-hidden font-sans bg-[#edf7f4] text-zinc-900">
            {{-- Hero Banner --}}
            <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-20 text-white border-b-2 border-emerald-500/30">
                <img
                    src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=1400&q=80&auto=format&fit=crop"
                    alt="Pendaftaran Santri Al-Hikmah"
                    class="absolute inset-0 size-full object-cover opacity-20"
                    loading="eager"
                    width="1400" height="400"
                >
                <div class="relative mx-auto max-w-7xl px-6">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                        ✦ {{ __('PPDB Online') }}
                    </span>
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-white leading-tight">
                        {{ __('Formulir Pendaftaran Santri Baru') }}
                    </h1>
                    <p class="mt-4 max-w-2xl text-sm text-emerald-100/90 leading-relaxed">
                        {{ __('Lengkapi formulir pendaftaran di bawah ini. Data calon santri akan ditinjau dan dikonfirmasi oleh pengurus lembaga.') }}
                    </p>
                </div>
            </section>

            {{-- Form Registration Section --}}
            <section class="w-full py-16">
                <div class="mx-auto max-w-4xl px-6">
                    <div class="rounded-3xl border border-emerald-600/30 bg-white p-8 sm:p-12 shadow-2xl shadow-emerald-950/10">
                        
                        @if (session('status'))
                            <div class="mb-8 rounded-2xl bg-emerald-50 border border-emerald-500 p-5 text-emerald-900 flex items-center gap-4 shadow-xs">
                                <div class="size-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                    <flux:icon name="check-circle" class="size-6" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-emerald-950">{{ __('Pendaftaran Berhasil Dikirim!') }}</h4>
                                    <p class="text-xs text-zinc-600 mt-0.5">{{ session('status') }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-8 rounded-2xl bg-rose-50 border border-rose-300 p-5 text-rose-800 shadow-xs">
                                <div class="flex items-center gap-2.5 font-bold text-sm mb-2 text-rose-700">
                                    <flux:icon name="exclamation-circle" class="size-5 text-rose-600 shrink-0" />
                                    <span>{{ __('Harap perbaiki beberapa isian berikut:') }}</span>
                                </div>
                                <ul class="list-disc list-inside text-xs space-y-1 text-rose-700 ps-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (! ($isPpdbOpen ?? true))
                            <div class="rounded-2xl bg-amber-50 border border-amber-300 p-8 text-amber-900 flex flex-col items-center justify-center text-center space-y-3 shadow-xs">
                                <div class="size-12 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                                    <flux:icon name="lock-closed" class="size-6" />
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-base text-amber-950">{{ __('Pendaftaran PPDB Online Saat Ini Dikunci') }}</h4>
                                    <p class="text-xs text-amber-800 mt-1 max-w-md">
                                        {{ __('Pendaftaran Santri Baru Online (PPDB) belum dibuka atau telah dikunci oleh Administrator. Silakan hubungi pengurus lembaga untuk informasi lebih lanjut.') }}
                                    </p>
                                </div>
                            </div>
                        @else
                        <form method="POST" action="{{ route('santri.register') }}" class="flex flex-col gap-10">
                            @csrf

                            {{-- 1. Data Identitas --}}
                            <div class="space-y-6">
                                <div class="flex items-center gap-3 border-b border-emerald-100 pb-3">
                                    <span class="flex size-7 items-center justify-center rounded-lg bg-emerald-700 text-xs font-black text-white">1</span>
                                    <div>
                                        <h3 class="text-lg font-extrabold text-emerald-950">{{ __('Data Pokok Calon Santri') }}</h3>
                                        <p class="text-xs text-zinc-500">{{ __('Informasi identitas santri sesuai kartu keluarga / akta.') }}</p>
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    {{-- Nama Lengkap --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="default_nama_lengkap" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Nama Lengkap Santri') }} <span class="text-rose-500">*</span>
                                        </label>
                                        <input 
                                            id="default_nama_lengkap" 
                                            name="nama_lengkap" 
                                            type="text" 
                                            value="{{ old('nama_lengkap') }}" 
                                            required 
                                            placeholder="{{ __('Contoh: Muhammad Rayhan') }}"
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('nama_lengkap') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                        @error('nama_lengkap')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Nama Panggilan --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="default_nama_panggilan" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Nama Panggilan') }}
                                        </label>
                                        <input 
                                            id="default_nama_panggilan" 
                                            name="nama_panggilan" 
                                            type="text" 
                                            value="{{ old('nama_panggilan') }}" 
                                            placeholder="{{ __('Contoh: Rayhan') }}"
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('nama_panggilan') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                        @error('nama_panggilan')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    {{-- Jenis Kelamin --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="default_jenis_kelamin" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Jenis Kelamin') }} <span class="text-rose-500">*</span>
                                        </label>
                                        <select 
                                            id="default_jenis_kelamin" 
                                            name="jenis_kelamin" 
                                            required 
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('jenis_kelamin') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 pr-10 text-sm text-zinc-900 shadow-2xs outline-none transition-all appearance-none cursor-pointer bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%23059669%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_1rem_center] bg-no-repeat"
                                        >
                                            <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }} class="bg-white text-zinc-400 py-2">{{ __('Pilih Jenis Kelamin') }}</option>
                                            @foreach ($genders as $gender)
                                                <option value="{{ $gender->value }}" {{ old('jenis_kelamin') === $gender->value ? 'selected' : '' }} class="bg-white text-zinc-900 py-2">
                                                    {{ $gender->value === 'laki_laki' ? __('Laki-laki') : __('Perempuan') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('jenis_kelamin')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Anak Ke --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="default_anak_ke" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Anak Ke-') }}
                                        </label>
                                        <input 
                                            id="default_anak_ke" 
                                            name="anak_ke" 
                                            type="number" 
                                            min="1" 
                                            value="{{ old('anak_ke', 1) }}" 
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('anak_ke') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 shadow-2xs outline-none transition-all"
                                        />
                                        @error('anak_ke')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    {{-- Tempat Lahir --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="default_tempat_lahir" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Tempat Lahir') }}
                                        </label>
                                        <input 
                                            id="default_tempat_lahir" 
                                            name="tempat_lahir" 
                                            type="text" 
                                            value="{{ old('tempat_lahir') }}" 
                                            placeholder="{{ __('Contoh: Surabaya') }}"
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('tempat_lahir') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                        @error('tempat_lahir')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Tanggal Lahir --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="default_tanggal_lahir" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Tanggal Lahir') }}
                                        </label>
                                        <input 
                                            id="default_tanggal_lahir" 
                                            name="tanggal_lahir" 
                                            type="date" 
                                            value="{{ old('tanggal_lahir') }}" 
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('tanggal_lahir') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 shadow-2xs outline-none transition-all"
                                        />
                                        @error('tanggal_lahir')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- 2. Pilihan Jenjang & Kelas --}}
                            <div class="space-y-6">
                                <div class="flex items-center gap-3 border-b border-emerald-100 pb-3">
                                    <span class="flex size-7 items-center justify-center rounded-lg bg-emerald-700 text-xs font-black text-white">2</span>
                                    <div>
                                        <h3 class="text-lg font-extrabold text-emerald-950">{{ __('Pilihan Lembaga & Kelas') }}</h3>
                                        <p class="text-xs text-zinc-500">{{ __('Tentukan unit lembaga dan kelas belajar santri.') }}</p>
                                    </div>
                                </div>

                                <div class="grid gap-6 sm:grid-cols-2">
                                    {{-- Unit Lembaga --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="default_lembaga_id" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Unit Lembaga Tujuan') }}
                                        </label>
                                        <select 
                                            id="default_lembaga_id" 
                                            name="lembaga_id" 
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('lembaga_id') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 pr-10 text-sm text-zinc-900 shadow-2xs outline-none transition-all appearance-none cursor-pointer bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%23059669%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_1rem_center] bg-no-repeat"
                                        >
                                            <option value="" disabled {{ old('lembaga_id') ? '' : 'selected' }} class="bg-white text-zinc-400 py-2">{{ __('Pilih Unit Lembaga') }}</option>
                                            @foreach ($lembagas as $lembaga)
                                                <option value="{{ $lembaga->id }}" {{ old('lembaga_id') == $lembaga->id ? 'selected' : '' }} class="bg-white text-zinc-900 py-2">
                                                    {{ $lembaga->nama }} ({{ $lembaga->jenjang }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('lembaga_id')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Kelas Tujuan --}}
                                    <div class="flex flex-col gap-2">
                                        <label for="default_kelas_id" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Pilihan Kelas / Kelompok') }}
                                        </label>
                                        <select 
                                            id="default_kelas_id" 
                                            name="kelas_id" 
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('kelas_id') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 pr-10 text-sm text-zinc-900 shadow-2xs outline-none transition-all appearance-none cursor-pointer bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%23059669%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_1rem_center] bg-no-repeat"
                                        >
                                            <option value="" disabled {{ old('kelas_id') ? '' : 'selected' }} class="bg-white text-zinc-400 py-2">{{ __('Pilih Kelas') }}</option>
                                            @foreach ($kelasList as $kelas)
                                                <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }} class="bg-white text-zinc-900 py-2">
                                                    {{ $kelas->nama }} @if($kelas->lembaga) ({{ $kelas->lembaga->jenjang }}) @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kelas_id')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- NISN / No Induk --}}
                                <div class="flex flex-col gap-2">
                                    <label for="default_noinduk" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                        {{ __('No. Induk Santri / NISN (Opsional)') }}
                                    </label>
                                    <input 
                                        id="default_noinduk" 
                                        name="noinduk" 
                                        type="text" 
                                        value="{{ old('noinduk') }}" 
                                        placeholder="{{ __('Kosongkan jika belum memiliki NISN') }}"
                                        class="w-full rounded-2xl bg-white border-2 {{ $errors->has('noinduk') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                    />
                                    @error('noinduk')
                                        <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                            <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- 3. Data Orang Tua / Wali --}}
                            <div class="space-y-6">
                                <div class="flex items-center gap-3 border-b border-emerald-100 pb-3">
                                    <span class="flex size-7 items-center justify-center rounded-lg bg-emerald-700 text-xs font-black text-white">3</span>
                                    <div>
                                        <h3 class="text-lg font-extrabold text-emerald-950">{{ __('Data Orang Tua & Kontak Wali') }}</h3>
                                        <p class="text-xs text-zinc-500">{{ __('Informasi untuk konfirmasi dan administrasi santri.') }}</p>
                                    </div>
                                </div>

                                {{-- No. WhatsApp Wali (WAJIB) --}}
                                <div class="flex flex-col gap-2">
                                    <label for="default_telepon_wali" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                        {{ __('No. WhatsApp Aktif Wali') }} <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        id="default_telepon_wali" 
                                        name="telepon_wali" 
                                        type="text" 
                                        required 
                                        value="{{ old('telepon_wali') }}" 
                                        placeholder="08xxxxxxxxxx"
                                        class="w-full rounded-2xl bg-white border-2 {{ $errors->has('telepon_wali') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                    />
                                    @error('telepon_wali')
                                        <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                            <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                {{-- Ayah --}}
                                <div class="grid gap-6 sm:grid-cols-3">
                                    <div class="flex flex-col gap-2">
                                        <label for="default_nama_ayah" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Nama Ayah') }}
                                        </label>
                                        <input 
                                            id="default_nama_ayah" 
                                            name="nama_ayah" 
                                            type="text" 
                                            value="{{ old('nama_ayah') }}" 
                                            placeholder="{{ __('Nama ayah') }}"
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('nama_ayah') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                        @error('nama_ayah')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="default_pendidikan_ayah" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Pendidikan Ayah') }}
                                        </label>
                                        <input 
                                            id="default_pendidikan_ayah" 
                                            name="pendidikan_ayah" 
                                            type="text" 
                                            value="{{ old('pendidikan_ayah') }}" 
                                            placeholder="{{ __('Contoh: S1') }}"
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('pendidikan_ayah') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="default_pekerjaan_ayah" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Pekerjaan Ayah') }}
                                        </label>
                                        <input 
                                            id="default_pekerjaan_ayah" 
                                            name="pekerjaan_ayah" 
                                            type="text" 
                                            value="{{ old('pekerjaan_ayah') }}" 
                                            placeholder="{{ __('Contoh: Wiraswasta') }}"
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('pekerjaan_ayah') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                    </div>
                                </div>

                                {{-- Ibu --}}
                                <div class="grid gap-6 sm:grid-cols-3">
                                    <div class="flex flex-col gap-2">
                                        <label for="default_nama_ibu" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Nama Ibu') }}
                                        </label>
                                        <input 
                                            id="default_nama_ibu" 
                                            name="nama_ibu" 
                                            type="text" 
                                            value="{{ old('nama_ibu') }}" 
                                            placeholder="{{ __('Nama ibu') }}"
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('nama_ibu') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                        @error('nama_ibu')
                                            <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                                <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="default_pendidikan_ibu" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Pendidikan Ibu') }}
                                        </label>
                                        <input 
                                            id="default_pendidikan_ibu" 
                                            name="pendidikan_ibu" 
                                            type="text" 
                                            value="{{ old('pendidikan_ibu') }}" 
                                            placeholder="{{ __('Contoh: SMA/D3') }}"
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('pendidikan_ibu') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="default_pekerjaan_ibu" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                            {{ __('Pekerjaan Ibu') }}
                                        </label>
                                        <input 
                                            id="default_pekerjaan_ibu" 
                                            name="pekerjaan_ibu" 
                                            type="text" 
                                            value="{{ old('pekerjaan_ibu') }}" 
                                            placeholder="{{ __('Contoh: Ibu Rumah Tangga') }}"
                                            class="w-full rounded-2xl bg-white border-2 {{ $errors->has('pekerjaan_ibu') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                        />
                                    </div>
                                </div>

                                {{-- Alamat --}}
                                <div class="flex flex-col gap-2">
                                    <label for="default_alamat" class="text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                        {{ __('Alamat Lengkap') }}
                                    </label>
                                    <textarea 
                                        id="default_alamat" 
                                        name="alamat" 
                                        rows="3" 
                                        placeholder="{{ __('Tuliskan alamat domisili santri...') }}"
                                        class="w-full rounded-2xl bg-white border-2 {{ $errors->has('alamat') ? 'border-rose-400 ring-2 ring-rose-300/30' : 'border-emerald-200 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/15' }} px-4 py-3.5 text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs outline-none transition-all"
                                    >{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                                            <flux:icon name="exclamation-circle" class="size-3.5 text-rose-500 shrink-0" />
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="flex justify-end pt-6 border-t border-emerald-100">
                                <button 
                                    type="submit" 
                                    class="inline-flex items-center gap-2.5 rounded-2xl bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-sm px-10 py-4 shadow-xl shadow-emerald-800/25 transition-all duration-300 transform hover:-translate-y-0.5 cursor-pointer"
                                >
                                    <flux:icon name="user-plus" class="size-4" />
                                    <span>{{ __('Kirim Pendaftaran Santri Baru') }}</span>
                                </button>
                            </div>

                        </form>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    @endif
</x-layouts::public>
