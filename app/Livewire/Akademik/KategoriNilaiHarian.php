<?php

namespace App\Livewire\Akademik;

use App\Models\KategoriNilaiHarian as KategoriModel;
use App\Models\Lembaga;
use App\Services\LembagaService;
use App\Traits\WithPerPage;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Kategori Nilai Harian')]
class KategoriNilaiHarian extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $nama = '';

    public int $bobot = 10;

    public bool $is_wajib = true;

    public string $keterangan = '';

    public ?int $lembaga_id = null;

    public function mount(): void
    {
        $this->authorize('viewAny', KategoriModel::class);
        $this->lembaga_id = app(LembagaService::class)->getActiveLembagaId();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('create', KategoriModel::class);

        $this->reset(['editingId', 'nama', 'keterangan']);
        $this->bobot = 10;
        $this->is_wajib = true;
        $this->lembaga_id = app(LembagaService::class)->getActiveLembagaId();

        $this->modal('kategori-form')->show();
    }

    public function edit(int $id): void
    {
        $kategori = KategoriModel::query()->visibleTo()->findOrFail($id);
        $this->authorize('update', $kategori);

        $this->editingId = $kategori->id;
        $this->nama = $kategori->nama;
        $this->bobot = $kategori->bobot;
        $this->is_wajib = $kategori->is_wajib;
        $this->keterangan = $kategori->keterangan ?? '';
        $this->lembaga_id = $kategori->lembaga_id;

        $this->modal('kategori-form')->show();
    }

    public function save(): void
    {
        $this->validate([
            'nama' => 'required|string|max:100',
            'bobot' => 'required|integer|min:1|max:100',
            'is_wajib' => 'boolean',
            'keterangan' => 'nullable|string|max:255',
            'lembaga_id' => 'nullable|exists:lembagas,id',
        ], [
            'nama.required' => 'Nama kategori nilai wajib diisi.',
            'bobot.required' => 'Bobot persentase wajib diisi.',
            'bobot.min' => 'Bobot minimal 1%.',
            'bobot.max' => 'Bobot maksimal 100%.',
        ]);

        $lembagaId = $this->lembaga_id ?? app(LembagaService::class)->getActiveLembagaId();

        if ($this->editingId) {
            $kategori = KategoriModel::query()->visibleTo()->findOrFail($this->editingId);
            $kategori->update([
                'lembaga_id' => $lembagaId,
                'nama' => $this->nama,
                'bobot' => $this->bobot,
                'is_wajib' => $this->is_wajib,
                'keterangan' => $this->keterangan,
            ]);
        } else {
            KategoriModel::query()->create([
                'lembaga_id' => $lembagaId,
                'nama' => $this->nama,
                'bobot' => $this->bobot,
                'is_wajib' => $this->is_wajib,
                'keterangan' => $this->keterangan,
            ]);
        }

        $this->modal('kategori-form')->close();
        $this->reset(['editingId', 'nama', 'keterangan']);

        Flux::toast(variant: 'success', text: __('Kategori Nilai Harian berhasil disimpan.'));
    }

    public function delete(?int $id = null): void
    {
        $targetId = $id ?? $this->deletingId;
        if (! $targetId) {
            return;
        }

        $kategori = KategoriModel::query()->visibleTo()->findOrFail($targetId);
        $this->authorize('delete', $kategori);

        $kategori->delete();
        $this->deletingId = null;

        Flux::toast(variant: 'success', text: __('Kategori Nilai Harian berhasil dihapus.'));
    }

    /**
     * @return LengthAwarePaginator<int, KategoriModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return KategoriModel::query()
            ->visibleTo()
            ->with('lembaga')
            ->when($this->search !== '', fn ($q) => $q->where('nama', 'like', '%'.$this->search.'%'))
            ->orderBy('id', 'asc')
            ->paginate($this->perPage);
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
        return view('livewire.akademik.kategori-nilai-harian');
    }
}
