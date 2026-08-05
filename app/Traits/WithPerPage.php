<?php

namespace App\Traits;

use Livewire\Attributes\Url;

trait WithPerPage
{
    #[Url]
    public int $perPage = 5;

    public function updatedPerPage(): void
    {
        if (! in_array($this->perPage, [5, 10, 25, 50, 100], true)) {
            $this->perPage = 5;
        }

        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }
}
