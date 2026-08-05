<div class="flex flex-col w-full overflow-hidden">
    {{-- Hero Banner --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-[#06382b] via-[#094a38] to-[#021d16] py-20 text-white border-b-2 border-emerald-500/30">
        <img
            src="https://images.unsplash.com/photo-1609220136736-443140cffec6?w=1400&q=80&auto=format&fit=crop"
            alt="Galeri Al-Hikmah"
            class="absolute inset-0 size-full object-cover opacity-20"
            loading="eager"
            width="1400" height="400"
        >
        <div class="relative mx-auto max-w-7xl px-6">
            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-200 mb-4">
                ✦ {{ __('Dokumentasi Lengkap') }}
            </span>
            <flux:heading size="xl" class="text-4xl! font-extrabold text-white leading-tight sm:text-5xl!">
                {{ __('Galeri & Dokumentasi Kegiatan') }}
            </flux:heading>
            <p class="mt-4 max-w-2xl text-sm text-emerald-100/90 leading-relaxed">
                {{ __('Dokumentasi momentum penting, kegiatan pembelajaran harian, perlombaan, dan acara keagamaan di lembaga kami.') }}
            </p>
        </div>
    </section>

    {{-- Main Gallery Content (Soft Jade Mist Theme #edf7f4) --}}
    <section class="w-full bg-[#edf7f4] py-16 border-b border-emerald-500/20">
        <div class="mx-auto max-w-7xl px-6">
            {{-- Category Filters --}}
            <div class="mb-10 flex flex-wrap items-center justify-between gap-4 border-b border-emerald-500/20 pb-6">
                <div class="flex flex-wrap gap-2">
                    <button
                        type="button"
                        wire:click="setGalleryType('semua')"
                        class="rounded-full px-4 py-2 text-xs font-semibold transition {{ $activeGalleryType === 'semua' ? 'bg-emerald-700 text-white shadow-md font-bold' : 'bg-white/90 text-emerald-950 hover:bg-white border border-emerald-500/20' }}"
                    >
                        {{ __('Semua Foto') }}
                    </button>

                    @foreach ($this->galleryTypes as $type)
                        <button
                            type="button"
                            wire:click="setGalleryType('{{ $type }}')"
                            class="rounded-full px-4 py-2 text-xs font-semibold transition {{ $activeGalleryType === $type ? 'bg-emerald-700 text-white shadow-md font-bold' : 'bg-white/90 text-emerald-950 hover:bg-white border border-emerald-500/20' }}"
                        >
                            {{ ucfirst($type) }}
                        </button>
                    @endforeach
                </div>

                <p class="text-xs text-emerald-900/80">
                    {{ __('Menampilkan dokumentasi :type', ['type' => $activeGalleryType === 'semua' ? 'seluruh kegiatan' : $activeGalleryType]) }}
                </p>
            </div>

            {{-- Gallery Grid --}}
            @if ($this->galleries->isEmpty())
                <div class="py-16 text-center">
                    <flux:callout icon="photo" heading="{{ __('Belum Ada Foto') }}" text="{{ __('Dokumentasi untuk kategori ini belum tersedia.') }}" />
                </div>
            @else
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($this->galleries as $gallery)
                        <div class="group overflow-hidden rounded-3xl border border-emerald-500/20 bg-white/95 shadow-md backdrop-blur-sm transition-all duration-300 hover:shadow-2xl hover:-translate-y-1" wire:key="galeri-item-{{ $gallery->id }}">
                            <div class="relative aspect-video overflow-hidden bg-emerald-900">
                                <img
                                    src="{{ $gallery->image }}"
                                    alt="{{ $gallery->title }}"
                                    class="size-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    loading="lazy"
                                    width="400" height="225"
                                >
                                <span class="absolute top-3 right-3 rounded-full bg-[#06382b]/90 border border-emerald-400/30 px-3 py-1 text-[10px] font-bold text-emerald-200 backdrop-blur-md">
                                    {{ strtoupper($gallery->type->value ?? $gallery->type) }}
                                </span>
                            </div>
                            <div class="p-5">
                                <h4 class="font-bold text-emerald-950 text-sm group-hover:text-emerald-700 transition">{{ $gallery->title }}</h4>
                                @if ($gallery->description)
                                    <p class="mt-2 text-xs leading-relaxed text-zinc-600">{{ $gallery->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $this->galleries->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
