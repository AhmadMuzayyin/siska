<x-layouts::public :title="__('Kebijakan Cookie')">
    <div class="flex flex-col w-full overflow-hidden">
        {{-- Hero Banner --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-16 text-white border-b-2 border-emerald-500/30">
            <div class="relative mx-auto max-w-7xl px-6">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                    ✦ {{ __('Informasi Cookie') }}
                </span>
                <flux:heading size="xl" class="text-4xl! font-extrabold text-white leading-tight">
                    {{ __('Kebijakan Cookie') }}
                </flux:heading>
                <p class="mt-2 text-xs text-emerald-100/80">{{ __('Informasi mengenai penggunaan cookie di website kami.') }}</p>
            </div>
        </section>

        {{-- Content Section (Soft Jade Mist Theme #edf7f4) --}}
        <section class="w-full bg-[#edf7f4] py-16 border-b border-emerald-500/20">
            <div class="mx-auto max-w-4xl px-6 space-y-6">
                <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm">
                    <h3 class="font-bold text-emerald-950 text-base mb-2">{{ __('Apa itu Cookie?') }}</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('Cookie adalah file teks kecil yang disimpan di perangkat Anda saat mengunjungi website. Cookie membantu website mengingat preferensi Anda dan meningkatkan pengalaman berselancar.') }}
                    </p>
                </div>

                <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm">
                    <h3 class="font-bold text-emerald-950 text-base mb-2">{{ __('Cookie yang Kami Gunakan') }}</h3>
                    <ul class="mt-3 flex flex-col gap-3 text-xs text-zinc-600">
                        <li class="flex items-start gap-2.5">
                            <flux:icon name="check-circle" class="mt-0.5 size-4 shrink-0 text-emerald-600" />
                            <span><strong>{{ __('Cookie Sesi') }}</strong> — {{ __('Digunakan untuk menjaga status login Anda selama sesi berlangsung.') }}</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <flux:icon name="check-circle" class="mt-0.5 size-4 shrink-0 text-emerald-600" />
                            <span><strong>{{ __('Cookie Preferensi') }}</strong> — {{ __('Menyimpan preferensi tampilan seperti mode gelap/terang.') }}</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <flux:icon name="check-circle" class="mt-0.5 size-4 shrink-0 text-emerald-600" />
                            <span><strong>{{ __('Cookie Persetujuan') }}</strong> — {{ __('Menyimpan persetujuan Anda terhadap kebijakan cookie ini.') }}</span>
                        </li>
                    </ul>
                </div>

                <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm">
                    <h3 class="font-bold text-emerald-950 text-base mb-2">{{ __('Mengelola Cookie') }}</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('Anda dapat mengatur browser untuk menolak atau menghapus cookie kapan saja. Namun, menonaktifkan cookie dapat mempengaruhi fungsionalitas website.') }}
                    </p>
                </div>
            </div>
        </section>
    </div>
</x-layouts::public>
