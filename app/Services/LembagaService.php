<?php

namespace App\Services;

use App\Models\Lembaga;
use Illuminate\Database\Eloquent\Collection;

class LembagaService
{
    /**
     * Get all active lembagas.
     *
     * @return Collection<int, Lembaga>
     */
    public function getActiveLembagas(): Collection
    {
        return Lembaga::query()->active()->ordered()->get();
    }

    /**
     * Get currently selected active lembaga ID from session (or null for "Semua Lembaga").
     */
    public function getActiveLembagaId(): ?int
    {
        $sessionValue = session('active_lembaga_id');

        if ($sessionValue === 'all' || $sessionValue === null) {
            return null;
        }

        return (int) $sessionValue;
    }

    /**
     * Set currently selected active lembaga ID in session.
     */
    public function setActiveLembagaId(?int $id): void
    {
        if ($id === null) {
            session(['active_lembaga_id' => 'all']);
        } else {
            session(['active_lembaga_id' => $id]);
        }
    }

    /**
     * Get current selected Lembaga model instance (if any).
     */
    public function current(): ?Lembaga
    {
        $id = $this->getActiveLembagaId();

        if (! $id) {
            return null;
        }

        return Lembaga::query()->find($id);
    }
}
