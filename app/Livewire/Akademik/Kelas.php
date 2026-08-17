<?php

namespace App\Livewire\Akademik;

use App\Enums\GuruStatus;
use App\Models\Guru as GuruModel;
use App\Models\Kelas as KelasModel;
use App\Models\Lembaga as LembagaModel;
use App\Models\WaliKelas as WaliKelasModel;
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

#[Title('Kelas')]
class Kelas extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public ?int $lembaga_id = null;

    #[Validate('required|string|max:255')]
    public string $nama = '';

    #[Validate('required|integer|min:1|max:1000')]
    public int $kapasitas = 30;

    #[Validate('nullable|integer|exists:gurus,id')]
    public ?int $waliKelasGuruId = null;

    public function mount(LembagaService $lembagaService): void
    {
        $this->authorize('viewAny', KelasModel::class);
        $this->lembaga_id = $lembagaService->getActiveLembagaId() ?? LembagaModel::query()->active()->first()?->id;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(LembagaService $lembagaService): void
    {
        $this->authorize('create', KelasModel::class);

        $this->reset(['editingId', 'nama', 'waliKelasGuruId']);
        $this->lembaga_id = $lembagaService->getActiveLembagaId() ?? LembagaModel::query()->active()->first()?->id;
        $this->kapasitas = 30;

        $this->modal('kelas-form')->show();
    }

    public function edit(int $id): void
    {
        $kelas = KelasModel::query()->with('waliKelas')->findOrFail($id);
        $this->authorize('update', $kelas);

        $this->editingId = $kelas->id;
        $this->lembaga_id = $kelas->lembaga_id;
        $this->nama = $kelas->nama;
        $this->kapasitas = $kelas->kapasitas;
        $this->waliKelasGuruId = $kelas->waliKelas?->guru_id;

        $this->modal('kelas-form')->show();
    }

    public function save(): void
    {
        $data = $this->validate([
            'nama' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1|max:1000',
            'lembaga_id' => 'nullable|integer|exists:lembagas,id',
            'waliKelasGuruId' => 'nullable|integer|exists:gurus,id',
        ]);

        $waliKelasGuruId = $data['waliKelasGuruId'];
        unset($data['waliKelasGuruId']);

        if ($this->editingId) {
            $kelas = KelasModel::query()->findOrFail($this->editingId);
            $this->authorize('update', $kelas);
            $kelas->update($data);
        } else {
            $this->authorize('create', KelasModel::class);
            $kelas = KelasModel::query()->create($data);
        }

        if ($waliKelasGuruId) {
            WaliKelasModel::query()->updateOrCreate(
                ['kelas_id' => $kelas->id],
                ['guru_id' => $waliKelasGuruId],
            );
        } else {
            $kelas->waliKelas()->delete();
        }

        $this->modal('kelas-form')->close();
        $this->reset(['editingId', 'nama', 'waliKelasGuruId']);
        $this->kapasitas = 30;

        Flux::toast(variant: 'success', text: __('Data kelas berhasil disimpan.'));
    }

    public function delete(?int $id = null): void
    {
        $targetId = $id ?? $this->deletingId;
        if (! $targetId) {
            return;
        }

        $kelas = KelasModel::query()->withCount(['santris', 'waliKelas'])->findOrFail($targetId);
        $this->authorize('delete', $kelas);

        if ($kelas->santris_count > 0) {
            Flux::toast(variant: 'danger', text: __('Kelas tidak bisa dihapus karena masih memiliki santri.'));

            return;
        }

        $kelas->waliKelas()->delete();
        $kelas->delete();
        $this->deletingId = null;

        Flux::toast(variant: 'success', text: __('Data kelas berhasil dihapus.'));
    }

    /**
     * @return LengthAwarePaginator<int, KelasModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return KelasModel::query()
            ->withCount('santris')
            ->with(['waliKelas.guru.user', 'lembaga'])
            ->when($activeLembagaId, fn ($query) => $query->where('lembaga_id', $activeLembagaId))
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

    /**
     * @return Collection<int, GuruModel>
     */
    #[Computed]
    public function availableGurus(): Collection
    {
        return GuruModel::query()
            ->with('user')
            ->where('status', GuruStatus::Aktif)
            ->get();
    }

    public function render(): View
    {
        return view('livewire.akademik.kelas');
    }
}
