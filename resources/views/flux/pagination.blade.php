@blaze(fold: true)

<?php
extract(Flux::forwardedAttributes($attributes, ['scrollTo']));
?>

@props([
    'paginator' => null,
    'scrollTo' => $scrollTo ?? null,
])

@php
$simple = ! $paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;

$scrollToSelector = $scrollTo === true ? 'body' : $scrollTo;

$scrollIntoViewJsSnippet = ($scrollTo !== null && $scrollTo !== false)
    ? "(\$el.closest('{$scrollToSelector}') || \$el.closest('body').querySelector('{$scrollToSelector}')).scrollIntoView()"
    : '';
@endphp

@if ($simple)
    <div {{ $attributes->class('px-5 py-3.5 border-t border-zinc-100 dark:border-zinc-700 flex justify-between items-center gap-3') }} data-flux-pagination>
        <div></div>

        @if ($paginator->hasPages())
            <div class="flex items-center bg-white border border-zinc-200 rounded-[8px] p-[1px] dark:bg-white/10 dark:border-white/10">
                @if ($paginator->onFirstPage())
                    <div class="flex justify-center items-center size-8 sm:size-6 rounded-[6px] text-zinc-300 dark:text-zinc-500">
                        <flux:icon.chevron-left variant="micro" class="rtl:hidden" />
                        <flux:icon.chevron-right variant="micro" class="hidden rtl:inline" />
                    </div>
                @else
                    @if(method_exists($paginator,'getCursorName'))
                        <button type="button" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->previousCursor()->encode() }}" wire:click="setPage('{{$paginator->previousCursor()->encode()}}','{{ $paginator->getCursorName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="flex justify-center items-center size-8 sm:size-6 rounded-[6px] text-zinc-400 dark:text-white hover:bg-zinc-100 dark:hover:bg-white/20 hover:text-zinc-800 dark:hover:text-white">
                            <flux:icon.chevron-left variant="micro" class="rtl:hidden" />
                            <flux:icon.chevron-right variant="micro" class="hidden rtl:inline" />
                        </button>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="flex justify-center items-center size-8 sm:size-6 rounded-[6px] text-zinc-400 dark:text-white hover:bg-zinc-100 dark:hover:bg-white/20 hover:text-zinc-800 dark:hover:text-white">
                            <flux:icon.chevron-left variant="micro" class="rtl:hidden" />
                            <flux:icon.chevron-right variant="micro" class="hidden rtl:inline" />
                        </button>
                    @endif
                @endif

                @if ($paginator->hasMorePages())
                    @if(method_exists($paginator,'getCursorName'))
                        <button type="button" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->nextCursor()->encode() }}" wire:click="setPage('{{$paginator->nextCursor()->encode()}}','{{ $paginator->getCursorName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="flex justify-center items-center size-8 sm:size-6 rounded-[6px] text-zinc-400 dark:text-white hover:bg-zinc-100 dark:hover:bg-white/20 hover:text-zinc-800 dark:hover:text-white">
                            <flux:icon.chevron-right variant="micro" class="rtl:hidden" />
                            <flux:icon.chevron-left variant="micro" class="hidden rtl:inline" />
                        </button>
                    @else
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="flex justify-center items-center size-8 sm:size-6 rounded-[6px] text-zinc-400 dark:text-white hover:bg-zinc-100 dark:hover:bg-white/20 hover:text-zinc-800 dark:hover:text-white">
                            <flux:icon.chevron-right variant="micro" class="rtl:hidden" />
                            <flux:icon.chevron-left variant="micro" class="hidden rtl:inline" />
                        </button>
                    @endif
                @else
                    <div class="flex justify-center items-center size-8 sm:size-6 rounded-[6px] text-zinc-300 dark:text-zinc-500">
                        <flux:icon.chevron-right variant="micro" class="rtl:hidden" />
                        <flux:icon.chevron-left variant="micro" class="hidden rtl:inline" />
                    </div>
                @endif
            </div>
        @endif
    </div>
@else
    <div {{ $attributes->class('@container px-5 py-3.5 border-t border-zinc-100 dark:border-zinc-700 flex flex-wrap justify-between items-center gap-3') }} data-flux-pagination>
        {{-- Left: Showing text aligned with table columns --}}
        @if ($paginator->total() > 0)
            <div class="text-zinc-500 dark:text-zinc-400 text-xs font-medium whitespace-nowrap">
                {!! __('Showing') !!} {{ $paginator->firstItem() }} {!! __('to') !!} {{ $paginator->lastItem() }} {!! __('of') !!} {{ $paginator->total() }} {!! __('results') !!}
            </div>
        @else
            <div></div>
        @endif

        {{-- Center: Per Page Selector Dropdown (No Label, options 5, 10, 25, 50, 100) --}}
        <div class="flex items-center">
            <select
                wire:model.live="perPage"
                class="rounded-lg border border-zinc-200 bg-white px-2.5 py-1 text-xs font-semibold text-zinc-700 shadow-xs focus:border-emerald-500 focus:outline-hidden focus:ring-1 focus:ring-emerald-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200"
            >
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>

        {{-- Right: Pagination Buttons --}}
        @if ($paginator->hasPages())
            {{-- Mobile pagination --}}
            <div class="flex @[40rem]:hidden items-center bg-white border border-zinc-200 rounded-[8px] p-[1px] dark:bg-white/10 dark:border-white/10">
                @if ($paginator->onFirstPage())
                    <div aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="flex justify-center items-center size-8 sm:size-6 rounded-[6px] text-zinc-300 dark:text-zinc-500">
                        <flux:icon.chevron-left variant="micro" class="rtl:hidden" />
                        <flux:icon.chevron-right variant="micro" class="hidden rtl:inline" />
                    </div>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" aria-label="{{ __('pagination.previous') }}" class="flex justify-center items-center size-8 sm:size-6 rounded-[6px] text-zinc-400 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-white/20 hover:text-zinc-800 dark:hover:text-white">
                        <flux:icon.chevron-left variant="micro" class="rtl:hidden" />
                        <flux:icon.chevron-right variant="micro" class="hidden rtl:inline" />
                    </button>
                @endif

                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" aria-label="{{ __('pagination.next') }}" class="flex justify-center items-center size-8 sm:size-6 rounded-[6px] text-zinc-400 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-white/20 hover:text-zinc-800 dark:hover:text-white">
                        <flux:icon.chevron-right variant="micro" class="rtl:hidden" />
                        <flux:icon.chevron-left variant="micro" class="hidden rtl:inline" />
                    </button>
                @else
                    <div aria-label="{{ __('pagination.next') }}" class="flex justify-center items-center size-8 sm:size-6 rounded-[6px] text-zinc-300 dark:text-zinc-500">
                        <flux:icon.chevron-right variant="micro" class="rtl:hidden" />
                        <flux:icon.chevron-left variant="micro" class="hidden rtl:inline" />
                    </div>
                @endif
            </div>

            {{-- Desktop pagination --}}
            <div class="hidden @[40rem]:flex items-center bg-white border border-zinc-200 rounded-[8px] p-[1px] dark:bg-white/10 dark:border-white/10">
                @if ($paginator->onFirstPage())
                    <div aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="flex justify-center items-center size-6 rounded-[6px] text-zinc-300 dark:text-zinc-500">
                        <flux:icon.chevron-left variant="micro" class="rtl:hidden" />
                        <flux:icon.chevron-right variant="micro" class="hidden rtl:inline" />
                    </div>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" aria-label="{{ __('pagination.previous') }}" class="flex justify-center items-center size-6 rounded-[6px] text-zinc-400 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-white/20 hover:text-zinc-800 dark:hover:text-white">
                        <flux:icon.chevron-left variant="micro" class="rtl:hidden" />
                        <flux:icon.chevron-right variant="micro" class="hidden rtl:inline" />
                    </button>
                @endif

                @foreach (\Livewire\invade($paginator)->elements() as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <div
                            aria-disabled="true"
                            class="cursor-default flex justify-center items-center text-xs size-6 rounded-[6px] font-medium dark:text-zinc-400 text-zinc-400"
                        >{{ $element }}</div>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <div
                                    wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}"
                                    aria-current="page"
                                    class="cursor-default flex justify-center items-center text-xs h-6 px-2 rounded-[6px] font-medium dark:text-white text-zinc-800"
                                >{{ $page }}</div>
                            @else
                                <button
                                    wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}"
                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    type="button"
                                    class="text-xs h-6 px-2 rounded-[6px] text-zinc-400 font-medium dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-white/20 hover:text-zinc-800 dark:hover:text-white"
                                >{{ $page }}</button>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" aria-label="{{ __('pagination.next') }}" class="flex justify-center items-center size-6 rounded-[6px] text-zinc-400 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-white/20 hover:text-zinc-800 dark:hover:text-white">
                        <flux:icon.chevron-right variant="micro" class="rtl:hidden" />
                        <flux:icon.chevron-left variant="micro" class="hidden rtl:inline" />
                    </button>
                @else
                    <div aria-label="{{ __('pagination.next') }}" class="flex justify-center items-center size-6 rounded-[6px] text-zinc-300 dark:text-zinc-500">
                        <flux:icon.chevron-right variant="micro" class="rtl:hidden" />
                        <flux:icon.chevron-left variant="micro" class="hidden rtl:inline" />
                    </div>
                @endif
            </div>
        @else
            <div></div>
        @endif
    </div>
@endif
