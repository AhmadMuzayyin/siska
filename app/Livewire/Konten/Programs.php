<?php

namespace App\Livewire\Konten;

use App\Models\Lembaga;
use App\Models\Program;
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

#[Title('Program Pendidikan')]
class Programs extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public string $lembagaFilter = '';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    #[Validate('nullable|integer|exists:lembagas,id')]
    public ?int $lembaga_id = null;

    #[Validate('required|string|max:255')]
    public string $nama_program = '';

    #[Validate('nullable|string|max:100')]
    public string $kategori_badge = '';

    #[Validate('required|string')]
    public string $deskripsi_singkat = '';

    /**
     * @var array<int, array{judul: string, deskripsi: string}>
     */
    #[Validate([
        'materi_unggulan' => 'nullable|array',
        'materi_unggulan.*.judul' => 'required|string|max:255',
        'materi_unggulan.*.deskripsi' => 'nullable|string|max:500',
    ])]
    public array $materi_unggulan = [];

    #[Validate('nullable|url|max:500')]
    public string $gambar_url = '';

    #[Validate('nullable|string|max:50')]
    public string $icon = 'book-open';

    #[Validate('required|integer|min:0')]
    public int $urutan = 0;

    #[Validate('boolean')]
    public bool $is_active = true;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedLembagaFilter(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->authorize('viewAny', Program::class);
    }

    /**
     * @return Collection<int, Lembaga>
     */
    #[Computed]
    public function lembagas(): Collection
    {
        return Lembaga::query()->active()->ordered()->get();
    }

    /**
     * @return LengthAwarePaginator<Program>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        $lembagaId = app(LembagaService::class)->getActiveLembagaId();

        return Program::query()
            ->with('lembaga')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nama_program', 'like', "%{$this->search}%")
                        ->orWhere('kategori_badge', 'like', "%{$this->search}%")
                        ->orWhere('deskripsi_singkat', 'like', "%{$this->search}%");
                });
            })
            ->when($this->lembagaFilter !== '', fn ($q) => $q->where('lembaga_id', $this->lembagaFilter))
            ->when($lembagaId, fn ($q) => $q->where(fn ($sub) => $sub->where('lembaga_id', $lembagaId)->orWhereNull('lembaga_id')))
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);
    }

    public function addMateri(): void
    {
        $this->materi_unggulan[] = ['judul' => '', 'deskripsi' => ''];
    }

    public function removeMateri(int $index): void
    {
        unset($this->materi_unggulan[$index]);
        $this->materi_unggulan = array_values($this->materi_unggulan);
    }

    public function create(): void
    {
        $this->authorize('create', Program::class);

        $this->reset([
            'editingId',
            'lembaga_id',
            'nama_program',
            'kategori_badge',
            'deskripsi_singkat',
            'materi_unggulan',
            'gambar_url',
            'icon',
            'urutan',
            'is_active',
        ]);

        $this->icon = 'book-open';
        $this->is_active = true;
        $this->urutan = (Program::max('urutan') ?? 0) + 1;
        $this->materi_unggulan = [
            ['judul' => '', 'deskripsi' => ''],
        ];

        $this->modal('program-form')->show();
    }

    public function edit(int $id): void
    {
        $program = Program::query()->findOrFail($id);
        $this->authorize('update', $program);

        $this->editingId = $program->id;
        $this->lembaga_id = $program->lembaga_id;
        $this->nama_program = $program->nama_program;
        $this->kategori_badge = (string) $program->kategori_badge;
        $this->deskripsi_singkat = $program->deskripsi_singkat;
        $this->materi_unggulan = is_array($program->materi_unggulan) ? $program->materi_unggulan : [];
        $this->gambar_url = (string) $program->gambar_url;
        $this->icon = $program->icon ?: 'book-open';
        $this->urutan = $program->urutan;
        $this->is_active = $program->is_active;

        $this->modal('program-form')->show();
    }

    public function save(): void
    {
        $data = $this->validate();

        // Clean empty materi unggulan
        if (! empty($data['materi_unggulan'])) {
            $data['materi_unggulan'] = array_values(array_filter($data['materi_unggulan'], function ($item) {
                return ! empty(trim($item['judul'] ?? ''));
            }));
        }

        if ($this->editingId) {
            $program = Program::query()->findOrFail($this->editingId);
            $this->authorize('update', $program);
            $program->update($data);
            Flux::toast(variant: 'success', text: __('Program pendidikan berhasil diperbarui.'));
        } else {
            $this->authorize('create', Program::class);
            Program::query()->create($data);
            Flux::toast(variant: 'success', text: __('Program pendidikan berhasil ditambahkan.'));
        }

        $this->modal('program-form')->close();
        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        $program = Program::query()->findOrFail($id);
        $this->authorize('update', $program);

        $program->update(['is_active' => ! $program->is_active]);

        Flux::toast(variant: 'success', text: __('Status program berhasil diubah.'));
    }

    public function delete(): void
    {
        if (! $this->deletingId) {
            return;
        }

        $program = Program::query()->findOrFail($this->deletingId);
        $this->authorize('delete', $program);

        $program->delete();

        $this->deletingId = null;
        $this->modal('confirm-delete-program-modal')->close();

        Flux::toast(variant: 'success', text: __('Program berhasil dihapus.'));
    }

    protected function resetForm(): void
    {
        $this->reset([
            'editingId',
            'lembaga_id',
            'nama_program',
            'kategori_badge',
            'deskripsi_singkat',
            'materi_unggulan',
            'gambar_url',
            'icon',
            'urutan',
            'is_active',
        ]);
    }

    public function render(): View
    {
        return view('livewire.konten.programs');
    }
}
