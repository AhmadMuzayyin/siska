<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode', 'lembaga_id', 'nama', 'kitab', 'kkm'])]
class Mapel extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'kkm' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Mapel $mapel): void {
            if (empty($mapel->kode)) {
                $maxId = (int) (static::max('id') ?? 0) + 1;
                $mapel->kode = 'MPL-'.str_pad((string) $maxId, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function lembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class);
    }

    public function jadwalPelajarans(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }
}
