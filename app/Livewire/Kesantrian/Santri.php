<?php

namespace App\Livewire\Kesantrian;

use App\Actions\ApproveSantriRegistrationAction;
use App\Actions\AutomaticClassPromotionAction;
use App\Actions\EnrollSantriAction;
use App\Actions\PromoteSantriAction;
use App\Enums\Gender;
use App\Enums\SantriStatus;
use App\Exceptions\KelasPenuhException;
use App\Models\Kelas as KelasModel;
use App\Models\Lembaga as LembagaModel;
use App\Models\Santri as SantriModel;
use App\Services\LembagaService;
use App\Traits\WithPerPage;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Data Santri')]
class Santri extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public string $statusFilter = '';

    public ?int $kelasFilter = null;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public ?int $lembaga_id = null;

    public ?int $kelas_id = null;

    public string $noinduk = '';

    public string $rfid_uid = '';

    public string $nama_lengkap = '';

    public string $nama_panggilan = '';

    public string $tempat_lahir = '';

    public string $tanggal_lahir = '';

    public int $anak_ke = 1;

    public string $alamat = '';

    public string $jenis_kelamin = '';

    public string $nama_ayah = '';

    public string $pendidikan_ayah = '';

    public string $pekerjaan_ayah = '';

    public string $nama_ibu = '';

    public string $pendidikan_ibu = '';

    public string $pekerjaan_ibu = '';

    public string $telepon_wali = '';

    public string $status = 'aktif';

    /** @var array<int, int> */
    public array $selected = [];

    public ?int $promoteKelasId = null;

    public function mount(LembagaService $lembagaService): void
    {
        $this->authorize('viewAny', SantriModel::class);
        $this->lembaga_id = $lembagaService->getActiveLembagaId() ?? LembagaModel::query()->active()->first()?->id;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
        $this->selected = [];
    }

    public function updatingKelasFilter(): void
    {
        $this->resetPage();
        $this->selected = [];
    }

    private function resetForm(LembagaService $lembagaService): void
    {
        $this->reset([
            'editingId', 'kelas_id', 'noinduk', 'rfid_uid', 'nama_lengkap', 'nama_panggilan',
            'tempat_lahir', 'tanggal_lahir', 'alamat', 'jenis_kelamin',
            'nama_ayah', 'pendidikan_ayah', 'pekerjaan_ayah',
            'nama_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'telepon_wali',
        ]);
        $this->lembaga_id = $lembagaService->getActiveLembagaId() ?? LembagaModel::query()->active()->first()?->id;
        $this->anak_ke = 1;
        $this->status = 'aktif';
    }

    public function create(LembagaService $lembagaService): void
    {
        $this->authorize('create', SantriModel::class);

        $this->resetForm($lembagaService);
        $this->modal('santri-form')->show();
    }

    public function edit(int $id): void
    {
        $santri = SantriModel::query()->findOrFail($id);
        $this->authorize('update', $santri);

        $this->editingId = $santri->id;
        $this->lembaga_id = $santri->lembaga_id;
        $this->kelas_id = $santri->kelas_id;
        $this->noinduk = $santri->noinduk;
        $this->rfid_uid = (string) $santri->rfid_uid;
        $this->nama_lengkap = $santri->nama_lengkap;
        $this->nama_panggilan = $santri->nama_panggilan;
        $this->tempat_lahir = $santri->tempat_lahir;
        $this->tanggal_lahir = $santri->tanggal_lahir->toDateString();
        $this->anak_ke = $santri->anak_ke;
        $this->alamat = $santri->alamat;
        $this->jenis_kelamin = $santri->jenis_kelamin->value;
        $this->nama_ayah = $santri->nama_ayah;
        $this->pendidikan_ayah = $santri->pendidikan_ayah;
        $this->pekerjaan_ayah = $santri->pekerjaan_ayah;
        $this->nama_ibu = $santri->nama_ibu;
        $this->pendidikan_ibu = $santri->pendidikan_ibu;
        $this->pekerjaan_ibu = $santri->pekerjaan_ibu;
        $this->telepon_wali = $santri->telepon_wali;
        $this->status = $santri->status->value;

        $this->modal('santri-form')->show();
    }

    public function save(EnrollSantriAction $enrollSantriAction, LembagaService $lembagaService): void
    {
        $editing = $this->editingId ? SantriModel::query()->find($this->editingId) : null;

        $data = $this->validate(SantriModel::validationRules($editing));

        if (empty($data['lembaga_id']) && $this->kelas_id) {
            $data['lembaga_id'] = KelasModel::query()->find($this->kelas_id)?->lembaga_id;
        }

        if ($editing) {
            $this->authorize('update', $editing);
            $editing->update($data);
        } else {
            $this->authorize('create', SantriModel::class);

            try {
                $enrollSantriAction->handle($data);
            } catch (KelasPenuhException $exception) {
                Flux::toast(variant: 'danger', text: $exception->getMessage());

                return;
            }
        }

        $this->modal('santri-form')->close();
        $this->resetForm($lembagaService);

        Flux::toast(variant: 'success', text: __('Data santri berhasil disimpan.'));
    }

    public function approve(int $id, ApproveSantriRegistrationAction $action): void
    {
        $santri = SantriModel::query()->findOrFail($id);
        $this->authorize('update', $santri);

        try {
            $action->handle($santri);
            Flux::toast(variant: 'success', text: __('Pendaftaran santri disetujui.'));
        } catch (KelasPenuhException $exception) {
            Flux::toast(variant: 'danger', text: $exception->getMessage());
        }
    }

    public function delete(?int $id = null): void
    {
        $targetId = $id ?? $this->deletingId;
        if (! $targetId) {
            return;
        }

        $santri = SantriModel::query()->withCount(['absensis', 'spps', 'nilais'])->findOrFail($targetId);
        $this->authorize('delete', $santri);

        if ($santri->absensis_count + $santri->spps_count + $santri->nilais_count > 0) {
            Flux::toast(variant: 'danger', text: __('Santri tidak bisa dihapus karena sudah memiliki riwayat akademik. Ubah status santri sebagai gantinya.'));

            return;
        }

        $santri->delete();
        $this->deletingId = null;

        Flux::toast(variant: 'success', text: __('Data santri berhasil dihapus.'));
    }

    public function promote(PromoteSantriAction $action): void
    {
        $this->authorize('create', SantriModel::class);

        if (empty($this->selected) || ! $this->promoteKelasId) {
            Flux::toast(variant: 'warning', text: __('Pilih santri dan kelas tujuan terlebih dahulu.'));

            return;
        }

        $kelasTujuan = KelasModel::query()->findOrFail($this->promoteKelasId);
        $santris = SantriModel::query()->whereIn('id', $this->selected)->get();

        $action->handle($santris, $kelasTujuan);

        $this->selected = [];
        $this->promoteKelasId = null;

        Flux::toast(variant: 'success', text: __(':count santri berhasil dipromosikan ke kelas :kelas.', ['count' => $santris->count(), 'kelas' => $kelasTujuan->nama]));
    }

    public function processAutomaticKenaikanKelas(AutomaticClassPromotionAction $action): void
    {
        $this->authorize('create', SantriModel::class);

        $res = $action->handle();

        Flux::toast(
            variant: 'success',
            text: __('Proses kenaikan kelas selesai: :promoted santri naik kelas, :graduated santri lulus/alumni, :retained santri tinggal kelas.', [
                'promoted' => $res['promoted'],
                'graduated' => $res['graduated'],
                'retained' => $res['retained'],
            ])
        );
    }

    /**
     * @return LengthAwarePaginator<int, SantriModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return SantriModel::query()
            ->with(['kelas.lembaga', 'lembaga'])
            ->when($activeLembagaId, fn ($query) => $query->where('lembaga_id', $activeLembagaId))
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', "%{$this->search}%")
                        ->orWhere('noinduk', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter, fn ($query) => $query->where('status', $this->statusFilter))
            ->when($this->kelasFilter, fn ($query) => $query->where('kelas_id', $this->kelasFilter))
            ->orderBy('nama_lengkap')
            ->paginate($this->perPage);
    }

    /**
     * @return Collection<int, LembagaModel>
     */
    #[Computed]
    public function lembagaOptions(): Collection
    {
        $lembagas = LembagaModel::query()->active()->ordered()->get();

        if ($lembagas->count() === 1 && ! $this->lembaga_id) {
            $this->lembaga_id = $lembagas->first()->id;
        }

        return $lembagas;
    }

    /**
     * @return Collection<int, KelasModel>
     */
    #[Computed]
    public function kelasOptions(): Collection
    {
        $kelases = KelasModel::query()
            ->when($this->lembaga_id, fn ($q) => $q->where('lembaga_id', $this->lembaga_id))
            ->orderBy('nama')
            ->get();

        if ($kelases->count() === 1 && ! $this->kelas_id) {
            $this->kelas_id = $kelases->first()->id;
        }

        return $kelases;
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    #[Computed]
    public function kelasFilterOptions(): array
    {
        $options = [['value' => '', 'label' => __('Semua Kelas')]];
        foreach ($this->kelasOptions as $k) {
            $options[] = ['value' => (string) $k->id, 'label' => $k->nama];
        }

        return $options;
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    #[Computed]
    public function statusFilterOptions(): array
    {
        $options = [['value' => '', 'label' => __('Semua Status')]];
        foreach ($this->statuses as $s) {
            $options[] = ['value' => $s->value, 'label' => ucfirst(str_replace('_', ' ', $s->value))];
        }

        return $options;
    }

    /**
     * @return array<int, Gender>
     */
    #[Computed]
    public function genders(): array
    {
        return Gender::cases();
    }

    /**
     * @return array<int, SantriStatus>
     */
    #[Computed]
    public function statuses(): array
    {
        return SantriStatus::cases();
    }

    public function render(): View
    {
        return view('livewire.kesantrian.santri');
    }
}
