<x-layouts::public :title="__('Kebijakan Privasi')">
    <div class="flex flex-col w-full overflow-hidden">
        {{-- Hero Banner --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-16 text-white border-b-2 border-emerald-500/30">
            <div class="relative mx-auto max-w-7xl px-6">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                    ✦ {{ __('Perlindungan Data') }}
                </span>
                <flux:heading size="xl" class="text-4xl! font-extrabold text-white leading-tight">
                    {{ __('Kebijakan Privasi') }}
                </flux:heading>
                <p class="mt-2 text-xs text-emerald-100/80">{{ __('Terakhir diperbarui: Juli 2026') }}</p>
            </div>
        </section>

        {{-- Content Section (Soft Jade Mist Theme #edf7f4) --}}
        <section class="w-full bg-[#edf7f4] py-16 border-b border-emerald-500/20">
            <div class="mx-auto max-w-4xl px-6 space-y-6">
                <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm">
                    <h3 class="font-bold text-emerald-950 text-base mb-2">{{ __('1. Informasi yang Kami Kumpulkan') }}</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('Kami mengumpulkan informasi yang Anda berikan secara langsung kepada kami, termasuk nama, alamat email, nomor telepon, dan data lainnya yang diperlukan untuk proses pendaftaran dan administrasi santri.') }}
                    </p>
                </div>

                <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm">
                    <h3 class="font-bold text-emerald-950 text-base mb-2">{{ __('2. Penggunaan Informasi') }}</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('Informasi yang kami kumpulkan digunakan untuk mengelola pendaftaran santri, mengirim notifikasi akademik, mengelola pembayaran SPP, serta meningkatkan layanan kami.') }}
                    </p>
                </div>

                <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm">
                    <h3 class="font-bold text-emerald-950 text-base mb-2">{{ __('3. Keamanan Data') }}</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('Kami berkomitmen untuk melindungi keamanan informasi pribadi Anda. Kami menggunakan langkah-langkah keamanan yang sesuai untuk mencegah akses yang tidak sah, pengungkapan, perubahan, atau penghancuran data.') }}
                    </p>
                </div>

                <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm">
                    <h3 class="font-bold text-emerald-950 text-base mb-2">{{ __('4. Berbagi Informasi') }}</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('Kami tidak menjual, memperdagangkan, atau memindahkan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda, kecuali jika diwajibkan oleh hukum yang berlaku.') }}
                    </p>
                </div>

                <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm">
                    <h3 class="font-bold text-emerald-950 text-base mb-2">{{ __('5. Hubungi Kami') }}</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('Jika Anda memiliki pertanyaan mengenai kebijakan privasi ini, silakan hubungi kami melalui halaman') }}
                        <a href="{{ route('contact.show') }}" class="text-emerald-700 font-bold underline hover:text-emerald-800">{{ __('Kontak') }}</a>.
                    </p>
                </div>
            </div>
        </section>
    </div>
</x-layouts::public>
