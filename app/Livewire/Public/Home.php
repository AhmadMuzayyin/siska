<?php

namespace App\Livewire\Public;

use App\Enums\GalleryType;
use App\Enums\GuruStatus;
use App\Enums\SantriStatus;
use App\Models\Gallery;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\Program;
use App\Models\Santri;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::public')]
#[Title('Beranda')]
class Home extends Component
{
    public string $activeGalleryType = GalleryType::Kegiatan->value;

    #[Computed]
    public function setting(): ?Setting
    {
        return Setting::query()->first();
    }

    /**
     * @return Collection<int, Lembaga>
     */
    #[Computed]
    public function lembagas(): Collection
    {
        return Lembaga::query()
            ->active()
            ->ordered()
            ->withCount(['santris' => fn ($q) => $q->where('status', SantriStatus::Aktif), 'kelas'])
            ->get();
    }

    #[Computed]
    public function totalLembagaCount(): int
    {
        return Lembaga::query()->active()->count();
    }

    /**
     * @return Collection<int, Program>
     */
    #[Computed]
    public function programs(): Collection
    {
        return Program::query()
            ->with('lembaga')
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * @return Collection<int, Gallery>
     */
    #[Computed]
    public function heroGalleries(): Collection
    {
        return Gallery::query()->latest()->limit(3)->get();
    }

    /**
     * @return array<int, GalleryType>
     */
    #[Computed]
    public function galleryTypes(): array
    {
        return GalleryType::cases();
    }

    /**
     * @return Collection<int, Gallery>
     */
    #[Computed]
    public function galleries(): Collection
    {
        return Gallery::query()
            ->where('type', $this->activeGalleryType)
            ->latest()
            ->limit(6)
            ->get();
    }

    /**
     * @return Collection<int, Guru>
     */
    #[Computed]
    public function pengajar(): Collection
    {
        return Guru::query()
            ->with('user')
            ->where('status', GuruStatus::Aktif)
            ->limit(8)
            ->get();
    }

    #[Computed]
    public function santriAktifCount(): int
    {
        return Santri::query()->where('status', SantriStatus::Aktif)->count();
    }

    #[Computed]
    public function guruAktifCount(): int
    {
        return Guru::query()->where('status', GuruStatus::Aktif)->count();
    }

    #[Computed]
    public function kelasCount(): int
    {
        return Kelas::query()->count();
    }

    public function setGalleryType(string $type): void
    {
        $this->activeGalleryType = $type;
    }

    public function render(): View
    {
        $theme = $this->setting?->landing_theme ?? 'default';

        if ($theme === 'pixigon') {
            return view('livewire.public.themes.pixigon');
        }

        return view('livewire.public.home');
    }
}
