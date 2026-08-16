<div>
    @if (auth()->user()?->role !== \App\Enums\UserRole::Operator)
        <flux:dropdown position="bottom" align="end">
            <button
                type="button"
                class="relative flex size-9 items-center justify-center rounded-full text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                title="{{ __('Pilih Unit Lembaga: ') . $this->activeLembagaName }}"
                aria-label="{{ __('Pilih Unit Lembaga') }}"
            >
                <flux:icon name="building-office-2" class="size-5" />
                @if ($selectedLembagaId !== 'all')
                    <span class="absolute top-1 right-1 size-2 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-zinc-900"></span>
                @endif
            </button>

            <flux:menu class="w-64 py-1.5 shadow-xl rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-900">
                <div class="px-3 py-1.5 text-[11px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                    {{ __('Pilih Unit Lembaga') }}
                </div>

                <flux:menu.item
                    wire:click="switchLembaga(null)"
                    class="flex items-center justify-between py-2 text-xs font-medium cursor-pointer {{ $selectedLembagaId === 'all' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 font-bold' : '' }}"
                >
                    <span>{{ __('Semua Lembaga') }}</span>
                    @if ($selectedLembagaId === 'all')
                        <flux:icon name="check" class="size-4 text-emerald-600 dark:text-emerald-400 ms-auto" />
                    @endif
                </flux:menu.item>

                <flux:menu.separator class="my-1" />

                @foreach ($this->lembagas as $lembaga)
                    <flux:menu.item
                        wire:click="switchLembaga({{ $lembaga->id }})"
                        class="flex items-center justify-between py-2 text-xs font-medium cursor-pointer {{ $selectedLembagaId == $lembaga->id ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 font-bold' : '' }}"
                    >
                        <span class="truncate">{{ $lembaga->nama }} ({{ $lembaga->jenjang }})</span>
                        @if ($selectedLembagaId == $lembaga->id)
                            <flux:icon name="check" class="size-4 text-emerald-600 dark:text-emerald-400 ms-auto" />
                        @endif
                    </flux:menu.item>
                @endforeach
            </flux:menu>
        </flux:dropdown>
    @endif
</div>
