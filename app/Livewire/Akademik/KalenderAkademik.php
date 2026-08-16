<?php

namespace App\Livewire\Akademik;

use App\Models\KalenderAkademik as KalenderModel;
use App\Models\Lembaga;
use App\Models\Semester;
use App\Services\LembagaService;
use App\Services\SemesterService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Kalender Akademik / Pendidikan')]
class KalenderAkademik extends Component
{
    public int $currentYear;

    public ?int $editingId = null;

    public string $judul = '';

    public string $tipe = 'kegiatan';

    public string $mulai = '';

    public ?string $selesai = null;

    public string $warna = '#10b981';

    public string $ikon = 'calendar';

    public string $deskripsi = '';

    public ?int $lembaga_id = null;

    public function mount(): void
    {
        $this->authorize('viewAny', KalenderModel::class);

        $activeSemester = app(SemesterService::class)->current();

        if ($activeSemester && $activeSemester->mulai) {
            $this->currentYear = (int) $activeSemester->mulai->format('Y');
        } else {
            $this->currentYear = (int) date('Y');
        }

        $this->mulai = now()->format('Y-m-d');
        $this->lembaga_id = app(LembagaService::class)->getActiveLembagaId();
    }

    public function openDrawer(?string $date = null): void
    {
        $this->authorize('create', KalenderModel::class);

        $this->reset(['editingId', 'judul', 'deskripsi', 'selesai']);
        $this->tipe = 'kegiatan';
        $this->warna = '#10b981';
        $this->ikon = 'calendar';
        $this->mulai = $date ?? now()->format('Y-m-d');
        $this->lembaga_id = app(LembagaService::class)->getActiveLembagaId();

        $this->modal('calendar-drawer')->show();
    }

    public function selectDate(string $date): void
    {
        if (empty($this->mulai) || ($this->mulai && $this->selesai)) {
            $this->mulai = $date;
            $this->selesai = null;
        } elseif ($this->mulai && empty($this->selesai)) {
            if ($date >= $this->mulai) {
                $this->selesai = $date;
            } else {
                $this->selesai = $this->mulai;
                $this->mulai = $date;
            }
        }
    }

    public function edit(int $id): void
    {
        $event = KalenderModel::query()->visibleTo()->findOrFail($id);
        $this->authorize('update', $event);

        $this->editingId = $event->id;
        $this->judul = $event->judul;
        $this->tipe = $event->tipe;
        $this->mulai = $event->mulai?->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->selesai = $event->selesai?->format('Y-m-d');
        $this->warna = $event->warna ?? '#10b981';
        $this->ikon = $event->ikon ?? 'calendar';
        $this->deskripsi = $event->deskripsi ?? '';
        $this->lembaga_id = $event->lembaga_id;

        $this->modal('calendar-drawer')->show();
    }

    public function save(): void
    {
        $activeSemester = app(SemesterService::class)->current();

        if (! $activeSemester) {
            Flux::toast(variant: 'danger', text: __('Tidak dapat menyimpan agenda. Harap aktifkan semester terlebih dahulu.'));

            return;
        }

        $this->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|string|max:50',
            'mulai' => 'required|date',
            'selesai' => 'nullable|date|after_or_equal:mulai',
            'warna' => 'required|string|max:20',
            'ikon' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'lembaga_id' => 'nullable|exists:lembagas,id',
        ], [
            'judul.required' => 'Judul agenda wajib diisi.',
            'mulai.required' => 'Tanggal mulai wajib diisi.',
            'selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);

        $lembagaId = $this->lembaga_id ?? app(LembagaService::class)->getActiveLembagaId();

        if ($this->editingId) {
            $event = KalenderModel::query()->visibleTo()->findOrFail($this->editingId);
            $this->authorize('update', $event);

            $event->update([
                'semester_id' => $activeSemester->id,
                'lembaga_id' => $lembagaId,
                'judul' => $this->judul,
                'tipe' => $this->tipe,
                'mulai' => $this->mulai,
                'selesai' => $this->selesai ?: null,
                'warna' => $this->warna,
                'ikon' => $this->ikon,
                'deskripsi' => $this->deskripsi,
            ]);
        } else {
            $this->authorize('create', KalenderModel::class);

            KalenderModel::query()->create([
                'semester_id' => $activeSemester->id,
                'lembaga_id' => $lembagaId,
                'judul' => $this->judul,
                'tipe' => $this->tipe,
                'mulai' => $this->mulai,
                'selesai' => $this->selesai ?: null,
                'warna' => $this->warna,
                'ikon' => $this->ikon,
                'deskripsi' => $this->deskripsi,
                'created_by' => auth()->id(),
            ]);
        }

        $this->modal('calendar-drawer')->close();
        $this->reset(['editingId', 'judul', 'deskripsi', 'selesai']);

        Flux::toast(variant: 'success', text: __('Agenda kalender akademik berhasil disimpan.'));
    }

    public function delete(int $id): void
    {
        $event = KalenderModel::query()->visibleTo()->findOrFail($id);
        $this->authorize('delete', $event);

        $event->delete();

        Flux::toast(variant: 'success', text: __('Agenda kalender akademik berhasil dihapus.'));
    }

    public function previousYear(): void
    {
        $this->currentYear--;
    }

    public function nextYear(): void
    {
        $this->currentYear++;
    }

    public function today(): void
    {
        $this->currentYear = (int) date('Y');
    }

    #[Computed]
    public function activeSemester(): ?Semester
    {
        return app(SemesterService::class)->current();
    }

    /**
     * @return Collection<int, KalenderModel>
     */
    #[Computed]
    public function eventsList(): Collection
    {
        $activeSem = $this->activeSemester;

        if (! $activeSem) {
            return new Collection;
        }

        return KalenderModel::query()
            ->visibleTo()
            ->where('semester_id', $activeSem->id)
            ->orderBy('mulai', 'asc')
            ->get();
    }

    /**
     * @return Collection<int, Lembaga>
     */
    #[Computed]
    public function lembagas(): Collection
    {
        return Lembaga::query()->active()->ordered()->get();
    }

    public function render(): View
    {
        return view('livewire.akademik.kalender-akademik');
    }
}
