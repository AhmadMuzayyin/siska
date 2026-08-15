@props(['setting' => null])

@php
    $theme = $setting?->landing_theme ?? 'default';
@endphp

@if ($theme === 'pixigon')
    @include('layouts.public.footers.pixigon', ['setting' => $setting])
@else
    @include('layouts.public.footers.default', ['setting' => $setting])
@endif
