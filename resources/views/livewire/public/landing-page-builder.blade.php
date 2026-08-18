<div>
    @if ($this->isAdmin)
        {{-- Custom Styles for Builder Canvas, Inline Editor & Locked Database Indicators --}}
        <style>
            .siska-builder-active [data-editable-field] {
                position: relative;
                outline: 2px dashed rgba(16, 185, 129, 0.45);
                outline-offset: 3px;
                border-radius: 6px;
                transition: all 0.15s ease-in-out;
                cursor: text !important;
            }
            .siska-builder-active [data-editable-field]:hover {
                outline: 2px solid #10b981;
                background-color: rgba(16, 185, 129, 0.08);
            }
            .siska-builder-active [data-editable-field]:focus {
                outline: 2px solid #059669;
                background-color: rgba(16, 185, 129, 0.12);
                box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
            }

            .siska-builder-active [data-editable-image] {
                position: relative;
                outline: 2px dashed rgba(245, 158, 11, 0.6);
                outline-offset: 4px;
                border-radius: 12px;
                transition: all 0.2s ease-in-out;
            }
            .siska-builder-active [data-editable-image]:hover {
                outline: 2px solid #f59e0b;
            }

            .siska-builder-active [data-db-locked="true"] {
                position: relative;
                outline: 2px dashed rgba(59, 130, 246, 0.5);
                outline-offset: 6px;
                border-radius: 16px;
            }
            .siska-builder-active [data-db-locked="true"]::before {
                content: "🔒 Terkunci (Data Otomatis dari Database / Sistem)";
                position: absolute;
                top: -14px;
                right: 18px;
                background: #1e3a8a;
                color: #bfdbfe;
                font-size: 11px;
                font-weight: 700;
                padding: 3px 10px;
                border-radius: 9999px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                z-index: 40;
                pointer-events: none;
                letter-spacing: 0.025em;
                border: 1px solid rgba(147, 197, 253, 0.3);
            }

            .siska-image-badge-btn {
                position: absolute;
                top: 12px;
                left: 12px;
                z-index: 30;
                background: rgba(15, 23, 42, 0.85);
                color: #fef08a;
                border: 1px solid rgba(245, 158, 11, 0.4);
                backdrop-filter: blur(8px);
                border-radius: 9999px;
                padding: 5px 12px;
                font-size: 11px;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 6px;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
                cursor: pointer;
                transition: all 0.2s ease;
            }
            .siska-image-badge-btn:hover {
                background: #f59e0b;
                color: #0f172a;
                transform: scale(1.04);
            }
        </style>

        {{-- Alpine.js Live Visual Editor Engine --}}
        <div
            x-data="{
                isEdit: @entangle('isEditMode'),
                init() {
                    this.$watch('isEdit', val => this.handleModeChange(val));
                    if (this.isEdit) {
                        this.$nextTick(() => this.handleModeChange(true));
                    }
                },
                handleModeChange(active) {
                    if (active) {
                        document.body.classList.add('siska-builder-active');
                        this.setupInlineEditing();
                    } else {
                        document.body.classList.remove('siska-builder-active');
                        this.teardownInlineEditing();
                    }
                },
                setupInlineEditing() {
                    // 1. Setup inline text contenteditable
                    document.querySelectorAll('[data-editable-field]').forEach(el => {
                        el.setAttribute('contenteditable', 'true');
                        el.setAttribute('title', 'Klik untuk mengedit teks secara langsung');
                        
                        if (!el.__siska_bound) {
                            el.__siska_bound = true;
                            
                            el.addEventListener('blur', (e) => {
                                if (!this.isEdit) return;
                                const field = el.getAttribute('data-editable-field');
                                const value = el.innerText.trim();
                                if (field) {
                                    $wire.updateSingleField(field, value);
                                }
                            });

                            el.addEventListener('keydown', (e) => {
                                if (!this.isEdit) return;
                                if (e.key === 'Enter' && !e.shiftKey && !el.classList.contains('whitespace-pre-line') && el.tagName !== 'P') {
                                    e.preventDefault();
                                    el.blur();
                                }
                            });
                        }
                    });

                    // 2. Setup image change overlays
                    document.querySelectorAll('[data-editable-image]').forEach(container => {
                        if (!container.querySelector('.siska-image-badge-btn')) {
                            const field = container.getAttribute('data-editable-image');
                            const label = container.getAttribute('data-image-label') || 'Ganti Gambar / Background';
                            
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'siska-image-badge-btn';
                            btn.innerHTML = `<span>🖼️</span> <span>${label}</span>`;
                            btn.addEventListener('click', (e) => {
                                e.preventDefault();
                                e.stopPropagation();
                                $wire.openImageEditor(field, label);
                            });
                            
                            if (getComputedStyle(container).position === 'static') {
                                container.style.position = 'relative';
                            }
                            container.appendChild(btn);
                        }
                    });
                },
                teardownInlineEditing() {
                    document.querySelectorAll('[data-editable-field]').forEach(el => {
                        el.removeAttribute('contenteditable');
                        el.removeAttribute('title');
                    });
                    document.querySelectorAll('.siska-image-badge-btn').forEach(btn => btn.remove());
                }
            }"
            x-init="init()"
        ></div>

        {{-- Floating Bottom Admin Toolbar --}}
        <div class="fixed bottom-6 inset-x-0 z-50 flex justify-center px-4 pointer-events-none">
            <div class="pointer-events-auto flex flex-wrap items-center gap-3 rounded-2xl border border-emerald-500/40 bg-zinc-950/95 px-5 py-3 shadow-2xl backdrop-blur-xl text-white">
                
                {{-- Admin Badge --}}
                <div class="flex items-center gap-2 pr-2 border-r border-zinc-700">
                    <span class="size-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-bold text-emerald-300">ADMIN LIVE BUILDER</span>
                    <span class="text-[11px] font-mono px-2 py-0.5 rounded bg-zinc-800 text-zinc-300">
                        {{ ucfirst($theme) }}
                    </span>
                </div>

                {{-- Toggle Mode Visual Button --}}
                <button
                    type="button"
                    wire:click="toggleEditMode"
                    class="inline-flex items-center gap-2 rounded-xl px-3.5 py-1.5 text-xs font-bold transition-all {{ $isEditMode ? 'bg-emerald-500 text-zinc-950 shadow-lg shadow-emerald-500/30 ring-2 ring-emerald-300' : 'bg-zinc-800 hover:bg-zinc-700 text-zinc-200' }}"
                >
                    <flux:icon :name="$isEditMode ? 'eye' : 'pencil-square'" class="size-4" />
                    <span>{{ $isEditMode ? __('Mode Visual Aktif (Klik Teks di Halaman)') : __('Nyalakan Visual Editor') }}</span>
                </button>

                {{-- Open Editor Drawer --}}
                <button
                    type="button"
                    wire:click="openDrawer('hero')"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-700/90 hover:bg-emerald-600 px-3.5 py-1.5 text-xs font-bold text-white transition-all shadow-sm"
                >
                    <flux:icon name="adjustments-horizontal" class="size-4" />
                    <span>{{ __('Panel Pengaturan') }}</span>
                </button>

                {{-- Save All Button --}}
                <button
                    type="button"
                    wire:click="save"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 px-4 py-1.5 text-xs font-extrabold text-zinc-950 transition-all shadow-md"
                >
                    <flux:icon name="check" class="size-4" />
                    <span>{{ __('Simpan Semua') }}</span>
                </button>

                {{-- Reset Default Button --}}
                <button
                    type="button"
                    x-on:click="$flux.modal('confirm-reset-builder-modal').show()"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-zinc-800/80 hover:bg-red-950/80 hover:text-red-300 px-3 py-1.5 text-xs font-semibold text-zinc-400 transition-all"
                    title="{{ __('Reset ke Teks Default') }}"
                >
                    <flux:icon name="arrow-path" class="size-3.5" />
                    <span>{{ __('Reset') }}</span>
                </button>
            </div>
        </div>

        {{-- 1. QUICK IMAGE EDITOR SLIDER (Ganti Background & Foto) --}}
        <flux:modal name="quick-image-editor-modal" flyout class="w-full md:w-[540px] max-w-full space-y-5">
            <div class="space-y-5">
                <div>
                    <div class="flex items-center gap-2">
                        <flux:badge size="sm" color="amber">🖼️ Ganti Gambar / Background</flux:badge>
                        <flux:badge size="sm" color="zinc">{{ $editingImageTitle }}</flux:badge>
                    </div>
                    <flux:heading size="lg" class="mt-2">{{ $editingImageTitle }}</flux:heading>
                    <flux:subheading>{{ __('Masukkan URL gambar kustom atau pilih langsung dari preset foto Islami berkualitas tinggi di bawah ini.') }}</flux:subheading>
                </div>

                {{-- Live Image Preview --}}
                <div class="space-y-2">
                    <label class="text-xs font-bold text-zinc-700 dark:text-zinc-300">{{ __('Pratinjau Gambar Saat Ini:') }}</label>
                    <div class="h-44 rounded-2xl overflow-hidden border border-zinc-200 dark:border-zinc-700 bg-zinc-900 relative shadow-inner">
                        @if (!empty($editingImageUrl))
                            <img src="{{ $editingImageUrl }}" alt="Preview" class="size-full object-cover" />
                        @else
                            <div class="size-full flex flex-col items-center justify-center text-zinc-500 text-xs">
                                <flux:icon name="photo" class="size-8 mb-1 opacity-50" />
                                <span>{{ __('Belum ada URL gambar yang dimasukkan') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- URL Input --}}
                <flux:input 
                    wire:model.live.debounce.300ms="editingImageUrl" 
                    :label="__('URL Link Gambar')" 
                    placeholder="https://images.unsplash.com/..." 
                />

                {{-- Preset Images Gallery --}}
                <div class="space-y-2">
                    <label class="text-xs font-bold text-zinc-700 dark:text-zinc-300 flex items-center justify-between">
                        <span>{{ __('Pilih Cepat dari Preset Foto Islami Siap Pakai:') }}</span>
                        <span class="text-[11px] font-normal text-zinc-500">{{ __('(Klik untuk menerapkan)') }}</span>
                    </label>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 max-h-48 overflow-y-auto p-1 border border-zinc-200 dark:border-zinc-700 rounded-xl bg-zinc-50 dark:bg-zinc-800/40">
                        @foreach ($presetImages as $preset)
                            <button
                                type="button"
                                wire:click="selectPresetImage('{{ $preset['url'] }}')"
                                class="group relative rounded-lg overflow-hidden border-2 transition-all {{ $editingImageUrl === $preset['url'] ? 'border-emerald-500 ring-2 ring-emerald-400' : 'border-transparent hover:border-emerald-400' }} text-left"
                            >
                                <img src="{{ $preset['url'] }}" alt="{{ $preset['title'] }}" class="h-16 w-full object-cover group-hover:scale-105 transition" />
                                <div class="p-1 bg-white dark:bg-zinc-900 text-[10px] font-medium truncate text-zinc-700 dark:text-zinc-300">
                                    {{ $preset['title'] }}
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                    </flux:modal.close>
                    
                    <flux:button wire:click="saveImage" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                        {{ __('Terapkan & Simpan Gambar') }}
                    </flux:button>
                </div>
            </div>
        </flux:modal>

        {{-- 2. QUICK TEXT EDITOR SLIDER (Edit Teks Popup) --}}
        <flux:modal name="quick-text-editor-modal" flyout class="w-full md:w-[480px] max-w-full space-y-4">
            <div class="space-y-4">
                <div>
                    <flux:badge size="sm" color="emerald">✏️ Edit Teks</flux:badge>
                    <flux:heading size="lg" class="mt-2">{{ $editingTextLabel }}</flux:heading>
                </div>

                <flux:textarea 
                    wire:model="editingTextValue" 
                    :label="__('Isi Teks / Konten')" 
                    rows="4" 
                />

                <div class="flex items-center justify-between pt-3 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                    </flux:modal.close>
                    
                    <flux:button wire:click="saveQuickText" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                        {{ __('Simpan Perubahan') }}
                    </flux:button>
                </div>
            </div>
        </flux:modal>

        {{-- 3. FLYOUT DRAWER EDITOR FOR ALL PUBLIC PAGES --}}
        <flux:modal name="landing-builder-drawer" flyout class="w-full md:w-[560px] max-w-full">
            <div class="flex flex-col gap-5 h-full">
                <div>
                    <div class="flex items-center gap-2">
                        <flux:badge size="sm" color="emerald">{{ __('Visual Page Builder') }}</flux:badge>
                        <flux:badge size="sm" color="zinc">{{ ucfirst($theme) }} Theme</flux:badge>
                    </div>
                    <flux:heading size="lg" class="mt-2">{{ __('Kustomisasi Seluruh Halaman Publik') }}</flux:heading>
                    <flux:subheading>{{ __('Atur teks judul, subjudul, visi-misi, serta foto latar untuk Beranda, Program, Tentang Kami, Kontak, dan Galeri.') }}</flux:subheading>
                </div>

                {{-- Section Tabs --}}
                <div class="flex flex-wrap gap-1 p-1 rounded-xl bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                    <button
                        type="button"
                        wire:click="$set('activeTab', 'hero')"
                        class="flex-1 py-1.5 px-2.5 rounded-lg text-xs font-bold transition {{ $activeTab === 'hero' ? 'bg-white dark:bg-zinc-900 text-emerald-600 dark:text-emerald-400 shadow-xs' : 'text-zinc-500 hover:text-zinc-800' }}"
                    >
                        {{ __('Beranda') }}
                    </button>
                    <button
                        type="button"
                        wire:click="$set('activeTab', 'pages')"
                        class="flex-1 py-1.5 px-2.5 rounded-lg text-xs font-bold transition {{ $activeTab === 'pages' ? 'bg-white dark:bg-zinc-900 text-emerald-600 dark:text-emerald-400 shadow-xs' : 'text-zinc-500 hover:text-zinc-800' }}"
                    >
                        {{ __('Halaman') }}
                    </button>
                    <button
                        type="button"
                        wire:click="$set('activeTab', 'images')"
                        class="flex-1 py-1.5 px-2.5 rounded-lg text-xs font-bold transition {{ $activeTab === 'images' ? 'bg-white dark:bg-zinc-900 text-emerald-600 dark:text-emerald-400 shadow-xs' : 'text-zinc-500 hover:text-zinc-800' }}"
                    >
                        {{ __('🖼️ Background') }}
                    </button>
                    <button
                        type="button"
                        wire:click="$set('activeTab', 'cta')"
                        class="flex-1 py-1.5 px-2.5 rounded-lg text-xs font-bold transition {{ $activeTab === 'cta' ? 'bg-white dark:bg-zinc-900 text-emerald-600 dark:text-emerald-400 shadow-xs' : 'text-zinc-500 hover:text-zinc-800' }}"
                    >
                        {{ __('CTA & Footer') }}
                    </button>
                </div>

                <form wire:submit="save" class="flex-1 overflow-y-auto pr-1 space-y-4">
                    {{-- 1. TAB BERANDA / HERO --}}
                    @if ($activeTab === 'hero')
                        <div class="space-y-4">
                            <flux:input wire:model="content.hero_badge" :label="__('Badge / Tagline Atas')" placeholder="Sistem Informasi Terpadu" />
                            <flux:input wire:model="content.hero_title" :label="__('Judul Utama Headline')" placeholder="Nama Lembaga / Slogan Utama" />
                            <flux:textarea wire:model="content.hero_subtitle" :label="__('Subjudul / Paragraf Hero')" rows="3" />

                            @if ($theme === 'pixigon')
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <flux:input wire:model="content.hero_cta_text" :label="__('Teks Tombol CTA')" />
                                    <flux:input wire:model="content.hero_cta_url" :label="__('Link Tombol CTA')" />
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <flux:input wire:model="content.hero_cta_primary_text" :label="__('Teks Tombol Utama')" />
                                    <flux:input wire:model="content.hero_cta_secondary_text" :label="__('Teks Tombol Kedua')" />
                                </div>
                            @endif

                            <div class="p-3.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 space-y-3">
                                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Section Keunggulan & Mengapa Memilih Kami</span>
                                @if ($theme === 'pixigon')
                                    <flux:input wire:model="content.about_title" :label="__('Judul Keunggulan')" size="sm" />
                                    <flux:textarea wire:model="content.about_subtitle" :label="__('Deskripsi Keunggulan')" rows="2" size="sm" />
                                @else
                                    <flux:input wire:model="content.why_us_title" :label="__('Judul Keunggulan')" size="sm" />
                                    <flux:textarea wire:model="content.why_us_subtitle" :label="__('Deskripsi Keunggulan')" rows="2" size="sm" />
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- 2. TAB HALAMAN PUBLIK (PROGRAM, TENTANG, KONTAK, GALERI) --}}
                    @if ($activeTab === 'pages')
                        <div class="space-y-4">
                            {{-- Program --}}
                            <div class="p-3.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="size-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-xs font-bold text-zinc-900 dark:text-white">1. Halaman Program Pendidikan (/program)</span>
                                </div>
                                <flux:input wire:model="content.page_program_title" :label="__('Judul Banner Program')" size="sm" />
                                <flux:textarea wire:model="content.page_program_subtitle" :label="__('Subjudul Banner Program')" rows="2" size="sm" />
                            </div>

                            {{-- Tentang Kami --}}
                            <div class="p-3.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="size-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-xs font-bold text-zinc-900 dark:text-white">2. Halaman Tentang Kami (/tentang)</span>
                                </div>
                                <flux:input wire:model="content.page_about_title" :label="__('Judul Banner Tentang')" size="sm" />
                                <flux:textarea wire:model="content.page_about_subtitle" :label="__('Subjudul Banner Tentang')" rows="2" size="sm" />
                                <flux:textarea wire:model="content.page_about_visi" :label="__('Visi Lembaga')" rows="2" size="sm" />
                                <flux:textarea wire:model="content.page_about_misi" :label="__('Misi Lembaga')" rows="3" size="sm" />
                                <flux:textarea wire:model="content.page_about_history" :label="__('Sejarah / Profil Singkat')" rows="2" size="sm" />
                            </div>

                            {{-- Kontak --}}
                            <div class="p-3.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="size-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-xs font-bold text-zinc-900 dark:text-white">3. Halaman Kontak (/kontak)</span>
                                </div>
                                <flux:input wire:model="content.page_contact_title" :label="__('Judul Banner Kontak')" size="sm" />
                                <flux:textarea wire:model="content.page_contact_subtitle" :label="__('Subjudul Banner Kontak')" rows="2" size="sm" />
                            </div>

                            {{-- Galeri --}}
                            <div class="p-3.5 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="size-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-xs font-bold text-zinc-900 dark:text-white">4. Halaman Galeri Dokumentasi (/galeri)</span>
                                </div>
                                <flux:input wire:model="content.page_gallery_title" :label="__('Judul Banner Galeri')" size="sm" />
                                <flux:textarea wire:model="content.page_gallery_subtitle" :label="__('Subjudul Banner Galeri')" rows="2" size="sm" />
                            </div>
                        </div>
                    @endif

                    {{-- 3. TAB GAMBAR & BACKGROUND --}}
                    @if ($activeTab === 'images')
                        <div class="space-y-4">
                            @if ($theme === 'pixigon')
                                <div class="p-3 rounded-xl border border-zinc-200 dark:border-zinc-700 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold">1. Foto Santriwati Kiri Hero</span>
                                        <button type="button" wire:click="openImageEditor('hero_student_image_left', 'Foto Santriwati Hero')" class="text-xs text-emerald-600 font-bold hover:underline">
                                            {{ __('Pilih Preset') }}
                                        </button>
                                    </div>
                                    <flux:input wire:model="content.hero_student_image_left" placeholder="https://..." size="sm" />
                                </div>

                                <div class="p-3 rounded-xl border border-zinc-200 dark:border-zinc-700 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold">2. Foto Santriwan Kanan Hero</span>
                                        <button type="button" wire:click="openImageEditor('hero_student_image_right', 'Foto Santriwan Hero')" class="text-xs text-emerald-600 font-bold hover:underline">
                                            {{ __('Pilih Preset') }}
                                        </button>
                                    </div>
                                    <flux:input wire:model="content.hero_student_image_right" placeholder="https://..." size="sm" />
                                </div>

                                <div class="p-3 rounded-xl border border-zinc-200 dark:border-zinc-700 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold">3. Foto Fasilitas Belajar</span>
                                        <button type="button" wire:click="openImageEditor('about_facility_image', 'Foto Fasilitas Belajar')" class="text-xs text-emerald-600 font-bold hover:underline">
                                            {{ __('Pilih Preset') }}
                                        </button>
                                    </div>
                                    <flux:input wire:model="content.about_facility_image" placeholder="https://..." size="sm" />
                                </div>
                            @else
                                <div class="p-3 rounded-xl border border-zinc-200 dark:border-zinc-700 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold">1. Background Hero Slider 1</span>
                                        <button type="button" wire:click="openImageEditor('hero_slide_1_image', 'Background Slider 1')" class="text-xs text-emerald-600 font-bold hover:underline">
                                            {{ __('Pilih Preset') }}
                                        </button>
                                    </div>
                                    <flux:input wire:model="content.hero_slide_1_image" placeholder="https://..." size="sm" />
                                </div>

                                <div class="p-3 rounded-xl border border-zinc-200 dark:border-zinc-700 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold">2. Background Hero Slider 2</span>
                                        <button type="button" wire:click="openImageEditor('hero_slide_2_image', 'Background Slider 2')" class="text-xs text-emerald-600 font-bold hover:underline">
                                            {{ __('Pilih Preset') }}
                                        </button>
                                    </div>
                                    <flux:input wire:model="content.hero_slide_2_image" placeholder="https://..." size="sm" />
                                </div>

                                <div class="p-3 rounded-xl border border-zinc-200 dark:border-zinc-700 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold">3. Background Hero Slider 3</span>
                                        <button type="button" wire:click="openImageEditor('hero_slide_3_image', 'Background Slider 3')" class="text-xs text-emerald-600 font-bold hover:underline">
                                            {{ __('Pilih Preset') }}
                                        </button>
                                    </div>
                                    <flux:input wire:model="content.hero_slide_3_image" placeholder="https://..." size="sm" />
                                </div>

                                <div class="p-3 rounded-xl border border-zinc-200 dark:border-zinc-700 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold">4. Foto Ilustrasi Keunggulan</span>
                                        <button type="button" wire:click="openImageEditor('why_us_image', 'Foto Keunggulan')" class="text-xs text-emerald-600 font-bold hover:underline">
                                            {{ __('Pilih Preset') }}
                                        </button>
                                    </div>
                                    <flux:input wire:model="content.why_us_image" placeholder="https://..." size="sm" />
                                </div>
                            @endif

                            <div class="p-3 rounded-xl border border-zinc-200 dark:border-zinc-700 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold">5. Background Banner Program</span>
                                    <button type="button" wire:click="openImageEditor('page_program_banner_image', 'Background Banner Program')" class="text-xs text-emerald-600 font-bold hover:underline">
                                        {{ __('Pilih Preset') }}
                                    </button>
                                </div>
                                <flux:input wire:model="content.page_program_banner_image" placeholder="https://..." size="sm" />
                            </div>

                            <div class="p-3 rounded-xl border border-zinc-200 dark:border-zinc-700 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold">6. Background Banner Tentang Kami</span>
                                    <button type="button" wire:click="openImageEditor('page_about_banner_image', 'Background Banner Tentang')" class="text-xs text-emerald-600 font-bold hover:underline">
                                        {{ __('Pilih Preset') }}
                                    </button>
                                </div>
                                <flux:input wire:model="content.page_about_banner_image" placeholder="https://..." size="sm" />
                            </div>
                        </div>
                    @endif

                    {{-- 4. TAB CTA & FOOTER --}}
                    @if ($activeTab === 'cta')
                        <div class="space-y-4">
                            <flux:input wire:model="content.cta_title" :label="__('Judul Banner Pendaftaran (CTA)')" />
                            <flux:textarea wire:model="content.cta_subtitle" :label="__('Subjudul Ajakan Mendaftar')" rows="2" />
                            <flux:input wire:model="content.cta_button_text" :label="__('Teks Tombol Pendaftaran')" />
                            
                            @if ($theme === 'default')
                                <flux:textarea wire:model="content.footer_description" :label="__('Deskripsi Footer')" rows="2" />
                            @endif
                        </div>
                    @endif

                    <div class="flex items-center justify-between pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <flux:modal.close>
                            <flux:button variant="ghost">{{ __('Tutup') }}</flux:button>
                        </flux:modal.close>
                        
                        <div class="flex gap-2">
                            <flux:button type="submit" variant="primary" class="bg-emerald-600! hover:bg-emerald-700! text-white! font-bold">
                                {{ __('Simpan Semua Perubahan') }}
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </flux:modal>

        {{-- Confirmation Modal for Resetting to Default --}}
        <x-confirm-modal 
            name="confirm-reset-builder-modal" 
            title="{{ __('Reset Konten ke Default') }}" 
            description="{{ __('Apakah Anda yakin ingin mengembalikan semua teks, judul, dan background ke pengaturan bawaan?') }}" 
            action="resetToDefault" 
            confirmText="{{ __('Reset Default') }}" 
        />
    @endif
</div>
