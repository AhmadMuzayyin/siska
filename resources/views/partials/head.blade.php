<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

@php $resolvedTitle = filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel'); @endphp

<title>{{ $resolvedTitle }}</title>

@isset($metaDescription)
    <meta name="description" content="{{ $metaDescription }}">
@endisset
@isset($metaKeyword)
    <meta name="keywords" content="{{ $metaKeyword }}">
@endisset

<meta property="og:title" content="{{ $resolvedTitle }}">
<meta property="og:type" content="website">
@isset($metaDescription)
    <meta property="og:description" content="{{ $metaDescription }}">
@endisset

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

@fonts

<style>
    /* Livewire Navigation Loading Bar (Fresh Emerald Green Theme) */
    #nprogress .bar,
    #livewire-progress-bar,
    .livewire-loading-bar,
    .nprogress-custom-parent #nprogress .bar {
        background: #059669 !important;
        background: linear-gradient(to right, #059669, #06382b, #10b981) !important;
        height: 3.5px !important;
    }
    #nprogress .peg {
        box-shadow: 0 0 10px #059669, 0 0 5px #059669 !important;
    }
</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
