<?php

namespace App\Models;

use Database\Factories\ProgramFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $lembaga_id
 * @property string $nama_program
 * @property string|null $kategori_badge
 * @property string $deskripsi_singkat
 * @property array<int, array{judul: string, deskripsi: string}>|null $materi_unggulan
 * @property string|null $gambar_url
 * @property string|null $icon
 * @property int $urutan
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Lembaga|null $lembaga
 */
#[Fillable([
    'lembaga_id',
    'nama_program',
    'kategori_badge',
    'deskripsi_singkat',
    'materi_unggulan',
    'gambar_url',
    'icon',
    'urutan',
    'is_active',
])]
class Program extends Model
{
    /** @use HasFactory<ProgramFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'lembaga_id' => 'integer',
            'materi_unggulan' => 'array',
            'urutan' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope for active programs.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for sorting by order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Relationship to Lembaga.
     */
    public function lembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class);
    }
}
