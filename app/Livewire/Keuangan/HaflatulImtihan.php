<?php

namespace App\Livewire\Keuangan;

use App\Models\HaflatulImtihan as HaflatulModel;
use App\Models\Kelas as KelasModel;
use App\Models\Santri as SantriModel;
use App\Models\Semester;
use App\Services\LembagaService;
use App\Services\SemesterService;
use App\Traits\WithPerPage;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Sumbangan Haflatul Imtihan')]
class HaflatulImtihan extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public ?int $kelasFilter = null;

    public ?string $santriId = null;

    public int $nominal = 250000;

    public string $tanggal = '';

    public string $metode = 'cash';

    public string $keterangan = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedKelasFilter(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->authorize('viewAny', HaflatulModel::class);
        $this->tanggal = now()->toDateString();
    }

    public function create(): void
    {
        $this->authorize('create', HaflatulModel::class);
        $this->reset(['santriId', 'keterangan']);
        $this->tanggal = now()->toDateString();
        $this->modal('haflatul-form')->show();
    }

    public function save(SemesterService $semesterService): void
    {
        $this->authorize('create', HaflatulModel::class);

        $data = $this->validate([
            'santriId' => 'required|integer|exists:santris,id',
            'nominal' => 'required|integer|min:1000',
            'tanggal' => 'required|date',
            'metode' => 'required|string',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $semester = $semesterService->current();

        if ($semester === null) {
            Flux::toast(variant: 'danger', text: __('Tidak ada semester aktif. Pengaturan semester diperlukan.'));

            return;
        }

        HaflatulModel::query()->create([
            'santri_id' => $data['santriId'],
            'semester_id' => $semester->id,
            'nominal' => $data['nominal'],
            'tanggal' => $data['tanggal'],
            'metode_pembayaran' => $data['metode'],
            'keterangan' => $data['keterangan'] ?? null,
        ]);

        $this->modal('haflatul-form')->close();
        $this->reset(['santriId', 'keterangan']);

        Flux::toast(variant: 'success', text: __('Pembayaran Haflatul Imtihan berhasil dicatat.'));
    }

    public function delete(int $id): void
    {
        $item = HaflatulModel::query()->findOrFail($id);
        $this->authorize('delete', $item);
        $item->delete();

        Flux::toast(variant: 'success', text: __('Catatan pembayaran berhasil dihapus.'));
    }

    /**
     * @return LengthAwarePaginator<int, HaflatulModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return HaflatulModel::query()
            ->with(['santri.kelas', 'santri.lembaga', 'semester'])
            ->when($activeLembagaId, fn ($query) => $query->whereHas('santri', fn ($s) => $s->where('lembaga_id', $activeLembagaId)))
            ->when($this->kelasFilter, fn ($query) => $query->whereHas('santri', fn ($q) => $q->where('kelas_id', $this->kelasFilter)))
            ->when($this->search !== '', function ($query) {
                $query->whereHas('santri', function ($q) {
                    $q->where('nama_lengkap', 'like', '%'.$this->search.'%')
                        ->orWhere('noinduk', 'like', '%'.$this->search.'%');
                });
            })
            ->orderByDesc('tanggal')
            ->paginate($this->perPage);
    }

    /**
     * @return Collection<int, KelasModel>
     */
    #[Computed]
    public function kelasOptions(): Collection
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return KelasModel::query()
            ->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId))
            ->orderBy('nama')
            ->get();
    }

    /**
     * @return Collection<int, SantriModel>
     */
    #[Computed]
    public function santriOptions(): Collection
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return SantriModel::query()
            ->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId))
            ->orderBy('nama_lengkap')
            ->get();
    }

    #[Computed]
    public function activeSemester(): ?Semester
    {
        return Semester::query()->active()->first();
    }

    public function render(): View
    {
        return view('livewire.keuangan.haflatul-imtihan');
    }
}
