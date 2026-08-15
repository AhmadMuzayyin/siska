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
        $this->js('window.location.reload()');
    }

    public function updatedSelectedLembagaId(string $value, LembagaService $service): void
    {
        if ($value === 'all' || empty($value)) {
            $service->setActiveLembagaId(null);
        } else {
            $service->setActiveLembagaId((int) $value);
        }

        $this->dispatch('lembaga-changed');
        $this->js('window.location.reload()');
    }

    /**
     * @return Collection<int, Lembaga>
     */
    #[Computed]
    public function lembagas(): Collection
    {
        return Lembaga::query()->active()->ordered()->get();
    }

    public function render(): View
    {
        return view('livewire.admin.lembaga-switcher');
    }
}
