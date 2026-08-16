<?php

namespace App\Livewire\Akademik;

use App\Actions\SemesterActivationAction;
use App\Enums\SemesterType;
use App\Models\Lembaga as LembagaModel;
use App\Models\Semester as SemesterModel;
use App\Models\TahunAkademik as TahunAkademikModel;
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

#[Title('Tahun Akademik & Semester')]
class TahunAkademik extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public ?int $editingTahunId = null;

    public ?int $lembaga_id = null;

    #[Validate('required|string|max:255')]
    public string $nama = '';

    public ?int $semesterTahunAkademikId = null;

    #[Validate('required|string|in:ganjil,genap')]
    public string $tipe = 'ganjil';

    #[Validate('required|date')]
    public string $mulai = '';

    #[Validate('required|date|after:mulai')]
    public string $selesai = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function mount(LembagaService $lembagaService): void
    {
        $this->authorize('viewAny', TahunAkademikModel::class);
        $this->lembaga_id = $lembagaService->getActiveLembagaId();
    }

    public function createTahun(LembagaService $lembagaService): void
    {
        $this->authorize('create', TahunAkademikModel::class);

        $this->reset(['editingTahunId', 'nama']);
        $this->lembaga_id = $lembagaService->getActiveLembagaId();
        $this->modal('tahun-form')->show();
    }

    public function editTahun(int $id): void
    {
        $tahun = TahunAkademikModel::query()->findOrFail($id);
        $this->authorize('update', $tahun);

        $this->editingTahunId = $tahun->id;
        $this->nama = $tahun->nama;
        $this->lembaga_id = $tahun->lembaga_id;

        $this->modal('tahun-form')->show();
    }

    public function saveTahun(): void
    {
        $data = $this->validate([
            'nama' => 'required|string|max:255',
            'lembaga_id' => 'nullable|integer|exists:lembagas,id',
        ]);

        if ($this->editingTahunId) {
            $tahun = TahunAkademikModel::query()->findOrFail($this->editingTahunId);
            $this->authorize('update', $tahun);
            $tahun->update($data);
        } else {
            $this->authorize('create', TahunAkademikModel::class);
            TahunAkademikModel::query()->create($data);
        }

        $this->modal('tahun-form')->close();
        $this->reset(['editingTahunId', 'nama']);

        Flux::toast(variant: 'success', text: __('Tahun akademik berhasil disimpan.'));
    }

    public function deleteTahun(int $id): void
    {
        $tahun = TahunAkademikModel::query()
            ->with(['semesters' => fn ($query) => $query->withCount([
                'jadwalPelajarans', 'absensis', 'absensiGurus', 'nilais', 'spps', 'gajiGurus',
            ])])
            ->findOrFail($id);
        $this->authorize('delete', $tahun);

        // Cek apakah ada semester yang sudah punya data transaksi
        $semesterDenganData = $tahun->semesters->filter(
            fn ($semester) => ($semester->jadwal_pelajarans_count
                + $semester->absensis_count
                + $semester->absensi_gurus_count
                + $semester->nilais_count
                + $semester->spps_count
                + $semester->gaji_gurus_count) > 0
        );

        if ($semesterDenganData->isNotEmpty()) {
            Flux::toast(variant: 'danger', text: __('Tahun akademik tidak bisa dihapus karena semester di dalamnya sudah memiliki data transaksi.'));

            return;
        }

        // cascadeOnDelete() di migrasi akan menghapus semua semester terkait
        $tahun->delete();

        Flux::toast(variant: 'success', text: __('Tahun akademik berhasil dihapus.'));
    }

    public function createSemester(int $tahunAkademikId): void
    {
        $this->authorize('create', SemesterModel::class);

        $this->reset(['tipe', 'mulai', 'selesai']);
        $this->tipe = 'ganjil';
        $this->semesterTahunAkademikId = $tahunAkademikId;

        $this->modal('semester-form')->show();
    }

    public function saveSemester(): void
    {
        $data = $this->validate([
            'tipe' => 'required|string|in:ganjil,genap',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after:mulai',
        ]);

        $this->authorize('create', SemesterModel::class);

        $existingCount = SemesterModel::query()
            ->where('tahun_akademik_id', $this->semesterTahunAkademikId)
            ->count();

        if ($existingCount >= 2) {
            Flux::toast(variant: 'danger', text: __('Satu tahun akademik maksimal hanya boleh memiliki 2 semester.'));

            return;
        }

        SemesterModel::query()->create([
            ...$data,
            'tahun_akademik_id' => $this->semesterTahunAkademikId,
        ]);

        $this->modal('semester-form')->close();
        $this->reset(['tipe', 'mulai', 'selesai', 'semesterTahunAkademikId']);

        Flux::toast(variant: 'success', text: __('Semester berhasil ditambahkan.'));
    }

    public function activateSemester(int $id, SemesterActivationAction $action): void
    {
        $semester = SemesterModel::query()->findOrFail($id);
        $this->authorize('update', $semester);

        $action->handle($semester);

        $this->dispatch('semester-changed');

        Flux::toast(variant: 'success', text: __('Semester berhasil diaktifkan.'));
    }

    public function deleteSemester(int $id): void
    {
        $semester = SemesterModel::query()
            ->withCount(['jadwalPelajarans', 'absensis', 'absensiGurus', 'nilais', 'spps', 'gajiGurus'])
            ->findOrFail($id);
        $this->authorize('delete', $semester);

        $inUse = $semester->jadwal_pelajarans_count
            + $semester->absensis_count
            + $semester->absensi_gurus_count
            + $semester->nilais_count
            + $semester->spps_count
            + $semester->gaji_gurus_count;

        if ($inUse > 0) {
            Flux::toast(variant: 'danger', text: __('Semester tidak bisa dihapus karena sudah memiliki data transaksi.'));

            return;
        }

        $semester->delete();

        Flux::toast(variant: 'success', text: __('Semester berhasil dihapus.'));
    }

    /**
     * @return LengthAwarePaginator<int, TahunAkademikModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return TahunAkademikModel::query()
            ->with(['lembaga', 'semesters' => fn ($query) => $query->orderBy('tipe')])
            ->when($activeLembagaId, fn ($query) => $query->where(fn ($q) => $q->where('lembaga_id', $activeLembagaId)->orWhereNull('lembaga_id')))
            ->when($this->search !== '', fn ($query) => $query->where('nama', 'like', '%'.$this->search.'%'))
            ->orderByDesc('nama')
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
     * @return array<int, SemesterType>
     */
    #[Computed]
    public function semesterTypes(): array
    {
        return SemesterType::cases();
    }

    public function render(): View
    {
        return view('livewire.akademik.tahun-akademik');
    }
}
