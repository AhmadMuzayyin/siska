<?php

namespace App\Livewire\Kesantrian;

use App\Models\Kelas as KelasModel;
use App\Models\Mapel as MapelModel;
use App\Models\Nilai as NilaiModel;
use App\Models\Santri as SantriModel;
use App\Models\Semester;
use App\Services\LembagaService;
use App\Services\PredikatCalculator;
use App\Services\SemesterService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Nilai Santri')]
class Nilai extends Component
{
    public ?int $semesterId = null;

    public ?int $kelasId = null;

    public ?int $mapelId = null;

    public string $search = '';

    public function mount(SemesterService $semesterService): void
    {
        $this->authorize('viewAny', NilaiModel::class);

        $this->semesterId = $semesterService->current()?->id ?? Semester::query()->latest('id')->first()?->id;
        $this->kelasId = $this->kelasOptions->first()?->id;
        $this->mapelId = $this->mapelOptions->first()?->id;
    }

    public function setNilai(int $santriId, string $value): void
    {
        $this->authorize('create', NilaiModel::class);

        if (! $this->semesterId || ! $this->mapelId) {
            return;
        }

        $validated = filter_var($value, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => 100],
        ]);

        if ($validated === false) {
            Flux::toast(variant: 'danger', text: __('Nilai harus berupa angka 0-100.'));

            return;
        }

        NilaiModel::query()->updateOrCreate(
            [
                'santri_id' => $santriId,
                'semester_id' => $this->semesterId,
                'mapel_id' => $this->mapelId,
            ],
            ['nilai' => $validated],
        );

        Flux::toast(variant: 'success', text: __('Nilai tersimpan.'), duration: 2000);
    }

    /**
     * @return Collection<int, Semester>
     */
    #[Computed]
    public function semesterOptions(): Collection
    {
        return Semester::query()->with('tahunAkademik')->orderByDesc('id')->get();
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
     * @return Collection<int, MapelModel>
     */
    #[Computed]
    public function mapelOptions(): Collection
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return MapelModel::query()
            ->when($activeLembagaId, fn ($q) => $q->where(fn ($sub) => $sub->where('lembaga_id', $activeLembagaId)->orWhereNull('lembaga_id')))
            ->orderBy('nama')
            ->get();
    }

    /**
     * @return Collection<int, SantriModel>
     */
    #[Computed]
    public function roster(): Collection
    {
        if (! $this->kelasId || ! $this->mapelId || ! $this->semesterId) {
            return new Collection;
        }

        $mapel = MapelModel::query()->findOrFail($this->mapelId);

        $existing = NilaiModel::query()
            ->where('semester_id', $this->semesterId)
            ->where('mapel_id', $this->mapelId)
            ->get()
            ->keyBy('santri_id');

        return SantriModel::query()
            ->where('kelas_id', $this->kelasId)
            ->when($this->search !== '', function ($query) {
                $query->where('nama_lengkap', 'like', '%'.$this->search.'%');
            })
            ->orderBy('nama_lengkap')
            ->get()
            ->map(function (SantriModel $santri) use ($existing, $mapel) {
                $nilai = $existing->get($santri->id);
                $santri->currentNilai = $nilai?->nilai;
                $santri->currentPredikat = $nilai ? PredikatCalculator::calculate($nilai->nilai)->value : null;
                $santri->currentLulus = $nilai ? $nilai->nilai >= $mapel->kkm : null;

                return $santri;
            });
    }

    #[Computed]
    public function activeSemester(): ?Semester
    {
        return Semester::query()->active()->first();
    }

    public function render(): View
    {
        return view('livewire.kesantrian.nilai');
    }
}
