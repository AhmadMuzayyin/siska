<?php

namespace App\Livewire\Akademik;

use App\Models\Lembaga;
use App\Models\Mapel as MapelModel;
use App\Models\SettingRapor as SettingRaporModel;
use App\Services\ImageKitService;
use App\Services\LembagaService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Setting Rapor')]
class SettingRapor extends Component
{
    use WithFileUploads;

    public ?int $selectedMapelId = null;

    public ?int $selectedLembagaId = null;

    public string $deskripsi_a = '';

    public string $deskripsi_b = '';

    public string $deskripsi_c = '';

    public string $deskripsi_d = '';

    public $template_file = null;

    public ?string $currentTemplatePath = null;

    public function mount(): void
    {
        $this->authorize('viewAny', SettingRaporModel::class);

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

    public function uploadTemplate(ImageKitService $imageKitService): void
    {
        $this->authorize('create', SettingRaporModel::class);

        $this->validate([
            'template_file' => 'required|file|mimes:docx,html,blade.php|max:10240',
            'selectedLembagaId' => 'nullable|integer|exists:lembagas,id',
        ], [
            'template_file.mimes' => __('File template rapor hanya boleh dokumen Word (.docx) atau HTML/Blade.'),
        ]);

        $uploadResult = $imageKitService->upload($this->template_file, null, '/siska/templates/rapor', ['rapor', 'template']);
        $path = $uploadResult->url;

        DB::transaction(function () use ($path) {
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

        Flux::toast(variant: 'success', text: __('Template Rapor berhasil diunggah ke ImageKit.'));
    }

    /**
     * @return Collection<int, MapelModel>
     */
    #[Computed]
    public function mapelOptions(): Collection
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();
        $mapels = MapelModel::query()
            ->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId)->orWhereNull('lembaga_id'))
            ->orderBy('nama')
            ->get();

        if ($mapels->count() === 1 && ! $this->selectedMapelId) {
            $this->selectedMapelId = $mapels->first()->id;
        }

        return $mapels;
    }

    /**
     * @return Collection<int, Lembaga>
     */
    #[Computed]
    public function lembagaOptions(): Collection
    {
        return Lembaga::query()->where('is_active', true)->orderBy('nama')->get();
    }

    public function render(): View
    {
        return view('livewire.akademik.setting-rapor');
    }
}
