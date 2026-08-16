<?php

namespace App\Models;

use App\Services\LembagaService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['semester_id', 'lembaga_id', 'judul', 'tipe', 'mulai', 'selesai', 'warna', 'ikon', 'deskripsi', 'created_by'])]
class KalenderAkademik extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'mulai' => 'date',
            'selesai' => 'date',
        ];
    }

    public function scopeVisibleTo(Builder $query, ?User $user = null): Builder
    {
        $user ??= auth()->user();

        if (! $user) {
            return $query;
        }

        $activeLembagaId = app(LembagaService::class)->getActiveLembagaId();

        return $query->when($activeLembagaId, fn ($q) => $q->where(fn ($sub) => $sub->where('lembaga_id', $activeLembagaId)->orWhereNull('lembaga_id')));
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function lembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
