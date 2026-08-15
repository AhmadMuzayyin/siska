<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

@php 
    $setting = \App\Models\Setting::query()->first();
    $appName = $setting?->lembaga ?? config('app.name', 'SISKA');
    $resolvedTitle = filled($title ?? null) ? $title.' - '.$appName : $appName;
    $faviconUrl = ($setting?->favicon && \Illuminate\Support\Facades\Storage::disk('public')->exists($setting->favicon))
        ? \Illuminate\Support\Facades\Storage::url($setting->favicon)
        : null;
@endphp

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

@if ($faviconUrl)
    <link rel="icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
@else
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
@endif

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

    /* Prevent browser autofill from rendering dark/gray/slate input backgrounds */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active,
    textarea:-webkit-autofill,
    textarea:-webkit-autofill:hover,
    textarea:-webkit-autofill:focus,
    select:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px #ffffff inset !important;
        -webkit-text-fill-color: #18181b !important;
        caret-color: #18181b !important;
        transition: background-color 5000s ease-in-out 0s;
    }
</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
