@php $setting = \App\Models\Setting::query()->first(); @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', [
            'title' => $title ?? null,
            'metaDescription' => $setting?->meta_deskripsi,
            'metaKeyword' => $setting?->meta_keyword,
        ])
    </head>
    <body class="min-h-screen bg-[#edf7f4] text-zinc-900 antialiased selection:bg-emerald-500 selection:text-white dark:bg-[#021d16] dark:text-zinc-100">
        <x-layouts::public.navbar :setting="$setting" />

        <main>
            {{ $slot }}
        </main>

        <x-layouts::public.footer :setting="$setting" />

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        <x-cookie-consent />

        @auth
            @if (auth()->user()->role === \App\Enums\UserRole::Admin)
                <livewire:public.landing-page-builder />
            @endif
        @endauth

        @fluxScripts
    </body>
</html>
