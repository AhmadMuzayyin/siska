<?php

namespace App\Livewire\Akademik;

use App\Enums\HariSekolah;
use App\Models\Guru as GuruModel;
use App\Models\JadwalPelajaran as JadwalPelajaranModel;
use App\Models\Kelas as KelasModel;
use App\Models\Mapel as MapelModel;
use App\Models\Semester as SemesterModel;
use App\Services\LembagaService;
use App\Services\SemesterService;
use App\Traits\WithPerPage;
use Carbon\Carbon;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Jadwal Pelajaran')]
class JadwalPelajaran extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public ?int $semesterFilter = null;

    public ?int $kelasFilter = null;

    public ?int $editingId = null;

    public ?int $semester_id = null;

    public ?int $kelas_id = null;

    public ?int $mapel_id = null;

    public ?int $guru_id = null;

    public string $hari = 'senin';

    public string $jam_mulai = '';

    public string $jam_selesai = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSemesterFilter(): void
    {
        $this->resetPage();
    }

    public function updatedKelasFilter(): void
    {
        $this->resetPage();
    }

    public function mount(SemesterService $semesterService): void
    {
        $this->authorize('viewAny', JadwalPelajaranModel::class);
        $this->semesterFilter = $semesterService->current()?->id ?? SemesterModel::query()->latest('id')->first()?->id;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'kelas_id', 'mapel_id', 'guru_id', 'jam_mulai', 'jam_selesai']);
        $this->hari = 'senin';
        $this->semester_id = $this->semesterFilter;
    }

    public function create(): void
    {
        $this->authorize('create', JadwalPelajaranModel::class);

        $this->resetForm();
        $this->modal('jadwal-form')->show();
    }

    public function edit(int $id): void
    {
        $jadwal = JadwalPelajaranModel::query()->findOrFail($id);
        $this->authorize('update', $jadwal);

        $this->editingId = $jadwal->id;
        $this->semester_id = $jadwal->semester_id;
        $this->kelas_id = $jadwal->kelas_id;
        $this->mapel_id = $jadwal->mapel_id;
        $this->guru_id = $jadwal->guru_id;
        $this->hari = $jadwal->hari->value;
        $this->jam_mulai = $jadwal->jam_mulai;
        $this->jam_selesai = $jadwal->jam_selesai;

        $this->modal('jadwal-form')->show();
    }

    public function save(): void
    {
        $data = $this->validate([
            'semester_id' => 'required|integer|exists:semesters,id',
            'kelas_id' => 'required|integer|exists:kelas,id',
            'mapel_id' => 'required|integer|exists:mapels,id',
            'guru_id' => 'required|integer|exists:gurus,id',
            'hari' => 'required|string|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $data['jam_mulai'] = Carbon::parse($data['jam_mulai'])->format('H:i:s');
        $data['jam_selesai'] = Carbon::parse($data['jam_selesai'])->format('H:i:s');

        // 1. Deteksi Bentrok Jadwal Guru (Multi-Lembaga)
        $guruConflict = JadwalPelajaranModel::query()
            ->with(['kelas.lembaga', 'mapel', 'guru.user'])
            ->where('semester_id', $data['semester_id'])
            ->where('guru_id', $data['guru_id'])
            ->where('hari', $data['hari'])
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai'])
            ->first();

        if ($guruConflict) {
            $guruNama = $guruConflict->guru->user->name;
            $kelasNama = $guruConflict->kelas->nama;
            $lembagaNama = $guruConflict->kelas->lembaga?->nama ?? 'Global';
            $jamExist = Carbon::parse($guruConflict->jam_mulai)->format('H:i').' - '.Carbon::parse($guruConflict->jam_selesai)->format('H:i');

            Flux::toast(
                variant: 'warning',
                text: __('⚠️ Bentrok Jadwal Guru: Ust. :guru sudah mengajar di Kelas :kelas (:lembaga) pada hari :hari pukul :jam.', [
                    'guru' => $guruNama,
                    'kelas' => $kelasNama,
                    'lembaga' => $lembagaNama,
                    'hari' => ucfirst($data['hari']),
                    'jam' => $jamExist,
                ]),
                duration: 6000
            );

            return;
        }

        // 2. Deteksi Bentrok Jadwal Kelas
        $kelasConflict = JadwalPelajaranModel::query()
            ->with(['mapel'])
            ->where('semester_id', $data['semester_id'])
            ->where('kelas_id', $data['kelas_id'])
            ->where('hari', $data['hari'])
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai'])
            ->first();

        if ($kelasConflict) {
            $mapelNama = $kelasConflict->mapel->nama;
            $jamExist = Carbon::parse($kelasConflict->jam_mulai)->format('H:i').' - '.Carbon::parse($kelasConflict->jam_selesai)->format('H:i');

            Flux::toast(
                variant: 'warning',
                text: __('⚠️ Bentrok Jadwal Kelas: Kelas ini sudah ada mata pelajaran :mapel pada hari :hari pukul :jam.', [
                    'mapel' => $mapelNama,
                    'hari' => ucfirst($data['hari']),
                    'jam' => $jamExist,
                ]),
                duration: 6000
            );

            return;
        }

        try {
            if ($this->editingId) {
                $jadwal = JadwalPelajaranModel::query()->findOrFail($this->editingId);
                $this->authorize('update', $jadwal);
                $jadwal->update($data);
            } else {
                $this->authorize('create', JadwalPelajaranModel::class);
                JadwalPelajaranModel::query()->create($data);
            }
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23000') {
                Flux::toast(variant: 'warning', text: __('⚠️ Bentrok Jadwal: kelas ini sudah memiliki mata pelajaran pada hari dan jam tersebut.'));

                return;
            }

            throw $exception;
        }

        $this->modal('jadwal-form')->close();
        $this->resetForm();

        Flux::toast(variant: 'success', text: __('Jadwal pelajaran berhasil disimpan.'));
    }

    public function delete(int $id): void
    {
        $jadwal = JadwalPelajaranModel::query()->withCount('absensis')->findOrFail($id);
        $this->authorize('delete', $jadwal);

        if ($jadwal->absensis_count > 0) {
            Flux::toast(variant: 'danger', text: __('Jadwal tidak bisa dihapus karena sudah memiliki data absensi.'));

            return;
        }

        $jadwal->delete();

        Flux::toast(variant: 'success', text: __('Jadwal pelajaran berhasil dihapus.'));
    }

    /**
     * @return LengthAwarePaginator<int, JadwalPelajaranModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return JadwalPelajaranModel::query()
            ->with(['kelas.lembaga', 'mapel', 'guru.user'])
            ->when($activeLembagaId, fn ($query) => $query->whereHas('kelas', fn ($k) => $k->where('lembaga_id', $activeLembagaId)))
            ->when($this->semesterFilter, fn ($query) => $query->where('semester_id', $this->semesterFilter))
            ->when($this->kelasFilter, fn ($query) => $query->where('kelas_id', $this->kelasFilter))
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('mapel', fn ($m) => $m->where('nama', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('guru.user', fn ($u) => $u->where('name', 'like', '%'.$this->search.'%'));
                });
            })
            ->orderByRaw("case hari
                when 'senin' then 1 when 'selasa' then 2 when 'rabu' then 3
                when 'kamis' then 4 when 'jumat' then 5 when 'sabtu' then 6
                else 7 end")
            ->orderBy('jam_mulai')
            ->paginate($this->perPage);
    }

    /**
     * @return Collection<int, SemesterModel>
     */
    #[Computed]
    public function semesterOptions(): Collection
    {
        return SemesterModel::query()->with('tahunAkademik')->orderByDesc('id')->get();
    }

    /**
     * @return Collection<int, KelasModel>
     */
    #[Computed]
    public function kelasOptions(): Collection
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return KelasModel::query()
            ->with('lembaga')
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
     * @return Collection<int, GuruModel>
     */
    #[Computed]
    public function guruOptions(): Collection
    {
        return GuruModel::query()->with('user')->get();
    }

    /**
     * @return array<int, HariSekolah>
     */
    #[Computed]
    public function hariOptions(): array
    {
        return HariSekolah::cases();
    }

    #[Computed]
    public function activeSemester(): ?SemesterModel
    {
        return SemesterModel::query()->active()->first();
    }

    public function render(): View
    {
        return view('livewire.akademik.jadwal-pelajaran');
    }
}
