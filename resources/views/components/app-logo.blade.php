@props([
    'sidebar' => false,
    'name' => null,
])

@php $brandName = $name ?? config('app.name', 'Laravel'); @endphp

@if($sidebar)
    <flux:sidebar.brand :name="$brandName" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-[#06382b] text-white dark:bg-emerald-600">
            <x-app-logo-icon class="size-5 fill-current text-emerald-400" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="$brandName" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-[#06382b] text-white dark:bg-emerald-600">
            <x-app-logo-icon class="size-5 fill-current text-emerald-400" />
        </x-slot>
    </flux:brand>
@endif
