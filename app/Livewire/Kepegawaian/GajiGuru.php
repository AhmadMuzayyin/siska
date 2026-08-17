<?php

namespace App\Livewire\Kepegawaian;

use App\Actions\GeneratePayrollAction;
use App\Enums\GuruStatus;
use App\Exceptions\PayrollCutoffNotReachedException;
use App\Models\GajiGuru as GajiGuruModel;
use App\Models\Guru as GuruModel;
use App\Models\Semester;
use App\Services\SemesterService;
use App\Services\SettingService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Gaji Guru')]
class GajiGuru extends Component
{
    public ?int $semesterId = null;

    public int $bulan;

    public int $tahun;

    public int $bisyaroh = 2600000;

    public string $search = '';

    public function mount(SemesterService $semesterService): void
    {
        $this->authorize('viewAny', GajiGuruModel::class);

        $this->semesterId = $semesterService->current()?->id ?? Semester::query()->latest('id')->first()?->id;
        $this->bulan = (int) now()->format('n');
        $this->tahun = (int) now()->format('Y');
    }

    public function generate(int $guruId, GeneratePayrollAction $action): void
    {
        $this->authorize('create', GajiGuruModel::class);

        if (! $this->semesterId) {
            Flux::toast(variant: 'danger', text: __('Tidak ada semester aktif.'));

            return;
        }

        $semester = Semester::query()->findOrFail($this->semesterId);
        $guru = GuruModel::query()->findOrFail($guruId);

        try {
            $action->handle($guru, $semester, $this->bulan, $this->tahun, $this->bisyaroh);
            Flux::toast(variant: 'success', text: __('Gaji berhasil dihitung untuk :nama.', ['nama' => $guru->user->name]));
        } catch (PayrollCutoffNotReachedException $exception) {
            Flux::toast(variant: 'danger', text: $exception->getMessage());
        }
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
     * @return Collection<int, GuruModel>
     */
    #[Computed]
    public function roster(): Collection
    {
        $existing = GajiGuruModel::query()
            ->where('bulan', $this->bulan)
            ->where('tahun', $this->tahun)
            ->when($this->semesterId, fn ($query) => $query->where('semester_id', $this->semesterId))
            ->get()
            ->keyBy('guru_id');

        return GuruModel::query()
            ->with('user')
            ->where('status', GuruStatus::Aktif)
            ->when($this->search !== '', function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('id')
            ->get()
            ->map(function (GuruModel $guru) use ($existing) {
                $gaji = $existing->get($guru->id);
                $guru->jumlahHadir = $gaji?->jumlah_hadir;
                $guru->totalGaji = $gaji?->total_gaji;

                return $guru;
            });
    }

    #[Computed]
    public function activeSemester(): ?Semester
    {
        return Semester::query()->active()->first();
    }

    #[Computed]
    public function payrollCutoffDay(): int
    {
        return (int) (app(SettingService::class)->get()->payroll_cutoff_day ?: 25);
    }

    public function render(): View
    {
        return view('livewire.kepegawaian.gaji-guru');
    }
}
