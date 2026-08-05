<?php

namespace App\Livewire\Akademik;

use App\Models\Lembaga as LembagaModel;
use App\Models\Mapel as MapelModel;
use App\Services\LembagaService;
use App\Traits\WithPerPage;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Mata Pelajaran')]
class Mapel extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public ?int $editingId = null;

    public ?int $lembaga_id = null;

    #[Validate('required|string|max:255')]
    public string $nama = '';

    #[Validate('nullable|string|max:255')]
    public string $kitab = '';

    #[Validate('required|integer|min:0|max:100')]
    public int $kkm = 70;

    public function mount(LembagaService $lembagaService): void
    {
        $this->authorize('viewAny', MapelModel::class);
        $this->lembaga_id = $lembagaService->getActiveLembagaId() ?? LembagaModel::query()->active()->first()?->id;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(LembagaService $lembagaService): void
    {
        $this->authorize('create', MapelModel::class);

        $this->reset(['editingId', 'nama', 'kitab']);
        $this->lembaga_id = $lembagaService->getActiveLembagaId() ?? LembagaModel::query()->active()->first()?->id;
        $this->kkm = 70;

        $this->modal('mapel-form')->show();
    }

    public function edit(int $id): void
    {
        $mapel = MapelModel::query()->findOrFail($id);
        $this->authorize('update', $mapel);

        $this->editingId = $mapel->id;
        $this->lembaga_id = $mapel->lembaga_id;
        $this->nama = $mapel->nama;
        $this->kitab = (string) $mapel->kitab;
        $this->kkm = $mapel->kkm;

        $this->modal('mapel-form')->show();
    }

    public function save(): void
    {
        $data = $this->validate([
            'nama' => 'required|string|max:255',
            'kitab' => 'nullable|string|max:255',
            'kkm' => 'required|integer|min:0|max:100',
            'lembaga_id' => 'nullable|integer|exists:lembagas,id',
        ]);

        if ($this->editingId) {
            $mapel = MapelModel::query()->findOrFail($this->editingId);
            $this->authorize('update', $mapel);
            $mapel->update($data);
        } else {
            $this->authorize('create', MapelModel::class);
            MapelModel::query()->create($data);
        }

        $this->modal('mapel-form')->close();
        $this->reset(['editingId', 'nama', 'kitab']);
        $this->kkm = 70;

        Flux::toast(variant: 'success', text: __('Data mata pelajaran berhasil disimpan.'));
    }

    public function delete(int $id): void
    {
        $mapel = MapelModel::query()->withCount(['jadwalPelajarans', 'nilais'])->findOrFail($id);
        $this->authorize('delete', $mapel);

        if ($mapel->jadwal_pelajarans_count + $mapel->nilais_count > 0) {
            Flux::toast(variant: 'danger', text: __('Mata pelajaran tidak bisa dihapus karena sudah memiliki jadwal atau nilai terikat.'));

            return;
        }

        $mapel->delete();

        Flux::toast(variant: 'success', text: __('Data mata pelajaran berhasil dihapus.'));
    }

    /**
     * @return LengthAwarePaginator<int, MapelModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return MapelModel::query()
            ->with('lembaga')
            ->when($activeLembagaId, fn ($query) => $query->where(fn ($q) => $q->where('lembaga_id', $activeLembagaId)->orWhereNull('lembaga_id')))
            ->when($this->search, fn ($query) => $query->where('nama', 'like', "%{$this->search}%"))
            ->orderBy('nama')
            ->paginate($this->perPage);
    }

    /**
     * @return Collection<int, LembagaModel>
     */
    #[Computed]
    public function lembagaOptions(): Collection
    {
        return LembagaModel::query()->active()->ordered()->get();
    }

    public function render(): View
    {
        return view('livewire.akademik.mapel');
    }
}
