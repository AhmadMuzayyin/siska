<?php

namespace App\Livewire\Admin;

use App\Models\Lembaga;
use App\Services\LembagaService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class LembagaSwitcher extends Component
{
    public ?string $selectedLembagaId = 'all';

    public function mount(LembagaService $service): void
    {
        $activeId = $service->getActiveLembagaId();
        $this->selectedLembagaId = $activeId ? (string) $activeId : 'all';
    }

    public function switchLembaga(?int $id, LembagaService $service): void
    {
        $service->setActiveLembagaId($id);
        $this->selectedLembagaId = $id ? (string) $id : 'all';

        $this->dispatch('lembaga-changed');
        $this->redirect(url()->previous(), navigate: true);
    }

    public function updatedSelectedLembagaId(string $value, LembagaService $service): void
    {
        if ($value === 'all' || empty($value)) {
            $service->setActiveLembagaId(null);
        } else {
            $service->setActiveLembagaId((int) $value);
        }

        $this->dispatch('lembaga-changed');
        $this->redirect(url()->previous(), navigate: true);
    }

    /**
     * @return Collection<int, Lembaga>
     */
    #[Computed]
    public function lembagas(): Collection
    {
        return Lembaga::query()->active()->ordered()->get();
    }

    #[Computed]
    public function activeLembagaName(): string
    {
        if ($this->selectedLembagaId === 'all' || empty($this->selectedLembagaId)) {
            return __('Semua Lembaga');
        }

        $lembaga = $this->lembagas->firstWhere('id', (int) $this->selectedLembagaId);

        return $lembaga?->nama ?? __('Semua Lembaga');
    }

    public function render(): View
    {
        return view('livewire.admin.lembaga-switcher');
    }
}
