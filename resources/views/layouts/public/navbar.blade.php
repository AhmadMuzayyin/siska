@props(['setting' => null])

@php
    $theme = $setting?->landing_theme ?? 'default';
@endphp

@if ($theme === 'pixigon')
    @include('layouts.public.navbars.pixigon', ['setting' => $setting])
@else
    @include('layouts.public.navbars.default', ['setting' => $setting])
@endif
