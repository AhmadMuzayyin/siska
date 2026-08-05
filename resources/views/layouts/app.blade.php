<x-layouts::app.sidebar :title="$title ?? null">
    <livewire:active-semester-banner />
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
