<?php

namespace App\Livewire\Admin;

use App\Models\Lembaga as LembagaModel;
use App\Traits\WithPerPage;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Manajemen Lembaga')]
class Lembagas extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    #[Validate('required|string|max:255')]
    public string $nama = '';

    #[Validate('required|string|max:100')]
    public string $kode = '';

    #[Validate('required|string|max:100')]
    public string $jenjang = 'MDTA';

    #[Validate('nullable|string|max:100')]
    public string $nsm = '';

    #[Validate('nullable|string|max:255')]
    public string $kepala_lembaga = '';

    #[Validate('nullable|string')]
    public string $alamat = '';

    #[Validate('nullable|string|max:50')]
    public string $telepon = '';

    public bool $is_active = true;

    public int $urutan = 1;

    public function updatedNama(string $value): void
    {
        if (! $this->editingId && empty($this->kode)) {
            $this->kode = Str::slug($value);
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->authorize('viewAny', LembagaModel::class);
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'nama', 'kode', 'nsm', 'kepala_lembaga', 'alamat', 'telepon']);
        $this->jenjang = 'TPQ';
        $this->is_active = true;
        $this->urutan = (int) (LembagaModel::max('urutan') ?? 0) + 1;
    }

    public function create(): void
    {
        $this->authorize('create', LembagaModel::class);

        $this->resetForm();
        $this->modal('lembaga-form')->show();
    }

    public function edit(int $id): void
    {
        $lembaga = LembagaModel::query()->findOrFail($id);
        $this->authorize('update', $lembaga);

        $this->editingId = $lembaga->id;
        $this->nama = $lembaga->nama;
        $this->kode = $lembaga->kode;
        $this->jenjang = $lembaga->jenjang;
        $this->nsm = (string) $lembaga->nsm;
        $this->kepala_lembaga = (string) $lembaga->kepala_lembaga;
        $this->alamat = (string) $lembaga->alamat;
        $this->telepon = (string) $lembaga->telepon;
        $this->is_active = $lembaga->is_active;
        $this->urutan = $lembaga->urutan;

        $this->modal('lembaga-form')->show();
    }

    public function save(): void
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:100|unique:lembagas,kode,'.($this->editingId ?? 'NULL'),
            'jenjang' => 'required|string|max:100',
            'nsm' => 'nullable|string|max:100',
            'kepala_lembaga' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'urutan' => 'integer|min:0',
        ]);

        $data = [
            'nama' => $this->nama,
            'kode' => Str::slug($this->kode),
            'jenjang' => strtoupper($this->jenjang),
            'nsm' => $this->nsm ?: null,
            'kepala_lembaga' => $this->kepala_lembaga ?: null,
            'alamat' => $this->alamat ?: null,
            'telepon' => $this->telepon ?: null,
            'is_active' => $this->is_active,
            'urutan' => $this->urutan,
        ];

        if ($this->editingId) {
            $lembaga = LembagaModel::query()->findOrFail($this->editingId);
            $this->authorize('update', $lembaga);
            $lembaga->update($data);
        } else {
            $this->authorize('create', LembagaModel::class);
            LembagaModel::query()->create($data);
        }

        $this->modal('lembaga-form')->close();
        $this->resetForm();

        Flux::toast(variant: 'success', text: __('Unit Lembaga berhasil disimpan.'));
    }

    public function delete(?int $id = null): void
    {
        $targetId = $id ?? $this->deletingId;
        if (! $targetId) {
            return;
        }

        $lembaga = LembagaModel::query()->withCount(['kelas', 'santris', 'mapels'])->findOrFail($targetId);
        $this->authorize('delete', $lembaga);

        if ($lembaga->kelas_count + $lembaga->santris_count + $lembaga->mapels_count > 0) {
            Flux::toast(variant: 'danger', text: __('Lembaga ini tidak dapat dihapus karena masih memiliki data kelas, santri, atau mata pelajaran terikat.'));

            return;
        }

        $lembaga->delete();
        $this->deletingId = null;

        Flux::toast(variant: 'success', text: __('Unit Lembaga berhasil dihapus.'));
    }

    /**
     * @return LengthAwarePaginator<int, LembagaModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return LembagaModel::query()
            ->withCount(['kelas', 'santris', 'mapels'])
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%'.$this->search.'%')
                        ->orWhere('kode', 'like', '%'.$this->search.'%')
                        ->orWhere('jenjang', 'like', '%'.$this->search.'%');
                });
            })
            ->ordered()
            ->paginate($this->perPage);
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function defaultJenjangs(): array
    {
        return ['PAUD', 'TPQ', 'MDTA', 'MI', 'MTS', 'MA', 'CUSTOM'];
    }

    public function render(): View
    {
        return view('livewire.admin.lembagas');
    }
}
