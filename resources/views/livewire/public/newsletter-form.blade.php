<div class="w-full">
    @if ($submitted)
        <div class="flex items-center gap-3 rounded-xl border border-emerald-400/40 bg-emerald-950/80 p-4 text-emerald-200 shadow-md">
            <flux:icon name="check-circle" class="size-6 shrink-0 text-emerald-400" />
            <div>
                <h4 class="font-bold text-white text-sm">{{ __('Terima kasih telah berlangganan!') }}</h4>
                <p class="text-xs text-emerald-200/90">{{ __('Alamat email Anda telah terdaftar. Kami akan mengirimkan kabar kegiatan terbaru.') }}</p>
            </div>
        </div>
    @else
        <form wire:submit="subscribe" class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <flux:input
                    wire:model="email"
                    type="email"
                    placeholder="{{ __('Masukkan alamat email Anda...') }}"
                    icon="envelope"
                    class="w-full bg-white! text-zinc-900! placeholder-zinc-400! border-emerald-300!"
                />
            </div>
            <flux:button type="submit" variant="primary" class="bg-emerald-500! hover:bg-emerald-400! text-emerald-950! font-extrabold shadow-lg px-6 py-2.5">
                <flux:icon name="paper-airplane" class="size-4 me-1.5" />
                {{ __('Berlangganan') }}
            </flux:button>
        </form>
        @error('email')
            <p class="mt-2 text-xs font-semibold text-rose-400">{{ $message }}</p>
        @enderror
    @endif
</div>
