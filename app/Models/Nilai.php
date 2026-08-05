<?php

namespace App\Models;

use App\Enums\Predikat;
use App\Services\PredikatCalculator;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['semester_id', 'santri_id', 'mapel_id', 'nilai', 'nilai_angka', 'predikat', 'catatan'])]
class Nilai extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'nilai' => 'integer',
            'nilai_angka' => 'integer',
            'predikat' => Predikat::class,
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Nilai $model) {
            if ($model->nilai !== null) {
                $model->predikat = PredikatCalculator::calculate((int) $model->nilai);
            }
        });
    }

    public function getIsLulusAttribute(): bool
    {
        return $this->predikat !== Predikat::E;
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class);
    }
}
