@props([
    'sidebar' => false,
    'name' => null,
])

@php $brandName = $name ?? config('app.name', 'Laravel'); @endphp

@if($sidebar)
    <flux:sidebar.brand :name="$brandName" {{ $attributes->class('gap-2.5 in-data-flux-sidebar-collapsed-desktop:in-data-flux-sidebar-active:opacity-100! in-data-flux-sidebar-collapsed-desktop:in-data-flux-sidebar-active:static!') }}>
        <x-slot name="logo" class="flex size-8 items-center justify-center shrink-0">
            <x-app-logo-icon class="size-7 text-emerald-600 dark:text-emerald-400 fill-current" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="$brandName" {{ $attributes }}>
        <x-slot name="logo" class="flex size-8 items-center justify-center shrink-0">
            <x-app-logo-icon class="size-7 text-emerald-600 dark:text-emerald-400 fill-current" />
        </x-slot>
    </flux:brand>
@endif
