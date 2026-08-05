<x-layouts::public :title="__('Kontak')">
    <div class="flex flex-col w-full overflow-hidden">
        {{-- Hero Banner --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-20 text-white border-b-2 border-emerald-500/30">
            <img
                src="https://images.unsplash.com/photo-1577896851231-70ef18881754?w=1400&q=80&auto=format&fit=crop"
                alt="Kontak Al-Hikmah"
                class="absolute inset-0 size-full object-cover opacity-20"
                loading="eager"
                width="1400" height="400"
            >
            <div class="relative mx-auto max-w-7xl px-6">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                    ✦ {{ __('Layanan Informasi') }}
                </span>
                <flux:heading size="xl" class="text-4xl! font-extrabold text-white leading-tight sm:text-5xl!">
                    {{ __('Hubungi Kami') }}
                </flux:heading>
                <p class="mt-4 max-w-2xl text-sm text-emerald-100/90 leading-relaxed">
                    {{ __('Ada pertanyaan seputar pendaftaran santri baru, kurikulum, atau administrasi? Kirimkan pesan Anda, kami akan segera merespons.') }}
                </p>
            </div>
        </section>

        {{-- Contact Form & Info Section (Soft Jade Mist Theme #edf7f4) --}}
        <section class="w-full bg-[#edf7f4] py-16 border-b border-emerald-500/20">
            <div class="mx-auto max-w-7xl px-6">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
                    {{-- Form Column (7 cols) --}}
                    <div class="lg:col-span-7 rounded-3xl border border-emerald-500/20 bg-white/95 p-8 shadow-md backdrop-blur-sm">
                        <h3 class="text-xl font-bold text-emerald-950 mb-2">{{ __('Kirimkan Pesan Pertanyaan') }}</h3>
                        <p class="text-xs text-zinc-500 mb-6">{{ __('Isi formulir di bawah ini untuk menghubungi sekretariat lembaga.') }}</p>

                        @if (session('status'))
                            <flux:callout variant="success" class="mb-6" icon="check-circle" text="{{ session('status') }}" />
                        @endif

                        <form method="POST" action="{{ route('contact.store') }}" class="flex flex-col gap-6">
                            @csrf

                            <div class="grid gap-4 sm:grid-cols-2">
                                <flux:field>
                                    <flux:input name="name" :label="__('Nama Lengkap')" :value="old('name')" />
                                    <flux:error name="name" />
                                </flux:field>
                                <flux:field>
                                    <flux:input name="email" type="email" :label="__('Email')" :value="old('email')" />
                                    <flux:error name="email" />
                                </flux:field>
                            </div>

                            <flux:field>
                                <flux:input name="subject" :label="__('Subjek')" :value="old('subject')" />
                                <flux:error name="subject" />
                            </flux:field>

                            <flux:field>
                                <flux:textarea name="message" :label="__('Pesan')" rows="5">{{ old('message') }}</flux:textarea>
                                <flux:error name="message" />
                            </flux:field>

                            <div class="flex justify-end">
                                <flux:button type="submit" variant="primary" class="bg-emerald-700! hover:bg-emerald-800! text-white! font-bold px-6 py-3 shadow-md">
                                    <flux:icon name="paper-airplane" class="size-4 me-1.5" />
                                    {{ __('Kirim Pesan') }}
                                </flux:button>
                            </div>
                        </form>
                    </div>

                    {{-- Contact Details & Map Column (5 cols) --}}
                    <div class="lg:col-span-5 flex flex-col gap-6">
                        @php $setting = \App\Models\Setting::query()->first(); @endphp
                        <div class="rounded-3xl border border-emerald-500/20 bg-white/95 p-8 shadow-md backdrop-blur-sm space-y-6">
                            <h3 class="text-xl font-bold text-emerald-950 border-b border-emerald-100 pb-3">{{ __('Informasi Sekretariat') }}</h3>

                            @if ($setting?->alamat)
                                <div class="flex items-start gap-3">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                        <flux:icon name="map-pin" class="size-5" />
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xs text-emerald-950">{{ __('Alamat Lembaga') }}</h4>
                                        <p class="text-xs text-zinc-600 leading-relaxed mt-0.5">{{ $setting->alamat }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($setting?->telepon)
                                <div class="flex items-center gap-3">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                        <flux:icon name="phone" class="size-5" />
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xs text-emerald-950">{{ __('Telepon / WhatsApp') }}</h4>
                                        <a href="tel:{{ $setting->telepon }}" class="text-xs text-emerald-700 font-semibold hover:underline mt-0.5 block">{{ $setting->telepon }}</a>
                                    </div>
                                </div>
                            @endif

                            @if ($setting?->email)
                                <div class="flex items-center gap-3">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                        <flux:icon name="envelope" class="size-5" />
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xs text-emerald-950">{{ __('Email Resmi') }}</h4>
                                        <a href="mailto:{{ $setting->email }}" class="text-xs text-emerald-700 font-semibold hover:underline mt-0.5 block">{{ $setting->email }}</a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($setting?->google_maps_url)
                            <div class="overflow-hidden rounded-3xl border-2 border-emerald-500/30 shadow-md">
                                <iframe
                                    src="{{ $setting->google_maps_url }}"
                                    class="w-full"
                                    style="height: 280px; border: 0;"
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
</x-layouts::public>
