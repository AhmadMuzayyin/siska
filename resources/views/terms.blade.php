<x-layouts::public :title="__('Syarat & Ketentuan')">
    <div class="flex flex-col w-full overflow-hidden">
        {{-- Hero Banner --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-16 text-white border-b-2 border-emerald-500/30">
            <div class="relative mx-auto max-w-7xl px-6">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                    ✦ {{ __('Ketentuan Penggunaan') }}
                </span>
                <flux:heading size="xl" class="text-4xl! font-extrabold text-white leading-tight">
                    {{ __('Syarat & Ketentuan') }}
                </flux:heading>
                <p class="mt-2 text-xs text-emerald-100/80">{{ __('Terakhir diperbarui: Juli 2026') }}</p>
            </div>
        </section>

        {{-- Content Section (Soft Jade Mist Theme #edf7f4) --}}
        <section class="w-full bg-[#edf7f4] py-16 border-b border-emerald-500/20">
            <div class="mx-auto max-w-4xl px-6 space-y-6">
                <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm">
                    <h3 class="font-bold text-emerald-950 text-base mb-2">{{ __('1. Penerimaan Syarat') }}</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('Dengan menggunakan sistem informasi akademik ini, Anda menyetujui syarat dan ketentuan yang berlaku. Jika Anda tidak menyetujui syarat ini, harap tidak menggunakan layanan kami.') }}
                    </p>
                </div>

                <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm">
                    <h3 class="font-bold text-emerald-950 text-base mb-2">{{ __('2. Penggunaan Layanan') }}</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('Layanan ini hanya boleh digunakan untuk keperluan akademik dan administrasi yang sah oleh pihak yang berwenang. Penggunaan yang tidak sah atau penyalahgunaan sistem akan berakibat pada penonaktifan akun.') }}
                    </p>
                </div>

                <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm">
                    <h3 class="font-bold text-emerald-950 text-base mb-2">{{ __('3. Akun Pengguna') }}</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('Anda bertanggung jawab untuk menjaga kerahasiaan informasi akun Anda. Segera laporkan kepada kami jika terjadi penggunaan akun yang tidak sah.') }}
                    </p>
                </div>

                <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-7 shadow-md backdrop-blur-sm">
                    <h3 class="font-bold text-emerald-950 text-base mb-2">{{ __('4. Perubahan Layanan') }}</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        {{ __('Kami berhak untuk mengubah, menangguhkan, atau menghentikan layanan kapan saja dengan atau tanpa pemberitahuan sebelumnya.') }}
                    </p>
                </div>
            </div>
        </section>
    </div>
</x-layouts::public>
