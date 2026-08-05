<?php

namespace App\Livewire\Kepegawaian;

use App\Actions\RecordTeacherAttendanceAction;
use App\Enums\GuruStatus;
use App\Enums\TeacherAttendanceStatus;
use App\Exceptions\DuplicateAttendanceException;
use App\Models\AbsensiGuru as AbsensiGuruModel;
use App\Models\Guru as GuruModel;
use App\Models\Semester;
use App\Services\SemesterService;
use Carbon\CarbonImmutable;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Absensi Guru')]
class AbsensiGuru extends Component
{
    public ?int $semesterId = null;

    public string $tanggal = '';

    public string $search = '';

    public function mount(SemesterService $semesterService): void
    {
        $this->authorize('viewAny', AbsensiGuruModel::class);

        $this->semesterId = $semesterService->current()?->id ?? Semester::query()->latest('id')->first()?->id;
        $this->tanggal = now()->toDateString();
    }

    public function setStatus(int $guruId, string $status, RecordTeacherAttendanceAction $action): void
    {
        $this->authorize('create', AbsensiGuruModel::class);

        if (! $this->semesterId) {
            Flux::toast(variant: 'warning', text: __('Tidak ada semester aktif.'));

            return;
        }

        $semester = Semester::query()->findOrFail($this->semesterId);

        try {
            $action->handle(
                $semester,
                [['guru_id' => $guruId, 'status' => TeacherAttendanceStatus::from($status)]],
                CarbonImmutable::parse($this->tanggal),
            );

            Flux::toast(variant: 'success', text: __('Absensi tersimpan.'), duration: 2000);
        } catch (DuplicateAttendanceException $exception) {
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
        $existing = AbsensiGuruModel::query()
            ->whereDate('tanggal', $this->tanggal)
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
                $record = $existing->get($guru->id);
                $guru->recordedStatus = $record?->status->value;

                return $guru;
            });
    }

    /**
     * @return array<int, TeacherAttendanceStatus>
     */
    #[Computed]
    public function statuses(): array
    {
        return TeacherAttendanceStatus::cases();
    }

    #[Computed]
    public function activeSemester(): ?Semester
    {
        return Semester::query()->active()->first();
    }

    public function render(): View
    {
        return view('livewire.kepegawaian.absensi-guru');
    }
}
