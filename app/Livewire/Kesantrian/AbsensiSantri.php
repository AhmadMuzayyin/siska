<?php

namespace App\Livewire\Kesantrian;

use App\Actions\RecordAttendanceAction;
use App\Enums\StudentAttendanceStatus;
use App\Exceptions\OutsideAttendanceWindowException;
use App\Models\Absensi as AbsensiModel;
use App\Models\JadwalPelajaran as JadwalPelajaranModel;
use App\Models\Santri as SantriModel;
use App\Models\Semester;
use App\Services\LembagaService;
use App\Services\SemesterService;
use Carbon\CarbonImmutable;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Absensi Santri')]
class AbsensiSantri extends Component
{
    public ?int $semesterFilter = null;

    public ?int $jadwalId = null;

    public string $tanggal = '';

    public string $search = '';

    public function mount(SemesterService $semesterService): void
    {
        $this->authorize('viewAny', AbsensiModel::class);

        $this->semesterFilter = $semesterService->current()?->id;
        $this->tanggal = now()->toDateString();
        $this->jadwalId = $this->jadwalOptions->first()?->id;
    }

    public function updatedSemesterFilter(): void
    {
        $this->jadwalId = $this->jadwalOptions->first()?->id;
    }

    public function setStatus(int $santriId, string $status, RecordAttendanceAction $action): void
    {
        $this->authorize('create', AbsensiModel::class);

        if (! $this->jadwalId) {
            return;
        }

        $jadwal = JadwalPelajaranModel::query()->findOrFail($this->jadwalId);
        $santri = SantriModel::query()->findOrFail($santriId);

        $at = CarbonImmutable::parse($this->tanggal.' '.$jadwal->jam_mulai);

        try {
            $action->handle($jadwal, $santri, StudentAttendanceStatus::from($status), $at);
            Flux::toast(variant: 'success', text: __('Absensi :nama tersimpan.', ['nama' => $santri->nama_lengkap]), duration: 2000);
        } catch (OutsideAttendanceWindowException $exception) {
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
     * @return Collection<int, JadwalPelajaranModel>
     */
    #[Computed]
    public function jadwalOptions(): Collection
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return JadwalPelajaranModel::query()
            ->with(['kelas', 'mapel'])
            ->when($activeLembagaId, fn ($query) => $query->whereHas('kelas', fn ($k) => $k->where('lembaga_id', $activeLembagaId)))
            ->when($this->semesterFilter, fn ($query) => $query->where('semester_id', $this->semesterFilter))
            ->orderBy('kelas_id')
            ->get();
    }

    /**
     * @return array<int, array{value: int, label: string, sublabel: string}>
     */
    #[Computed]
    public function jadwalSearchOptions(): array
    {
        return $this->jadwalOptions->map(function ($j) {
            return [
                'value' => $j->id,
                'label' => $j->kelas->nama.' — '.$j->mapel->nama,
                'sublabel' => ucfirst($j->hari->value).' ('.substr($j->jam_mulai, 0, 5).' - '.substr($j->jam_selesai, 0, 5).')',
            ];
        })->toArray();
    }

    /**
     * @return Collection<int, SantriModel>
     */
    #[Computed]
    public function roster(): Collection
    {
        if (! $this->jadwalId) {
            return new Collection;
        }

        $jadwal = JadwalPelajaranModel::query()->findOrFail($this->jadwalId);

        $existing = AbsensiModel::query()
            ->where('jadwal_pelajaran_id', $jadwal->id)
            ->whereDate('tanggal', $this->tanggal)
            ->get()
            ->keyBy('santri_id');

        return SantriModel::query()
            ->where('kelas_id', $jadwal->kelas_id)
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%'.$this->search.'%')
                        ->orWhere('noinduk', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('nama_lengkap')
            ->get()
            ->map(function (SantriModel $santri) use ($existing) {
                $santri->currentStatus = $existing->get($santri->id)?->status->value ?? 'hadir';

                return $santri;
            });
    }

    /**
     * @return array<int, StudentAttendanceStatus>
     */
    #[Computed]
    public function statuses(): array
    {
        return StudentAttendanceStatus::cases();
    }

    #[Computed]
    public function activeSemester(): ?Semester
    {
        return Semester::query()->active()->first();
    }

    public function render(): View
    {
        return view('livewire.kesantrian.absensi-santri');
    }
}
