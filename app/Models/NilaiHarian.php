<?php

namespace App\Models;

use App\Services\LembagaService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['kategori_nilai_harian_id', 'santri_id', 'semester_id', 'tanggal', 'nilai', 'catatan', 'user_id'])]
class NilaiHarian extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'nilai' => 'integer',
            'tanggal' => 'date',
        ];
    }

    public function scopeVisibleTo(Builder $query, ?User $user = null): Builder
    {
        $user ??= auth()->user();

        if (! $user) {
            return $query;
        }

        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return $query->when($activeLembagaId, function ($q) use ($activeLembagaId) {
            $q->whereHas('santri', fn ($s) => $s->where('lembaga_id', $activeLembagaId));
        });
    }

    public function kategoriNilaiHarian(): BelongsTo
    {
        return $this->belongsTo(KategoriNilaiHarian::class, 'kategori_nilai_harian_id');
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
