<?php

namespace App\Livewire\Akademik;

use App\Models\Kelas as KelasModel;
use App\Models\Lembaga;
use App\Models\Mapel as MapelModel;
use App\Models\Nilai as NilaiModel;
use App\Models\Santri as SantriModel;
use App\Models\Semester;
use App\Models\SettingRapor as SettingRaporModel;
use App\Services\LembagaService;
use App\Services\SemesterService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Cetak Rapor')]
class Rapor extends Component
{
    use WithFileUploads;

    public ?int $semesterId = null;

    public ?int $kelasFilter = null;

    public string $statusFilter = 'semua'; // semua, lengkap, belum_lengkap

    public string $search = '';

    // Settings popover properties
    public ?int $selectedLembagaId = null;

    public ?int $selectedMapelId = null;

    public string $deskripsi_a = '';

    public string $deskripsi_b = '';

    public string $deskripsi_c = '';

    public string $deskripsi_d = '';

    public $template_file = null;

    public ?string $currentTemplatePath = null;

    public function mount(SemesterService $semesterService): void
    {
        $this->authorize('viewAny', SettingRaporModel::class);

        $this->semesterId = $semesterService->current()?->id ?? Semester::query()->latest('id')->first()?->id;

        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();
        if ($activeLembagaId) {
            $this->selectedLembagaId = $activeLembagaId;
        }

        $firstMapel = $this->mapelOptions->first();
        if ($firstMapel) {
            $this->selectedMapelId = $firstMapel->id;
            $this->loadSettings();
        }

        $this->loadTemplatePath();
    }

    public function updatedSelectedLembagaId(): void
    {
        $this->loadTemplatePath();
    }

    public function loadTemplatePath(): void
    {
        $setting = SettingRaporModel::query()
            ->where('lembaga_id', $this->selectedLembagaId)
            ->whereNull('mapel_id')
            ->first();

        if (! $setting && $this->selectedLembagaId) {
            $setting = SettingRaporModel::query()
                ->whereNull('lembaga_id')
                ->whereNull('mapel_id')
                ->first();
        }

        $this->currentTemplatePath = $setting?->template_path;
    }

    public function updatedSelectedMapelId(): void
    {
        $this->loadSettings();
    }

    public function loadSettings(): void
    {
        if (! $this->selectedMapelId) {
            return;
        }

        $setting = SettingRaporModel::query()->where('mapel_id', $this->selectedMapelId)->first();
        if ($setting) {
            $this->deskripsi_a = $setting->deskripsi_a ?? '';
            $this->deskripsi_b = $setting->deskripsi_b ?? '';
            $this->deskripsi_c = $setting->deskripsi_c ?? '';
            $this->deskripsi_d = $setting->deskripsi_d ?? '';
        } else {
            $this->reset(['deskripsi_a', 'deskripsi_b', 'deskripsi_c', 'deskripsi_d']);
        }
    }

    public function saveDeskripsi(): void
    {
        $this->authorize('create', SettingRaporModel::class);

        $this->validate([
            'selectedMapelId' => 'required|integer|exists:mapels,id',
            'deskripsi_a' => 'nullable|string',
            'deskripsi_b' => 'nullable|string',
            'deskripsi_c' => 'nullable|string',
            'deskripsi_d' => 'nullable|string',
        ]);

        DB::transaction(function () {
            SettingRaporModel::query()->updateOrCreate(
                [
                    'mapel_id' => $this->selectedMapelId,
                    'lembaga_id' => $this->selectedLembagaId,
                ],
                [
                    'deskripsi_a' => $this->deskripsi_a,
                    'deskripsi_b' => $this->deskripsi_b,
                    'deskripsi_c' => $this->deskripsi_c,
                    'deskripsi_d' => $this->deskripsi_d,
                ]
            );
        });

        Flux::toast(variant: 'success', text: __('Deskripsi nilai mata pelajaran berhasil disimpan.'));
    }

    public function uploadTemplate(): void
    {
        $this->authorize('create', SettingRaporModel::class);

        $this->validate([
            'template_file' => 'required|file|max:10240',
            'selectedLembagaId' => 'nullable|integer|exists:lembagas,id',
        ]);

        $ext = strtolower($this->template_file->getClientOriginalExtension());
        if (! in_array($ext, ['docx', 'xml', 'html', 'htm', 'blade', 'php', 'txt'], true)) {
            $this->addError('template_file', __('File template harus berformat Word (.docx), Word XML (.xml), atau HTML (.html).'));

            return;
        }

        DB::transaction(function () {
            $path = $this->template_file->store('templates/rapor', 'public');

            SettingRaporModel::query()->updateOrCreate(
                [
                    'mapel_id' => null,
                    'lembaga_id' => $this->selectedLembagaId,
                ],
                ['template_path' => $path]
            );

            $this->currentTemplatePath = $path;
            $this->reset('template_file');
        });

        Flux::toast(variant: 'success', text: __('Template Rapor berhasil diunggah.'));
    }

    public function deleteTemplate(): void
    {
        $setting = SettingRaporModel::query()
            ->where('lembaga_id', $this->selectedLembagaId)
            ->whereNull('mapel_id')
            ->whereNotNull('template_path')
            ->first();

        if (! $setting && $this->selectedLembagaId) {
            $setting = SettingRaporModel::query()
                ->whereNull('lembaga_id')
                ->whereNull('mapel_id')
                ->whereNotNull('template_path')
                ->first();
        }

        if ($setting) {
            $this->authorize('delete', $setting);

            if ($setting->template_path && Storage::disk('public')->exists($setting->template_path)) {
                Storage::disk('public')->delete($setting->template_path);
            }

            $setting->update(['template_path' => null]);
            $this->currentTemplatePath = null;

            Flux::toast(variant: 'success', text: __('Template rapor berhasil dihapus.'));
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
     * @return array<int, array{value: int, label: string}>
     */
    #[Computed]
    public function semesterSearchOptions(): array
    {
        return $this->semesterOptions->map(fn ($s) => [
            'value' => $s->id,
            'label' => $s->tahunAkademik->nama.' — '.ucfirst($s->tipe->value),
        ])->toArray();
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
        return [
            ['value' => 'semua', 'label' => __('Semua Status')],
            ['value' => 'lengkap', 'label' => __('Nilai Lengkap')],
            ['value' => 'belum_lengkap', 'label' => __('Belum Lengkap')],
        ];
    }

    /**
     * @return Collection<int, MapelModel>
     */
    #[Computed]
    public function mapelOptions(): Collection
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return MapelModel::query()
            ->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId)->orWhereNull('lembaga_id'))
            ->orderBy('nama')
            ->get();
    }

    /**
     * @return Collection<int, Lembaga>
     */
    #[Computed]
    public function lembagaOptions(): Collection
    {
        return Lembaga::query()->where('is_active', true)->orderBy('nama')->get();
    }

    /**
     * Get list of santris with calculated grade completeness
     */
    #[Computed]
    public function santriRoster()
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        $santris = SantriModel::query()
            ->with(['kelas', 'lembaga'])
            ->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId))
            ->when($this->kelasFilter, fn ($q) => $q->where('kelas_id', $this->kelasFilter))
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_lengkap', 'like', '%'.$this->search.'%')
                        ->orWhere('noinduk', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('nama_lengkap')
            ->get();

        $mapelCounts = MapelModel::query()
            ->selectRaw('lembaga_id, COUNT(*) as total')
            ->groupBy('lembaga_id')
            ->pluck('total', 'lembaga_id');

        $totalGlobalMapel = MapelModel::query()->count();

        return $santris->map(function (SantriModel $santri) use ($mapelCounts, $totalGlobalMapel) {
            $totalMapel = $mapelCounts->get($santri->lembaga_id) ?? $totalGlobalMapel;

            $inputtedCount = NilaiModel::query()
                ->where('santri_id', $santri->id)
                ->when($this->semesterId, fn ($q) => $q->where('semester_id', $this->semesterId))
                ->count();

            $santri->totalMapel = $totalMapel;
            $santri->inputtedCount = $inputtedCount;
            $santri->isLengkap = ($totalMapel > 0 && $inputtedCount >= $totalMapel);

            return $santri;
        })->filter(function (SantriModel $santri) {
            if ($this->statusFilter === 'lengkap') {
                return $santri->isLengkap;
            }
            if ($this->statusFilter === 'belum_lengkap') {
                return ! $santri->isLengkap;
            }

            return true;
        });
    }

    public function render(): View
    {
        return view('livewire.akademik.rapor');
    }
}
