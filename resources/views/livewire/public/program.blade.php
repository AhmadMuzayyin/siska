<div class="flex flex-col w-full overflow-hidden">
    {{-- Hero Banner --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-20 text-white border-b-2 border-emerald-500/30">
        <img
            src="https://images.unsplash.com/photo-1585036156171-384164a8c675?w=1400&q=80&auto=format&fit=crop"
            alt="Program Pendidikan Al-Hikmah"
            class="absolute inset-0 size-full object-cover opacity-20"
            loading="eager"
            width="1400" height="400"
        >
        <div class="relative mx-auto max-w-7xl px-6">
            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                ✦ {{ __('Kurikulum & Pendidikan') }}
            </span>
            <flux:heading size="xl" class="text-4xl! font-extrabold text-white leading-tight sm:text-5xl!">
                {{ __('Program Pendidikan & Kurikulum') }}
            </flux:heading>
            <p class="mt-4 max-w-2xl text-sm text-emerald-100/90 leading-relaxed">
                {{ __('Program pembelajaran terstruktur yang memadukan pembacaan Al-Qur\'an metode Tilawati, ilmu diniyah, serta pembinaan karakter santri.') }}
            </p>
        </div>
    </section>

    {{-- Program Detail Sections (Soft Jade Mist Theme #edf7f4) --}}
    <section class="w-full bg-[#edf7f4] py-16 border-b border-emerald-500/20">
        <div class="mx-auto max-w-7xl px-6 space-y-16">
            {{-- 1. TPQ --}}
            <div id="tpq" class="grid grid-cols-1 gap-10 items-center lg:grid-cols-12 rounded-3xl border border-emerald-500/20 bg-white/95 p-8 shadow-md backdrop-blur-sm transition duration-300 hover:shadow-2xl">
                <div class="lg:col-span-7 space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                            <flux:icon name="book-open" class="size-6" />
                        </div>
                        <div>
                            <span class="rounded-full bg-emerald-900 px-3 py-1 text-xs font-bold text-emerald-200">TPQ TILAWATI</span>
                            <h3 class="text-2xl font-bold text-emerald-950 mt-1">{{ __('Taman Pendidikan Al-Qur\'an (TPQ)') }}</h3>
                        </div>
                    </div>

                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('Program utama akselerasi baca Al-Qur\'an metode Tilawati dengan motto "Belajar Al-Qur\'an Mudah dan Menyenangkan". Dimulai dari tingkat PAUD hingga santri mengkhatamkan Al-Qur\'an 30 juz dan menerima ijazah resmi dari pusat Tilawati Nurul Falah Surabaya.') }}
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="flex items-start gap-3">
                            <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                            <div>
                                <h4 class="font-semibold text-xs text-emerald-950">{{ __('Metode Tilawati Jilid 1 - 6') }}</h4>
                                <p class="text-[11px] text-zinc-500">{{ __('Pembelajaran bertahap nada lagu Rost.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                            <div>
                                <h4 class="font-semibold text-xs text-emerald-950">{{ __('Tajwid & Ghorib') }}</h4>
                                <p class="text-[11px] text-zinc-500">{{ __('Pemahaman hukum bacaan & bacaan khusus.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                            <div>
                                <h4 class="font-semibold text-xs text-emerald-950">{{ __('Munaqosyah & Ijazah') }}</h4>
                                <p class="text-[11px] text-zinc-500">{{ __('Ujian resmi bersertifikasi Nurul Falah.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                            <div>
                                <h4 class="font-semibold text-xs text-emerald-950">{{ __('Hafalan Juz \'Amma') }}</h4>
                                <p class="text-[11px] text-zinc-500">{{ __('Target hafalan surat-surat pendek.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-5 overflow-hidden rounded-2xl border-2 border-emerald-500/30">
                    <img src="https://images.unsplash.com/photo-1609220136736-443140cffec6?w=600&q=80&auto=format&fit=crop" alt="TPQ Tilawati" class="w-full aspect-4/3 object-cover rounded-2xl">
                </div>
            </div>

            {{-- 2. MDTA --}}
            <div id="mdta" class="grid grid-cols-1 gap-10 items-center lg:grid-cols-12 rounded-3xl border border-emerald-500/20 bg-white/95 p-8 shadow-md backdrop-blur-sm transition duration-300 hover:shadow-2xl">
                <div class="lg:col-span-5 order-2 lg:order-1 overflow-hidden rounded-2xl border-2 border-emerald-500/30">
                    <img src="https://images.unsplash.com/photo-1585036156171-384164a8c675?w=600&q=80&auto=format&fit=crop" alt="MDTA Diniyah" class="w-full aspect-4/3 object-cover rounded-2xl">
                </div>
                <div class="lg:col-span-7 order-1 lg:order-2 space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                            <flux:icon name="academic-cap" class="size-6" />
                        </div>
                        <div>
                            <span class="rounded-full bg-emerald-900 px-3 py-1 text-xs font-bold text-emerald-200">DINIYAH (MDTA)</span>
                            <h3 class="text-2xl font-bold text-emerald-950 mt-1">{{ __('Madrasah Diniyah Takmiliyah Awwaliyah') }}</h3>
                        </div>
                    </div>

                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('Program pendidikan diniyah yang memperdalam ilmu syariat dasar Islam. Santri diajarkan Fiqih praktis, Akidah, Akhlak, Bahasa Arab dasar, serta sejarah nabi dan sahabat untuk bekal ibadah harian yang benar.') }}
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="flex items-start gap-3">
                            <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                            <div>
                                <h4 class="font-semibold text-xs text-emerald-950">{{ __('Fiqih Ibadah Praktis') }}</h4>
                                <p class="text-[11px] text-zinc-500">{{ __('Tata cara thoharah, salat, & bersuci.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                            <div>
                                <h4 class="font-semibold text-xs text-emerald-950">{{ __('Akidah & Akhlak Islam') }}</h4>
                                <p class="text-[11px] text-zinc-500">{{ __('Penanaman tauhid & adab bermasyarakat.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                            <div>
                                <h4 class="font-semibold text-xs text-emerald-950">{{ __('Bahasa Arab Dasar') }}</h4>
                                <p class="text-[11px] text-zinc-500">{{ __('Mufrodat & kaidah percakapan sederhana.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                            <div>
                                <h4 class="font-semibold text-xs text-emerald-950">{{ __('Tarikh / Sejarah Islam') }}</h4>
                                <p class="text-[11px] text-zinc-500">{{ __('Kisah keteladanan Nabi Muhammad SAW.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Tahfizh & Akhlak --}}
            <div id="tahfizh" class="grid grid-cols-1 gap-10 items-center lg:grid-cols-12 rounded-3xl border border-emerald-500/20 bg-white/95 p-8 shadow-md backdrop-blur-sm transition duration-300 hover:shadow-2xl">
                <div class="lg:col-span-7 space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="flex size-12 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                            <flux:icon name="heart" class="size-6" />
                        </div>
                        <div>
                            <span class="rounded-full bg-emerald-900 px-3 py-1 text-xs font-bold text-emerald-200">TAHFIDH & ADAB</span>
                            <h3 class="text-2xl font-bold text-emerald-950 mt-1">{{ __('Tahfizh & Pembinaan Karakter') }}</h3>
                        </div>
                    </div>

                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('Bimbingan pembiasaan karakter Islami dan bimbingan hafalan Al-Qur\'an intensif (Tahfizh) dengan pendampingan langsung ustadz/ustadzah. Santri diajarkan disiplin salat berjamaah, berbakti kepada orang tua, serta adab terhadap sesama.') }}
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="flex items-start gap-3">
                            <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                            <div>
                                <h4 class="font-semibold text-xs text-emerald-950">{{ __('Halaqah Tahfizh') }}</h4>
                                <p class="text-[11px] text-zinc-500">{{ __('Setoran harian & muroja\'ah berkesinambungan.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <flux:icon name="check-circle" class="size-5 text-emerald-600 shrink-0 mt-0.5" />
                            <div>
                                <h4 class="font-semibold text-xs text-emerald-950">{{ __('Pembiasaan Ibadah Harian') }}</h4>
                                <p class="text-[11px] text-zinc-500">{{ __('Salat dhuha & dzikir bersama.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-5 overflow-hidden rounded-2xl border-2 border-emerald-500/30">
                    <img src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=600&q=80&auto=format&fit=crop" alt="Tahfizh Qur'an" class="w-full aspect-4/3 object-cover rounded-2xl">
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Band --}}
    <section class="w-full bg-gradient-to-r from-[#06382b] via-[#094a38] to-[#021d16] py-16 text-white text-center border-t-2 border-emerald-500/30">
        <div class="mx-auto max-w-4xl px-6 space-y-4">
            <flux:heading size="xl" class="text-3xl! font-bold text-white">{{ __('Daftarkan Putra-Putri Anda Sekarang') }}</flux:heading>
            <p class="text-emerald-100/90 text-xs max-w-xl mx-auto">
                {{ __('Proses pendaftaran santri baru dapat dilakukan secara online melalui website ini.') }}
            </p>
            <div class="pt-4 flex justify-center gap-4">
                <flux:button variant="primary" class="bg-emerald-500! text-emerald-950! font-extrabold hover:bg-emerald-400! shadow-xl border border-emerald-300 px-6 py-3" :href="route('santri.register.form')" wire:navigate>
                    <flux:icon name="user-plus" class="size-4 me-1.5" />
                    {{ __('Daftar Santri Baru') }}
                </flux:button>
                <flux:button variant="ghost" class="text-white! hover:bg-white/10! border border-emerald-500/30 px-5 py-3" :href="route('contact.show')" wire:navigate>
                    <flux:icon name="chat-bubble-left-right" class="size-4 me-1.5 text-emerald-200" />
                    {{ __('Hubungi Kami') }}
                </flux:button>
            </div>
        </div>
    </section>
</div>
