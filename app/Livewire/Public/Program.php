<?php

namespace App\Livewire\Public;

use App\Models\Setting;
use Illuminate\Contracts\View\View;
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

    public function render(): View
    {
        return view('livewire.public.program');
    }
}
