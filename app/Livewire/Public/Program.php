<?php

namespace App\Livewire\Public;

use App\Models\Program as ProgramModel;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::public')]
#[Title('Program Pendidikan')]
class Program extends Component
{
    #[Computed]
    public function setting(): ?Setting
    {
        return Setting::query()->first();
    }

    /**
     * @return Collection<int, ProgramModel>
     */
    #[Computed]
    public function programs(): Collection
    {
        return ProgramModel::query()
            ->with('lembaga')
            ->active()
            ->ordered()
            ->get();
    }

    public function render(): View
    {
        return view('livewire.public.program');
    }
}
