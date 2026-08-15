<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => __('Masuk Portal')])
    </head>
    <body class="min-h-screen antialiased selection:bg-emerald-500 selection:text-white">
        <livewire:auth.login />

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
