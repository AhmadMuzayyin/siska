<?php

namespace App\Livewire\Public;

use App\Enums\GalleryType;
use App\Models\Gallery;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts::public')]
#[Title('Galeri Foto')]
class Galeri extends Component
{
    use WithPagination;

    public string $activeGalleryType = 'semua';

    #[Computed]
    public function setting(): ?Setting
    {
        return Setting::query()->first();
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function galleryTypes(): array
    {
        return array_map(fn (GalleryType $type) => $type->value, GalleryType::cases());
    }

    #[Computed]
    public function galleries()
    {
        $query = Gallery::query()->latest();

        if ($this->activeGalleryType !== 'semua') {
            $query->where('type', $this->activeGalleryType);
        }

        return $query->paginate(12);
    }

    public function setGalleryType(string $type): void
    {
        $this->activeGalleryType = $type;
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.public.galeri');
    }
}
