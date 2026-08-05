<?php

namespace App\Livewire\Keuangan;

use App\Actions\RecordSppPaymentAction;
use App\Exceptions\DuplicatePaymentException;
use App\Models\Kelas as KelasModel;
use App\Models\Santri as SantriModel;
use App\Models\Semester;
use App\Models\Spp as SppModel;
use App\Services\LembagaService;
use App\Services\SemesterService;
use App\Traits\WithPerPage;
use Carbon\CarbonImmutable;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Pembayaran SPP')]
class Spp extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public ?int $kelasFilter = null;

    public ?string $santriId = null;

    public int $nominal = 150000;

    public string $tanggal = '';

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
        $this->authorize('viewAny', SppModel::class);

        $this->tanggal = now()->toDateString();
    }

    public function create(): void
    {
        $this->authorize('create', SppModel::class);

        $this->reset(['santriId']);
        $this->tanggal = now()->toDateString();
        $this->modal('spp-form')->show();
    }

    public function save(RecordSppPaymentAction $action, SemesterService $semesterService): void
    {
        $this->authorize('create', SppModel::class);

        $data = $this->validate([
            'santriId' => 'required|integer|exists:santris,id',
            'nominal' => 'required|integer|min:1000',
            'tanggal' => 'required|date',
        ]);

        $semester = $semesterService->current();

        if ($semester === null) {
            Flux::toast(variant: 'danger', text: __('Tidak ada semester aktif. Pengaturan semester diperlukan untuk mencatat SPP.'));

            return;
        }

        $santri = SantriModel::query()->findOrFail((int) $data['santriId']);

        try {
            $action->handle($santri, $semester, (int) $data['nominal'], CarbonImmutable::parse($data['tanggal']));
        } catch (DuplicatePaymentException $exception) {
            Flux::toast(variant: 'danger', text: $exception->getMessage());

            return;
        }

        $this->modal('spp-form')->close();
        $this->reset(['santriId']);

        Flux::toast(variant: 'success', text: __('Pembayaran SPP berhasil dicatat.'));
    }

    /**
     * @return LengthAwarePaginator<int, SppModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return SppModel::query()
            ->with(['santri.kelas', 'santri.lembaga'])
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
        return view('livewire.keuangan.spp');
    }
}
