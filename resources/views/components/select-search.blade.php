@props([
    'placeholder' => __('Pilih opsi...'),
    'options' => [],
    'searchable' => true,
    'label' => null,
    'name' => null,
    'disabled' => false,
    'size' => 'md',
    'class' => '',
])

@php
    $modelName = $attributes->wire('model')->value();
    
    // Normalize options into array of ['value' => ..., 'label' => ..., 'sublabel' => ...]
    $normalizedOptions = [];
    foreach ($options as $key => $option) {
        if (is_array($option)) {
            $normalizedOptions[] = [
                'value' => (string) ($option['value'] ?? $key),
                'label' => (string) ($option['label'] ?? $option['name'] ?? $option['nama'] ?? $key),
                'sublabel' => (string) ($option['sublabel'] ?? $option['description'] ?? $option['deskripsi'] ?? ''),
            ];
        } elseif (is_object($option)) {
            $normalizedOptions[] = [
                'value' => (string) ($option->id ?? $option->value ?? $key),
                'label' => (string) ($option->nama_lengkap ?? $option->nama ?? $option->name ?? $option->label ?? $option->judul ?? $key),
                'sublabel' => (string) ($option->noinduk ?? $option->kode ?? $option->jenjang ?? $option->sublabel ?? ''),
            ];
        } else {
            $normalizedOptions[] = [
                'value' => (string) $key,
                'label' => (string) $option,
                'sublabel' => '',
            ];
        }
    }
@endphp

<div 
    x-data="{
        open: false,
        search: '',
        value: @if($modelName) @entangle($attributes->wire('model')) @else null @endif,
        options: {{ json_encode($normalizedOptions) }},
        get selectedLabel() {
            if (this.value === null || this.value === '' || this.value === undefined) return '';
            const found = this.options.find(o => String(o.value) === String(this.value));
            return found ? found.label : '';
        },
        get filteredOptions() {
            if (!this.search.trim()) return this.options;
            const q = this.search.toLowerCase();
            return this.options.filter(o => 
                o.label.toLowerCase().includes(q) || 
                (o.sublabel && o.sublabel.toLowerCase().includes(q))
            );
        },
        select(val) {
            this.value = val;
            this.open = false;
            this.search = '';
            $dispatch('change', val);
        },
        clear() {
            this.value = null;
            this.open = false;
            this.search = '';
            $dispatch('change', null);
        }
    }"
    @click.away="open = false; search = ''"
    @keydown.escape="open = false; search = ''"
    class="relative {{ $class }}"
>
    @if ($label)
        <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">
            {{ $label }}
        </label>
    @endif

    {{-- Dropdown Trigger Button --}}
    <button 
        type="button" 
        @click="open = !open; if(open) { $nextTick(() => $refs.searchInput?.focus()) }"
        class="relative w-full flex items-center justify-between text-left rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800/90 px-3.5 py-2.5 text-xs text-zinc-900 dark:text-white shadow-2xs hover:border-emerald-500 dark:hover:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition cursor-pointer"
        :class="{ 'border-emerald-500 ring-2 ring-emerald-500/20': open }"
    >
        <span 
            x-text="selectedLabel || '{{ $placeholder }}'" 
            class="truncate block"
            :class="{ 'text-zinc-400 dark:text-zinc-500': !selectedLabel, 'font-semibold text-zinc-900 dark:text-white': selectedLabel }"
        ></span>

        <div class="flex items-center gap-1 shrink-0 ms-2 text-zinc-400 dark:text-zinc-500">
            <template x-if="value !== null && value !== ''">
                <span @click.stop="clear()" class="p-0.5 hover:text-rose-500 cursor-pointer rounded-full transition" title="{{ __('Hapus pilihan') }}">
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </span>
            </template>
            <svg class="size-4 transition-transform duration-200" :class="{ 'rotate-180 text-emerald-600 dark:text-emerald-400': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </button>

    {{-- Dropdown Menu Popover --}}
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1 scale-98"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-1 scale-98"
        class="absolute z-50 mt-1.5 w-full rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-2 shadow-2xl backdrop-blur-md overflow-hidden min-w-[220px]"
        style="display: none;"
    >
        @if ($searchable)
            <div class="relative mb-2">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-400">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    x-ref="searchInput"
                    x-model="search" 
                    placeholder="{{ __('Ketik untuk mencari...') }}" 
                    class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/80 py-2 pl-9 pr-8 text-xs text-zinc-900 dark:text-white placeholder-zinc-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                    @keydown.escape="open = false"
                />
                <template x-if="search.length > 0">
                    <button type="button" @click="search = ''" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-zinc-400 hover:text-zinc-600">
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </template>
            </div>
        @endif

        {{-- Options List --}}
        <div class="max-h-60 overflow-y-auto space-y-1 pr-1 custom-scrollbar">
            <template x-for="item in filteredOptions" :key="item.value">
                <button 
                    type="button" 
                    @click="select(item.value)" 
                    class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-left text-xs transition cursor-pointer"
                    :class="String(value) === String(item.value) ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 font-bold' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700/60'"
                >
                    <div class="flex flex-col gap-0.5 truncate">
                        <span x-text="item.label" class="truncate font-medium"></span>
                        <template x-if="item.sublabel">
                            <span x-text="item.sublabel" class="text-[10px] text-zinc-400 dark:text-zinc-500"></span>
                        </template>
                    </div>

                    <template x-if="String(value) === String(item.value)">
                        <svg class="size-4 text-emerald-600 dark:text-emerald-400 shrink-0 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </template>
                </button>
            </template>

            <template x-if="filteredOptions.length === 0">
                <div class="py-4 text-center text-xs text-zinc-400 dark:text-zinc-500">
                    {{ __('Tidak ada data ditemukan') }}
                </div>
            </template>
        </div>
    </div>
</div>
