<?php

namespace App\Models;

use App\Services\LembagaService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['lembaga_id', 'kode', 'nama', 'bobot', 'is_wajib', 'keterangan'])]
class KategoriNilaiHarian extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'bobot' => 'integer',
            'is_wajib' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (KategoriNilaiHarian $kategori) {
            if (empty($kategori->kode)) {
                $maxId = (int) static::query()->max('id') + 1;
                $kategori->kode = 'KNH-'.str_pad((string) $maxId, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function scopeVisibleTo(Builder $query, ?User $user = null): Builder
    {
        $user ??= auth()->user();

        if (! $user) {
            return $query;
        }

        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return $query->when($activeLembagaId, fn ($q) => $q->where('lembaga_id', $activeLembagaId));
    }

    public function lembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class);
    }

    public function nilaiHarians(): HasMany
    {
        return $this->hasMany(NilaiHarian::class);
    }
}
