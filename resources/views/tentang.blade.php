<x-layouts::public :title="__('Tentang Kami')">
    <div class="flex flex-col w-full overflow-hidden">
        {{-- Hero --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-20 text-white border-b-2 border-emerald-500/30">
            <img
                src="https://images.unsplash.com/photo-1585036156171-384164a8c675?w=1400&q=80&auto=format&fit=crop"
                alt="Santri mengaji Al-Hikmah"
                class="absolute inset-0 size-full object-cover opacity-20"
                loading="eager"
                width="1400" height="500"
            >
            <div class="relative mx-auto max-w-7xl px-6">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                    ✦ {{ __('Profil & Identitas') }}
                </span>
                <flux:heading size="xl" class="text-4xl! font-extrabold text-white leading-tight sm:text-5xl!">
                    {{ __('Mendidik Generasi Qurani Beradab') }}
                </flux:heading>
                <p class="mt-4 max-w-2xl text-sm text-emerald-100/90 leading-relaxed">
                    {{ __('TPQ & Madin Al-Hikmah adalah lembaga pendidikan Al-Qur\'an dan Diniyah yang berkomitmen mencetak generasi berakhlak mulia menggunakan metode Tilawati — belajar Al-Qur\'an mudah dan menyenangkan.') }}
                </p>
            </div>
        </section>

        {{-- Motto Ticker Bar --}}
        <div class="w-full border-b border-emerald-500/30 bg-gradient-to-r from-[#059669] via-[#06382b] to-[#059669] py-4 text-white">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-6 text-center text-xs font-bold uppercase tracking-widest text-emerald-100 sm:text-sm">
                <span class="mx-auto sm:mx-0 flex items-center gap-2">
                    <span class="text-amber-300">✦</span> {{ __('Mengaji untuk Masa Depan') }}
                </span>
                <span class="hidden md:inline text-amber-300">✦</span>
                <span class="hidden md:inline">{{ __('Metode Tilawati Mudah & Menyenangkan') }}</span>
                <span class="hidden lg:inline text-amber-300">✦</span>
                <span class="hidden lg:inline">{{ __('Ijazah Resmi Nurul Falah Surabaya') }}</span>
            </div>
        </div>

        {{-- Visi & Misi Section (Soft Sage Theme #dcf0ea) --}}
        <section class="w-full bg-[#dcf0ea] py-20 border-b border-emerald-500/20">
            <div class="mx-auto max-w-7xl px-6">
                <div class="mb-12 max-w-xl">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-600/30 bg-emerald-600/10 px-3.5 py-1 text-xs font-bold text-emerald-800 mb-3">
                        ✦ {{ __('Landasan Utama') }}
                    </span>
                    <flux:heading size="xl" class="text-3xl! font-bold text-emerald-950 sm:text-4xl!">{{ __('Visi & Misi Lembaga') }}</flux:heading>
                    <p class="mt-2 text-sm text-emerald-900/80">{{ __('Landasan dan arah perjuangan kami dalam mendidik generasi Qurani.') }}</p>
                </div>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-8 shadow-md backdrop-blur-sm transition duration-300 hover:shadow-2xl">
                        <div class="flex size-14 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 mb-6">
                            <flux:icon name="flag" class="size-7" />
                        </div>
                        <h3 class="text-2xl font-bold text-emerald-950">{{ __('Visi Utama') }}</h3>
                        <p class="mt-3 text-xs text-zinc-600 leading-relaxed">
                            {{ __('Menjadi lembaga pendidikan Al-Qur\'an terkemuka yang menghasilkan generasi Qurani yang berakhlak mulia, berprestasi, dan bermanfaat bagi agama, orang tua, serta masyarakat.') }}
                        </p>
                    </div>

                    <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-8 shadow-md backdrop-blur-sm transition duration-300 hover:shadow-2xl">
                        <div class="flex size-14 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 mb-6">
                            <flux:icon name="check-badge" class="size-7" />
                        </div>
                        <h3 class="text-2xl font-bold text-emerald-950">{{ __('Misi Lembaga') }}</h3>
                        <ul class="mt-4 flex flex-col gap-3 text-xs text-zinc-600">
                            <li class="flex items-start gap-2.5">
                                <flux:icon name="check-circle" class="mt-0.5 size-4 shrink-0 text-emerald-600" />
                                <span>{{ __('Menyelenggarakan pendidikan Al-Qur\'an yang berkualitas dengan metode pembelajaran yang efektif dan menyenangkan.') }}</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <flux:icon name="check-circle" class="mt-0.5 size-4 shrink-0 text-emerald-600" />
                                <span>{{ __('Membentuk karakter dan kepribadian santri berdasarkan nilai-nilai Islam dan Al-Qur\'an.') }}</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <flux:icon name="check-circle" class="mt-0.5 size-4 shrink-0 text-emerald-600" />
                                <span>{{ __('Mengembangkan potensi santri melalui program pendidikan diniyah komprehensif.') }}</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <flux:icon name="check-circle" class="mt-0.5 size-4 shrink-0 text-emerald-600" />
                                <span>{{ __('Membangun kerja sama yang baik dengan orang tua dan masyarakat dalam mendidik generasi Qurani.') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- Sejarah & Profil (Soft Jade Mist Theme #edf7f4) --}}
        <section class="w-full bg-[#edf7f4] py-20 border-b border-emerald-500/20">
            <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-6 lg:grid-cols-2">
                <div class="overflow-hidden rounded-3xl border-2 border-emerald-500/30 shadow-2xl">
                    <img
                        src="https://images.unsplash.com/photo-1609220136736-443140cffec6?w=800&q=80&auto=format&fit=crop"
                        alt="Kegiatan belajar mengajar"
                        class="w-full aspect-4/3 object-cover rounded-3xl"
                        loading="lazy"
                        width="800" height="600"
                    >
                </div>
                <div class="space-y-5">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-600/30 bg-emerald-600/10 px-3.5 py-1 text-xs font-bold text-emerald-800 mb-2">
                        ✦ {{ __('Sejarah Singkat') }}
                    </span>
                    <flux:heading size="xl" class="text-3xl! font-bold text-emerald-950 sm:text-4xl!">{{ __('Mengapa Memilih Al-Hikmah?') }}</flux:heading>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('TPQ & Madin Al-Hikmah berdiri sebagai lembaga pendidikan Al-Qur\'an terpadu yang berkomitmen membentuk generasi Qurani yang berakhlak mulia dan berwawasan luas melalui pendidikan Al-Qur\'an berkualitas menggunakan metode Tilawati.') }}
                    </p>

                    <div class="grid grid-cols-1 gap-3 pt-2">
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-white/80 border border-emerald-500/20 shadow-xs">
                            <flux:icon name="check-circle" class="size-5 shrink-0 text-emerald-600" />
                            <span class="text-xs font-semibold text-emerald-950">{{ __('Kurikulum holistik yang mencakup aspek akademis, ibadah, dan adab santri.') }}</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-white/80 border border-emerald-500/20 shadow-xs">
                            <flux:icon name="check-circle" class="size-5 shrink-0 text-emerald-600" />
                            <span class="text-xs font-semibold text-emerald-950">{{ __('Lingkungan belajar yang ramah anak, nyaman, dan kondusif.') }}</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-white/80 border border-emerald-500/20 shadow-xs">
                            <flux:icon name="check-circle" class="size-5 shrink-0 text-emerald-600" />
                            <span class="text-xs font-semibold text-emerald-950">{{ __('Tenaga pengajar profesional, sabar, dan mengantongi bersyahadah resmi.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Metode Pembelajaran (Soft Sage Theme #dcf0ea) --}}
        <section class="w-full bg-[#dcf0ea] py-20 border-b border-emerald-500/20">
            <div class="mx-auto max-w-7xl px-6">
                <div class="mb-12 text-center">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-600/30 bg-emerald-600/10 px-3.5 py-1 text-xs font-bold text-emerald-800 mb-3">
                        ✦ {{ __('Pendekatan Belajar') }}
                    </span>
                    <flux:heading size="xl" class="text-3xl! font-bold text-emerald-950 sm:text-4xl!">{{ __('Metode Pembelajaran Teruji') }}</flux:heading>
                    <p class="mt-2 text-sm text-emerald-900/80">{{ __('Pendekatan terstruktur untuk hasil yang optimal.') }}</p>
                </div>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 text-center shadow-md backdrop-blur-sm transition duration-300 hover:shadow-2xl">
                        <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 mb-4">
                            <flux:icon name="book-open" class="size-7" />
                        </div>
                        <h4 class="font-bold text-emerald-950 text-lg">{{ __('Tilawati') }}</h4>
                        <p class="mt-2 text-xs text-zinc-600 leading-relaxed">{{ __('Metode baca Al-Qur\'an yang mudah, menyenangkan, dan terbukti efektif secara nasional.') }}</p>
                    </div>

                    <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 text-center shadow-md backdrop-blur-sm transition duration-300 hover:shadow-2xl">
                        <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 mb-4">
                            <flux:icon name="users" class="size-7" />
                        </div>
                        <h4 class="font-bold text-emerald-950 text-lg">{{ __('Klasikal & Individual') }}</h4>
                        <p class="mt-2 text-xs text-zinc-600 leading-relaxed">{{ __('Kombinasi belajar bersama dan pendampingan personal privat untuk setiap perkembangan santri.') }}</p>
                    </div>

                    <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 text-center shadow-md backdrop-blur-sm transition duration-300 hover:shadow-2xl">
                        <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 mb-4">
                            <flux:icon name="academic-cap" class="size-7" />
                        </div>
                        <h4 class="font-bold text-emerald-950 text-lg">{{ __('Evaluasi & Munaqosyah') }}</h4>
                        <p class="mt-2 text-xs text-zinc-600 leading-relaxed">{{ __('Penilaian rutin dan munaqosyah resmi untuk memantau kelulusan juz dan penerbitan ijazah.') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layouts::public>
