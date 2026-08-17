<?php

namespace App\Livewire\Keuangan;

use App\Models\Kelas as KelasModel;
use App\Models\Santri as SantriModel;
use App\Models\Tabungan as TabunganModel;
use App\Services\LembagaService;
use App\Traits\WithPerPage;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Tabungan Santri')]
class Tabungan extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public ?int $kelasFilter = null;

    public ?int $deletingId = null;

    public ?string $santriId = null;

    public string $tipe = 'setor';

    public int $nominal = 50000;

    public string $tanggal = '';

    public string $keterangan = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedKelasFilter(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->authorize('viewAny', TabunganModel::class);
        $this->tanggal = now()->toDateString();
    }

    public function create(): void
    {
        $this->authorize('create', TabunganModel::class);
        $this->reset(['santriId', 'keterangan']);
        $this->tipe = 'setor';
        $this->tanggal = now()->toDateString();
        $this->modal('tabungan-form')->show();
    }

    public function save(): void
    {
        $this->authorize('create', TabunganModel::class);

        $data = $this->validate([
            'santriId' => 'required|integer|exists:santris,id',
            'tipe' => 'required|string|in:setor,tarik',
            'nominal' => 'required|integer|min:1000',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $santri = SantriModel::query()->findOrFail((int) $data['santriId']);

        DB::transaction(function () use ($data, $santri): void {
            // Get current last balance
            $lastBalance = TabunganModel::query()
                ->where('santri_id', $santri->id)
                ->latest('id')
                ->value('saldo_akhir') ?? 0;

            if ($data['tipe'] === 'tarik' && $data['nominal'] > $lastBalance) {
                throw new \InvalidArgumentException(__('Saldo tabungan santri tidak mencukupi untuk penarikan ini. Saldo saat ini: Rp').number_format($lastBalance, 0, ',', '.'));
            }

            $newBalance = $data['tipe'] === 'setor'
                ? $lastBalance + $data['nominal']
                : $lastBalance - $data['nominal'];

            TabunganModel::query()->create([
                'santri_id' => $santri->id,
                'tipe' => $data['tipe'],
                'nominal' => $data['nominal'],
                'saldo_akhir' => $newBalance,
                'tanggal' => $data['tanggal'],
                'keterangan' => $data['keterangan'] ?? null,
                'user_id' => auth()->id(),
            ]);
        });

        $this->modal('tabungan-form')->close();
        $this->reset(['santriId', 'keterangan']);

        Flux::toast(variant: 'success', text: __('Transaksi tabungan berhasil dicatat.'));
    }

    public function delete(?int $id = null): void
    {
        $targetId = $id ?? $this->deletingId;
        if (! $targetId) {
            return;
        }

        $item = TabunganModel::query()->findOrFail($targetId);
        $this->authorize('delete', $item);
        $item->delete();
        $this->deletingId = null;

        Flux::toast(variant: 'success', text: __('Transaksi tabungan berhasil dihapus.'));
    }

    /**
     * @return LengthAwarePaginator<int, TabunganModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return TabunganModel::query()
            ->with(['santri.kelas', 'santri.lembaga', 'user'])
            ->when($activeLembagaId, fn ($query) => $query->whereHas('santri', fn ($s) => $s->where('lembaga_id', $activeLembagaId)))
            ->when($this->kelasFilter, fn ($query) => $query->whereHas('santri', fn ($q) => $q->where('kelas_id', $this->kelasFilter)))
            ->when($this->search !== '', function ($query) {
                $query->whereHas('santri', function ($q) {
                    $q->where('nama_lengkap', 'like', '%'.$this->search.'%')
                        ->orWhere('noinduk', 'like', '%'.$this->search.'%');
                });
            })
            ->orderByDesc('id')
            ->paginate($this->perPage);
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
     * @return Collection<int, SantriModel>
     */
    #[Computed]
    public function santriOptions(): Collection
    {
        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return SantriModel::query()
            ->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId))
            ->orderBy('nama_lengkap')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.keuangan.tabungan');
    }
}
