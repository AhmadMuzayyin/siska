<?php

namespace App\Livewire\Konten;

use App\Enums\GalleryType;
use App\Models\Gallery as GalleryModel;
use App\Traits\WithPerPage;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Galeri')]
class Galleries extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public string $typeFilter = '';

    public ?int $editingId = null;

    #[Validate('required|string|in:kegiatan,wisata,bimbingan')]
    public string $type = 'kegiatan';

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|url|max:2048')]
    public string $image = '';

    #[Validate('nullable|string')]
    public string $description = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTypeFilter(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->authorize('viewAny', GalleryModel::class);
    }

    public function create(): void
    {
        $this->authorize('create', GalleryModel::class);

        $this->reset(['editingId', 'title', 'image', 'description']);
        $this->type = 'kegiatan';

        $this->modal('gallery-form')->show();
    }

    public function edit(int $id): void
    {
        $gallery = GalleryModel::query()->findOrFail($id);
        $this->authorize('update', $gallery);

        $this->editingId = $gallery->id;
        $this->type = $gallery->type->value;
        $this->title = $gallery->title;
        $this->image = $gallery->image;
        $this->description = (string) $gallery->description;

        $this->modal('gallery-form')->show();
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            $gallery = GalleryModel::query()->findOrFail($this->editingId);
            $this->authorize('update', $gallery);
            $gallery->update($data);
        } else {
            $this->authorize('create', GalleryModel::class);
            GalleryModel::query()->create($data);
        }

        $this->modal('gallery-form')->close();
        $this->reset(['editingId', 'title', 'image', 'description']);
        $this->type = 'kegiatan';

        Flux::toast(variant: 'success', text: __('Galeri berhasil disimpan.'));
    }

    public function delete(int $id): void
    {
        $gallery = GalleryModel::query()->findOrFail($id);
        $this->authorize('delete', $gallery);

        $gallery->delete();

        Flux::toast(variant: 'success', text: __('Galeri berhasil dihapus.'));
    }

    /**
     * @return LengthAwarePaginator<int, GalleryModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return GalleryModel::query()
            ->when($this->search !== '', fn ($query) => $query->where('title', 'like', '%'.$this->search.'%'))
            ->when($this->typeFilter !== '', fn ($query) => $query->where('type', $this->typeFilter))
            ->orderByDesc('id')
            ->paginate($this->perPage);
    }

    /**
     * @return array<int, GalleryType>
     */
    #[Computed]
    public function types(): array
    {
        return GalleryType::cases();
    }

    public function render(): View
    {
        return view('livewire.konten.galleries');
    }
}
