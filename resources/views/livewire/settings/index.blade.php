<div class="w-full space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">{{ __('Pengaturan Sistem & Website') }}</flux:heading>
            <flux:subheading size="lg">{{ __('Kelola konfigurasi lembaga, konten halaman publik, tema tampilan, dan profil akun.') }}</flux:subheading>
        </div>

        @if ($isAdmin)
            <div class="flex items-center gap-2">
                <flux:button size="sm" variant="filled" :href="route('home')" wire:navigate class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold text-xs shadow-sm">
                    <flux:icon name="pencil-square" class="size-3.5 me-1.5" />
                    {{ __('Buka Live Visual Editor') }} ↗
                </flux:button>
            </div>
        @endif
    </div>

    {{-- Clean Top Tabs Navigation Bar (Full Width) --}}
    <div class="border-b border-zinc-200 dark:border-zinc-700 w-full">
        <nav class="-mb-px flex space-x-2 sm:space-x-4 overflow-x-auto pb-1 w-full" aria-label="Tabs">
            @if ($isAdmin)
                <button
                    type="button"
                    wire:click="$set('tab', 'general')"
                    class="group inline-flex items-center gap-2 border-b-2 py-3 px-3 sm:px-4 text-xs sm:text-sm font-bold transition-all cursor-pointer whitespace-nowrap {{ $tab === 'general' ? 'border-emerald-600 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400' : 'border-transparent text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200' }}"
                >
                    <flux:icon name="building-office-2" class="size-4 shrink-0" />
                    <span>{{ __('Informasi') }}</span>
                </button>

                <button
                    type="button"
                    wire:click="$set('tab', 'pages')"
                    class="group inline-flex items-center gap-2 border-b-2 py-3 px-3 sm:px-4 text-xs sm:text-sm font-bold transition-all cursor-pointer whitespace-nowrap {{ $tab === 'pages' ? 'border-emerald-600 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400' : 'border-transparent text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200' }}"
                >
                    <flux:icon name="document-text" class="size-4 shrink-0" />
                    <span>{{ __('Konten') }}</span>
                </button>

                <button
                    type="button"
                    wire:click="$set('tab', 'appearance')"
                    class="group inline-flex items-center gap-2 border-b-2 py-3 px-3 sm:px-4 text-xs sm:text-sm font-bold transition-all cursor-pointer whitespace-nowrap {{ $tab === 'appearance' ? 'border-emerald-600 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400' : 'border-transparent text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200' }}"
                >
                    <flux:icon name="paint-brush" class="size-4 shrink-0" />
                    <span>{{ __('Tampilan') }}</span>
                </button>
            @endif

            <button
                type="button"
                wire:click="$set('tab', 'profile')"
                class="group inline-flex items-center gap-2 border-b-2 py-3 px-3 sm:px-4 text-xs sm:text-sm font-bold transition-all cursor-pointer whitespace-nowrap {{ ($tab === 'profile' || $tab === 'security') ? 'border-emerald-600 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400' : 'border-transparent text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200' }}"
            >
                <flux:icon name="user" class="size-4 shrink-0" />
                <span>{{ __('Profil') }}</span>
            </button>
        </nav>
    </div>

    {{-- ================= TAB 1: INFORMASI LEMBAGA & SISTEM (FULL WIDTH 2 COLUMNS) ================= --}}
    @if ($tab === 'general' && $isAdmin)
        <form wire:submit="saveGeneral" class="space-y-6 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start w-full">
                
                {{-- Left Column: Identitas, Logo, Kontak & Alamat --}}
                <div class="space-y-6">
                    {{-- Card 1: Logo & Favicon Browser --}}
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-5">
                        <div>
                            <flux:heading size="md">{{ __('Logo & Favicon Browser') }}</flux:heading>
                            <flux:subheading>{{ __('Unggah logo resmi lembaga dan favicon tab browser (tersimpan otomatis ke ImageKit.io).') }}</flux:subheading>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Logo --}}
                            <div class="p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/30 space-y-3">
                                <flux:heading size="xs" class="font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">{{ __('Logo Lembaga') }}</flux:heading>
                                <div class="flex items-center gap-3">
                                    <div class="size-14 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2 flex items-center justify-center shrink-0 overflow-hidden shadow-2xs">
                                        @if ($logo_upload)
                                            <img src="{{ $logo_upload->temporaryUrl() }}" alt="Preview Logo" class="max-h-full max-w-full object-contain" />
                                        @elseif ($setting?->logo_url)
                                            <img src="{{ $setting->logo_url }}" alt="Logo Lembaga" class="max-h-full max-w-full object-contain" />
                                        @else
                                            <x-app-logo-icon class="size-8 text-emerald-600 dark:text-emerald-400 fill-current" />
                                        @endif
                                    </div>
                                    <div class="flex-1 space-y-1.5">
                                        <flux:input type="file" wire:model="logo_upload" accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp" size="sm" />
                                        @if ($setting?->logo)
                                            <flux:button type="button" wire:click="removeLogo" variant="ghost" size="xs" class="text-rose-600 hover:text-rose-700">
                                                {{ __('Hapus Logo') }}
                                            </flux:button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Favicon --}}
                            <div class="p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/30 space-y-3">
                                <flux:heading size="xs" class="font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">{{ __('Favicon Tab') }}</flux:heading>
                                <div class="flex items-center gap-3">
                                    <div class="size-14 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2 flex items-center justify-center shrink-0 overflow-hidden shadow-2xs">
                                        @if ($favicon_upload)
                                            <img src="{{ $favicon_upload->temporaryUrl() }}" alt="Preview Favicon" class="size-7 object-contain" />
                                        @elseif ($setting?->favicon_url)
                                            <img src="{{ $setting->favicon_url }}" alt="Favicon Lembaga" class="size-7 object-contain" />
                                        @else
                                            <img src="/favicon.ico" alt="Default Favicon" class="size-7 object-contain" />
                                        @endif
                                    </div>
                                    <div class="flex-1 space-y-1.5">
                                        <flux:input type="file" wire:model="favicon_upload" accept=".ico,image/png,image/jpeg,image/svg+xml" size="sm" />
                                        @if ($setting?->favicon)
                                            <flux:button type="button" wire:click="removeFavicon" variant="ghost" size="xs" class="text-rose-600 hover:text-rose-700">
                                                {{ __('Hapus Favicon') }}
                                            </flux:button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Identitas & Kontak Operasional --}}
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-4">
                        <div>
                            <flux:heading size="md">{{ __('Identitas & Kontak Lembaga') }}</flux:heading>
                            <flux:subheading>{{ __('Informasi resmi yang tercantum di header, footer, dan cetakan laporan.') }}</flux:subheading>
                        </div>

                        <flux:input wire:model="lembaga" :label="__('Nama Lembaga / Yayasan')" required />

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <flux:input wire:model="nsm" :label="__('NSM / Nomor Statistik (opsional)')" placeholder="Contoh: 111232..." />
                            <flux:input wire:model="telepon" :label="__('Nomor Telepon / Hotline')" placeholder="0812-xxxx-xxxx" required />
                        </div>

                        <flux:input wire:model="email_lembaga" type="email" :label="__('Alamat Email Resmi')" placeholder="sekretariat@lembaga.sch.id" required />
                        <flux:textarea wire:model="alamat" :label="__('Alamat Lengkap Kantor / Kampus')" rows="2" placeholder="Jl. Raya No. ..., Kelurahan ..., Kecamatan ..., Kota ..." required />
                        <flux:input wire:model="google_maps_url" type="url" :label="__('URL Google Maps Embed')" placeholder="https://www.google.com/maps/embed?pb=..." description="{{ __('Salin URL embed dari Google Maps → Bagikan → Sematkan peta') }}" />
                    </div>
                </div>

                {{-- Right Column: SEO, Kontrol Akses, Penggajian & WhatsApp --}}
                <div class="space-y-6">
                    {{-- Card 3: Buka / Kunci Fitur Sistem --}}
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-4">
                        <div>
                            <flux:heading size="md">{{ __('Buka / Kunci Fitur Sistem') }}</flux:heading>
                            <flux:subheading>{{ __('Kontrol izin akses penginputan nilai dan pendaftaran publik.') }}</flux:subheading>
                        </div>

                        <div class="space-y-3 pt-1">
                            <flux:switch wire:model="is_input_nilai_open" :label="__('Akses Input Nilai Guru')" description="{{ __('Buka/tutup penginputan nilai rapor dan nilai harian oleh guru.') }}" />
                            <flux:separator />
                            <flux:switch wire:model="is_ppdb_open" :label="__('Pendaftaran Santri Baru Online (PPDB)')" description="{{ __('Buka/tutup formulir pendaftaran santri baru publik.') }}" />
                        </div>
                    </div>

                    {{-- Card 4: Search Engine Optimization (SEO) --}}
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-4">
                        <div>
                            <flux:heading size="md">{{ __('Search Engine Optimization (SEO)') }}</flux:heading>
                            <flux:subheading>{{ __('Informasi deskripsi dan keyword mesin pencari Google.') }}</flux:subheading>
                        </div>

                        <flux:textarea wire:model="meta_deskripsi" :label="__('Meta Deskripsi')" rows="2" placeholder="Ringkasan singkat tentang profil lembaga untuk Google Search..." />
                        <flux:input wire:model="meta_keyword" :label="__('Meta Keywords')" placeholder="pesantren, madrasah, tahfidz, spmb" />
                    </div>

                    {{-- Card 5: Penggajian & WhatsApp Gateway --}}
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-4">
                        <div>
                            <flux:heading size="md">{{ __('Penggajian & WhatsApp') }}</flux:heading>
                            <flux:subheading>{{ __('Aturan cutoff payroll dan integrasi notifikasi WA.') }}</flux:subheading>
                        </div>

                        <flux:input wire:model="payroll_cutoff_day" type="number" min="1" max="31" :label="__('Tanggal Cutoff Penggajian (1 - 31)')" required />

                        <flux:separator />

                        <flux:switch wire:model="fitur_pesan_whatsapp" :label="__('Aktifkan Notifikasi WhatsApp')" description="{{ __('Kirim pesan presensi dan tagihan otomatis ke wali santri.') }}" />
                        <flux:textarea wire:model="pesan_whatsapp" :label="__('Template Pesan Default')" rows="2" placeholder="Assalamu'alaikum wr. wb..." />
                    </div>
                </div>
            </div>

            <div class="flex justify-end p-4 rounded-2xl bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-200 dark:border-zinc-700 shadow-xs w-full">
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                    {{ __('Simpan Semua Pengaturan Lembaga') }}
                </flux:button>
            </div>
        </form>
    @endif

    {{-- ================= TAB 2: KONTEN HALAMAN PUBLIK (FULL WIDTH 3 COLUMNS) ================= --}}
    @if ($tab === 'pages' && $isAdmin)
        <form wire:submit="savePageContent" class="space-y-6 w-full">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 w-full">
                <div class="flex items-center gap-3">
                    <div class="size-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                        <flux:icon name="document-text" class="size-5" />
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-emerald-950 dark:text-emerald-200">{{ __('Kustomisasi Konten Halaman Publik (:theme Theme)', ['theme' => ucfirst($landing_theme)]) }}</h4>
                        <p class="text-[11px] text-zinc-600 dark:text-zinc-400 mt-0.5">{{ __('Atur judul, subjudul, visi, misi, dan foto latar untuk masing-masing halaman website.') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <flux:button type="submit" variant="primary" size="sm" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                        {{ __('Simpan Konten Halaman') }}
                    </flux:button>
                </div>
            </div>

            {{-- Full Width 3-Column Grid of Pages --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 items-start w-full">
                
                {{-- Kolom 1: 🏠 Halaman Beranda (Home) --}}
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="size-2.5 rounded-full bg-emerald-500"></span>
                            <flux:heading size="md">{{ __('Halaman Beranda') }}</flux:heading>
                        </div>
                        <a href="{{ route('home') }}" target="_blank" class="text-[11px] font-bold text-emerald-600 hover:underline">
                            / (Home) ↗
                        </a>
                    </div>

                    <flux:input wire:model="content.hero_badge" :label="__('Badge Hero Atas')" placeholder="Tagline / Badge Atas" />
                    <flux:input wire:model="content.hero_title" :label="__('Judul Utama Headline')" placeholder="Nama Lembaga / Headline" />
                    <flux:textarea wire:model="content.hero_subtitle" :label="__('Subjudul Hero')" rows="3" placeholder="Deskripsi pengantar hero..." />

                    @if ($landing_theme === 'pixigon')
                        <flux:input wire:model="content.hero_cta_text" :label="__('Teks Tombol CTA')" />
                        <flux:input wire:model="content.about_title" :label="__('Judul Bagian Tentang')" />
                        <flux:textarea wire:model="content.about_subtitle" :label="__('Deskripsi Bagian Tentang')" rows="2" />
                    @else
                        <div class="grid grid-cols-2 gap-3">
                            <flux:input wire:model="content.hero_cta_primary_text" :label="__('Tombol Utama')" />
                            <flux:input wire:model="content.hero_cta_secondary_text" :label="__('Tombol Kedua')" />
                        </div>
                        <flux:input wire:model="content.why_us_title" :label="__('Judul Mengapa Memilih Kami')" />
                        <flux:textarea wire:model="content.why_us_subtitle" :label="__('Deskripsi Keunggulan')" rows="2" />
                    @endif
                </div>

                {{-- Kolom 2: 📚 Halaman Program Pendidikan --}}
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="size-2.5 rounded-full bg-sky-500"></span>
                            <flux:heading size="md">{{ __('Halaman Program') }}</flux:heading>
                        </div>
                        <a href="{{ route('program') }}" target="_blank" class="text-[11px] font-bold text-sky-600 hover:underline">
                            /program ↗
                        </a>
                    </div>

                    <flux:input wire:model="content.page_program_title" :label="__('Judul Banner Program')" placeholder="Program & Kurikulum" />
                    <flux:textarea wire:model="content.page_program_subtitle" :label="__('Subjudul Halaman Program')" rows="3" placeholder="Pilihan jenjang pendidikan..." />
                    <flux:input wire:model="content.page_program_banner_image" :label="__('URL Background Banner Program')" placeholder="https://images.unsplash.com/..." />

                    <div class="p-3 rounded-xl bg-sky-50/60 dark:bg-sky-950/20 border border-sky-200/60 dark:border-sky-900 text-xs text-sky-900 dark:text-sky-300">
                        <div class="flex items-center gap-1.5 font-bold mb-1">
                            <flux:icon name="information-circle" class="size-4" />
                            <span>{{ __('Daftar Program Pendidikan') }}</span>
                        </div>
                        <p class="text-[11px] leading-relaxed text-zinc-600 dark:text-zinc-400">
                            {{ __('Daftar kurikulum program diambil langsung dari database. Atur melalui menu') }}
                            <a href="{{ route('konten.programs') }}" wire:navigate class="font-bold underline text-sky-600 hover:text-sky-700">{{ __('Konten → Program') }}</a>.
                        </p>
                    </div>
                </div>

                {{-- Kolom 3: 🏛️ Halaman Tentang Kami --}}
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="size-2.5 rounded-full bg-amber-500"></span>
                            <flux:heading size="md">{{ __('Halaman Tentang Kami') }}</flux:heading>
                        </div>
                        <a href="{{ route('about') }}" target="_blank" class="text-[11px] font-bold text-amber-600 hover:underline">
                            /tentang ↗
                        </a>
                    </div>

                    <flux:input wire:model="content.page_about_title" :label="__('Judul Banner Tentang')" placeholder="Tentang Kami" />
                    <flux:textarea wire:model="content.page_about_subtitle" :label="__('Subjudul Halaman Tentang')" rows="2" />
                    <flux:textarea wire:model="content.page_about_visi" :label="__('Visi Utama Lembaga')" rows="2" />
                    <flux:textarea wire:model="content.page_about_misi" :label="__('Misi Lembaga (Gunakan baris baru untuk nomor)')" rows="3" />
                    <flux:input wire:model="content.page_about_banner_image" :label="__('URL Background Banner')" placeholder="https://images.unsplash.com/..." />
                    <flux:input wire:model="content.page_about_building_image" :label="__('URL Foto Gedung / Fasilitas')" placeholder="https://images.unsplash.com/..." />
                </div>

                {{-- Kolom 4: 📞 Halaman Kontak & Sekretariat --}}
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="size-2.5 rounded-full bg-rose-500"></span>
                            <flux:heading size="md">{{ __('Halaman Kontak') }}</flux:heading>
                        </div>
                        <a href="{{ route('contact.show') }}" target="_blank" class="text-[11px] font-bold text-rose-600 hover:underline">
                            /kontak ↗
                        </a>
                    </div>

                    <flux:input wire:model="content.page_contact_title" :label="__('Judul Banner Kontak')" placeholder="Hubungi Kami" />
                    <flux:textarea wire:model="content.page_contact_subtitle" :label="__('Subjudul Halaman Kontak')" rows="3" />
                    <flux:input wire:model="content.page_contact_banner_image" :label="__('URL Background Banner Kontak')" placeholder="https://images.unsplash.com/..." />
                </div>

                {{-- Kolom 5: 🖼️ Halaman Galeri & Dokumentasi --}}
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="size-2.5 rounded-full bg-violet-500"></span>
                            <flux:heading size="md">{{ __('Halaman Galeri') }}</flux:heading>
                        </div>
                        <a href="{{ route('galeri') }}" target="_blank" class="text-[11px] font-bold text-violet-600 hover:underline">
                            /galeri ↗
                        </a>
                    </div>

                    <flux:input wire:model="content.page_gallery_title" :label="__('Judul Banner Galeri')" placeholder="Galeri Dokumentasi" />
                    <flux:textarea wire:model="content.page_gallery_subtitle" :label="__('Subjudul Halaman Galeri')" rows="3" />
                    <flux:input wire:model="content.page_gallery_banner_image" :label="__('URL Background Banner Galeri')" placeholder="https://images.unsplash.com/..." />
                </div>

                {{-- Kolom 6: 🎨 Foto Background Slider & Media Hero --}}
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="size-2.5 rounded-full bg-teal-500"></span>
                            <flux:heading size="md">{{ __('Background & Hero Slides') }}</flux:heading>
                        </div>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">
                            Media URLs
                        </span>
                    </div>

                    @if ($landing_theme === 'pixigon')
                        <flux:input wire:model="content.hero_student_image_left" :label="__('Foto Santriwati (Kiri)')" placeholder="https://images.unsplash.com/..." />
                        <flux:input wire:model="content.hero_student_image_right" :label="__('Foto Santriwan (Kanan)')" placeholder="https://images.unsplash.com/..." />
                        <flux:input wire:model="content.about_facility_image" :label="__('Foto Fasilitas Pembelajaran')" placeholder="https://images.unsplash.com/..." />
                    @else
                        <flux:input wire:model="content.hero_slide_1_image" :label="__('Foto Hero Slider 1')" placeholder="https://images.unsplash.com/..." />
                        <flux:input wire:model="content.hero_slide_2_image" :label="__('Foto Hero Slider 2')" placeholder="https://images.unsplash.com/..." />
                        <flux:input wire:model="content.hero_slide_3_image" :label="__('Foto Hero Slider 3')" placeholder="https://images.unsplash.com/..." />
                        <flux:input wire:model="content.why_us_image" :label="__('Foto Mengapa Memilih Kami')" placeholder="https://images.unsplash.com/..." />
                    @endif
                </div>

            </div>

            <div class="flex justify-end p-4 rounded-2xl bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-200 dark:border-zinc-700 shadow-xs w-full">
                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                    {{ __('Simpan Semua Konten Halaman') }}
                </flux:button>
            </div>
        </form>
    @endif

    {{-- ================= TAB 3: TAMPILAN (APPEARANCE - FULL WIDTH) ================= --}}
    @if ($tab === 'appearance' && $isAdmin)
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-xs space-y-8 w-full">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                    {{-- Opsi 1: Tema Default (Klasik Emerald) --}}
                    <label class="relative flex flex-col p-6 rounded-2xl border-2 cursor-pointer transition-all duration-200 {{ $landing_theme === 'default' ? 'border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/20 dark:border-emerald-500 shadow-md ring-2 ring-emerald-500/20' : 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="size-3 rounded-full bg-emerald-500"></span>
                                <span class="font-bold text-sm text-zinc-900 dark:text-zinc-100">{{ __('Tema Default') }}</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">{{ __('Klasik Emerald') }}</span>
                            </div>
                            <input type="radio" wire:model.live="landing_theme" value="default" class="text-emerald-600 focus:ring-emerald-500 size-4">
                        </div>
                        
                        {{-- Mini Preview Box --}}
                        <div class="h-32 w-full rounded-xl bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-950 p-3.5 flex flex-col justify-between overflow-hidden shadow-inner border border-emerald-700/50">
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
                    <label class="relative flex flex-col p-6 rounded-2xl border-2 cursor-pointer transition-all duration-200 {{ $landing_theme === 'pixigon' ? 'border-[#6bb82d] bg-[#f0f8ec]/60 dark:bg-lime-950/20 dark:border-[#6bb82d] shadow-md ring-2 ring-lime-500/20' : 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="size-3 rounded-full bg-[#6bb82d]"></span>
                                <span class="font-bold text-sm text-zinc-900 dark:text-zinc-100">{{ __('Tema Pixigon') }}</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-lime-100 text-lime-800 dark:bg-lime-900/60 dark:text-lime-300">{{ __('Soft Green Light') }}</span>
                            </div>
                            <input type="radio" wire:model.live="landing_theme" value="pixigon" class="text-[#6bb82d] focus:ring-[#6bb82d] size-4">
                        </div>

                        {{-- Mini Preview Box --}}
                        <div class="h-32 w-full rounded-xl bg-[#f0f8ec] p-3.5 flex flex-col justify-between overflow-hidden shadow-inner border border-[#d6eda6]">
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
                            {{ __('Desain modern academy nuansa soft green light dengan doodle botani, navbar seamless, kartu review miring, dan dark forest footer.') }}
                        </p>
                    </label>
                </div>
            </div>
        </div>
    @endif

    {{-- ================= TAB 4: PROFIL (PROFIL, KATA SANDI & KEAMANAN 2FA TERPADU) ================= --}}
    @if ($tab === 'profile' || $tab === 'security')
        <div class="space-y-6 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start w-full">
                
                {{-- Left Column: Informasi Profil & 2FA --}}
                <div class="space-y-6">
                    {{-- Section 1: Informasi Profil Akun --}}
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-xs space-y-6">
                        <div class="flex items-center gap-3 border-b border-zinc-100 dark:border-zinc-800 pb-4">
                            <div class="size-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                <flux:icon name="user" class="size-5" />
                            </div>
                            <div>
                                <flux:heading size="md">{{ __('Informasi Profil') }}</flux:heading>
                                <flux:subheading>{{ __('Perbarui nama lengkap dan alamat email akun Anda.') }}</flux:subheading>
                            </div>
                        </div>

                        <form wire:submit="updateProfileInformation" class="space-y-4">
                            <flux:input wire:model="name" :label="__('Nama Lengkap')" type="text" required autofocus autocomplete="name" />

                            <div>
                                <flux:input wire:model="email" :label="__('Alamat Email')" type="email" required autocomplete="email" />

                                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                                    <div class="mt-3 p-3 rounded-xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800 text-xs text-amber-800 dark:text-amber-300 flex items-center justify-between">
                                        <span>{{ __('Alamat email Anda belum terverifikasi.') }}</span>
                                        <flux:button size="xs" variant="filled" wire:click.prevent="resendVerificationNotification">
                                            {{ __('Kirim Ulang Email Verifikasi') }}
                                        </flux:button>
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-end pt-2">
                                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                                    {{ __('Simpan Perubahan Profil') }}
                                </flux:button>
                            </div>
                        </form>
                    </div>

                    {{-- Section 2: Autentikasi Dua Faktor (2FA) --}}
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-xs space-y-6">
                        <div class="flex items-center gap-3 border-b border-zinc-100 dark:border-zinc-800 pb-4">
                            <div class="size-10 rounded-xl bg-violet-100 dark:bg-violet-950/50 text-violet-600 dark:text-violet-400 flex items-center justify-center shrink-0">
                                <flux:icon name="shield-check" class="size-5" />
                            </div>
                            <div>
                                <flux:heading size="md">{{ __('Autentikasi Dua Faktor (2FA)') }}</flux:heading>
                                <flux:subheading>{{ __('Tambahkan lapisan keamanan ekstra pada akun menggunakan Google Authenticator atau aplikasi TOTP.') }}</flux:subheading>
                            </div>
                        </div>

                        @if (auth()->user()->two_factor_confirmed_at)
                            <div class="space-y-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800">
                                <div class="flex items-center gap-2 text-emerald-800 dark:text-emerald-300 font-semibold text-xs">
                                    <flux:icon name="shield-check" class="size-5 text-emerald-600 dark:text-emerald-400" />
                                    <span>{{ __('Autentikasi dua faktor saat ini aktif dan melindungi akun Anda.') }}</span>
                                </div>

                                <details class="text-xs text-zinc-600 dark:text-zinc-400 cursor-pointer">
                                    <summary class="font-bold hover:underline">{{ __('Tampilkan Kode Pemulihan Cadangan (Recovery Codes)') }}</summary>
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

                {{-- Right Column: Perbarui Kata Sandi --}}
                <div class="space-y-6">
                    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-xs space-y-6">
                        <div class="flex items-center gap-3 border-b border-zinc-100 dark:border-zinc-800 pb-4">
                            <div class="size-10 rounded-xl bg-amber-100 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                                <flux:icon name="key" class="size-5" />
                            </div>
                            <div>
                                <flux:heading size="md">{{ __('Keamanan & Kata Sandi') }}</flux:heading>
                                <flux:subheading>{{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak demi keamanan.') }}</flux:subheading>
                            </div>
                        </div>

                        <form wire:submit="updatePassword" class="space-y-4">
                            <flux:input
                                wire:model="current_password"
                                :label="__('Kata Sandi Saat Ini')"
                                type="password"
                                required
                                autocomplete="current-password"
                            />

                            <flux:input
                                wire:model="password"
                                :label="__('Kata Sandi Baru')"
                                type="password"
                                required
                                autocomplete="new-password"
                            />

                            <flux:input
                                wire:model="password_confirmation"
                                :label="__('Konfirmasi Kata Sandi Baru')"
                                type="password"
                                required
                                autocomplete="new-password"
                            />

                            <div class="flex justify-end pt-2">
                                <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                                    {{ __('Perbarui Kata Sandi') }}
                                </flux:button>
                            </div>
                        </form>
                    </div>
                </div>

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
