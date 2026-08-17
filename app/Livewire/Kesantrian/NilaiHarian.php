<?php

namespace App\Livewire\Kesantrian;

use App\Enums\SantriStatus;
use App\Models\KategoriNilaiHarian;
use App\Models\Kelas;
use App\Models\NilaiHarian as NilaiHarianModel;
use App\Models\Santri;
use App\Models\Semester;
use App\Services\KalenderAkademikService;
use App\Services\LembagaService;
use App\Services\SemesterService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Input Nilai Harian Santri')]
class NilaiHarian extends Component
{
    public ?int $kelas_id = null;

    public ?int $kategori_nilai_harian_id = null;

    public ?int $semester_id = null;

    public string $tanggal = '';

    /**
     * Array of scores: [santri_id => ['nilai' => int, 'catatan' => string]]
     *
     * @var array<int, array{nilai: int, catatan: string}>
     */
    public array $scores = [];

    public function mount(): void
    {
        $this->authorize('viewAny', NilaiHarianModel::class);

        $this->tanggal = now()->format('Y-m-d');
        $this->semester_id = app(SemesterService::class)->current()?->id;

        $firstKelas = $this->kelasList->first();
        if ($firstKelas) {
            $this->kelas_id = $firstKelas->id;
        }

        $firstKategori = $this->kategoriList->first();
        if ($firstKategori) {
            $this->kategori_nilai_harian_id = $firstKategori->id;
        }

        $this->loadSantris();
    }

    public function updatedKelasId(): void
    {
        $this->loadSantris();
    }

    public function updatedKategoriNilaiHarianId(): void
    {
        $this->loadSantris();
    }

    public function updatedTanggal(): void
    {
        $this->loadSantris();
    }

    public function loadSantris(): void
    {
        $this->scores = [];

        if (! $this->kelas_id || ! $this->kategori_nilai_harian_id || ! $this->semester_id || ! $this->tanggal) {
            return;
        }

        $santris = Santri::query()
            ->where('kelas_id', $this->kelas_id)
            ->where('status', SantriStatus::Aktif)
            ->orderBy('nama_lengkap')
            ->get();

        $existingScores = NilaiHarianModel::query()
            ->where('kategori_nilai_harian_id', $this->kategori_nilai_harian_id)
            ->where('semester_id', $this->semester_id)
            ->where('tanggal', $this->tanggal)
            ->whereIn('santri_id', $santris->pluck('id'))
            ->get()
            ->keyBy('santri_id');

        foreach ($santris as $santri) {
            $existing = $existingScores->get($santri->id);
            $this->scores[$santri->id] = [
                'nilai' => $existing?->nilai ?? 80,
                'catatan' => $existing?->catatan ?? '',
            ];
        }
    }

    public function save(): void
    {
        $this->authorize('create', NilaiHarianModel::class);

        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();
        if (! app(KalenderAkademikService::class)->canInputNilai($this->tanggal, $activeLembagaId)) {
            Flux::toast(variant: 'danger', text: __('Penginputan Nilai Harian saat ini dikunci oleh Admin.'));

            return;
        }

        $this->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'kategori_nilai_harian_id' => 'required|exists:kategori_nilai_harians,id',
            'semester_id' => 'required|exists:semesters,id',
            'tanggal' => 'required|date',
            'scores' => 'required|array',
            'scores.*.nilai' => 'required|integer|min:0|max:100',
            'scores.*.catatan' => 'nullable|string|max:255',
        ], [
            'kelas_id.required' => 'Kelas wajib dipilih.',
            'kategori_nilai_harian_id.required' => 'Kategori Nilai wajib dipilih.',
            'tanggal.required' => 'Tanggal penilaian wajib diisi.',
        ]);

        DB::transaction(function () {
            foreach ($this->scores as $santriId => $data) {
                NilaiHarianModel::query()->updateOrCreate(
                    [
                        'kategori_nilai_harian_id' => $this->kategori_nilai_harian_id,
                        'santri_id' => $santriId,
                        'semester_id' => $this->semester_id,
                        'tanggal' => $this->tanggal,
                    ],
                    [
                        'nilai' => (int) $data['nilai'],
                        'catatan' => $data['catatan'] ?? null,
                        'user_id' => auth()->id(),
                    ]
                );
            }
        });

        Flux::toast(variant: 'success', text: __('Nilai Harian Santri berhasil disimpan.'));
    }

    /**
     * @return Collection<int, Kelas>
     */
    #[Computed]
    public function kelasList(): Collection
    {
        $lembagaId = app(LembagaService::class)->getActiveLembagaId();

        $kelases = Kelas::query()
            ->when($lembagaId, fn ($q) => $q->where('lembaga_id', $lembagaId))
            ->get();

        if ($kelases->count() === 1 && ! $this->kelas_id) {
            $this->kelas_id = $kelases->first()->id;
        }

        return $kelases;
    }

    /**
     * @return Collection<int, KategoriNilaiHarian>
     */
    #[Computed]
    public function kategoriList(): Collection
    {
        $kategori = KategoriNilaiHarian::query()->visibleTo()->get();

        if ($kategori->count() === 1 && ! $this->kategori_nilai_harian_id) {
            $this->kategori_nilai_harian_id = $kategori->first()->id;
        }

        return $kategori;
    }

    /**
     * @return array<int, array{value: int, label: string, sublabel: string}>
     */
    #[Computed]
    public function kategoriSearchOptions(): array
    {
        return $this->kategoriList->map(fn ($k) => [
            'value' => $k->id,
            'label' => $k->nama,
            'sublabel' => __('Bobot :bobot%', ['bobot' => $k->bobot]),
        ])->toArray();
    }

    /**
     * @return Collection<int, Semester>
     */
    #[Computed]
    public function semesterList(): Collection
    {
        return Semester::query()->with('tahunAkademik')->get();
    }

    /**
     * @return array<int, array{value: int, label: string}>
     */
    #[Computed]
    public function semesterSearchOptions(): array
    {
        return $this->semesterList->map(fn ($s) => [
            'value' => $s->id,
            'label' => ($s->tahunAkademik?->nama ?? '').' — '.ucfirst($s->tipe->value),
        ])->toArray();
    }

    /**
     * @return Collection<int, Santri>
     */
    #[Computed]
    public function santriList(): Collection
    {
        if (! $this->kelas_id) {
            return new Collection;
        }

        return Santri::query()
            ->where('kelas_id', $this->kelas_id)
            ->where('status', SantriStatus::Aktif)
            ->orderBy('nama_lengkap')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.kesantrian.nilai-harian');
    }
}
