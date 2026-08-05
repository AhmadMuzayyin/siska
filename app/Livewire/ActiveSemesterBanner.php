<?php

namespace App\Livewire;

use App\Models\Semester;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ActiveSemesterBanner extends Component
{
    /**
     * Check whether there is a currently active semester.
     */
    #[Computed]
    public function hasActiveSemester(): bool
    {
        return Semester::query()->active()->exists();
    }

    public function render(): View
    {
        return view('livewire.active-semester-banner');
    }
}
