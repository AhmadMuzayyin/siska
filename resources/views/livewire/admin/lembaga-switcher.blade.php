<div class="w-full">
    <flux:select wire:model.live="selectedLembagaId" class="w-full">
        <flux:select.option value="all">
            🏛️ {{ __('Semua Lembaga') }}
        </flux:select.option>
        @foreach ($this->lembagas as $lembaga)
            <flux:select.option value="{{ $lembaga->id }}" :selected="$selectedLembagaId == $lembaga->id">
                🎓 {{ $lembaga->nama }}
            </flux:select.option>
        @endforeach
    </flux:select>
</div>
