<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property string $jenjang
 * @property string|null $nsm
 * @property string|null $kepala_lembaga
 * @property string|null $alamat
 * @property string|null $telepon
 * @property bool $is_active
 * @property int $urutan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['kode', 'nama', 'jenjang', 'nsm', 'kepala_lembaga', 'alamat', 'telepon', 'is_active', 'urutan'])]
class Lembaga extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    /**
     * Scope query to active lembagas.
     *
     * @param  Builder<Lembaga>  $query
     * @return Builder<Lembaga>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope query ordered by urutan.
     *
     * @param  Builder<Lembaga>  $query
     * @return Builder<Lembaga>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('nama');
    }

    /**
     * @return HasMany<Kelas, $this>
     */
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }

    /**
     * @return HasMany<Santri, $this>
     */
    public function santris(): HasMany
    {
        return $this->hasMany(Santri::class);
    }

    /**
     * @return HasMany<Mapel, $this>
     */
    public function mapels(): HasMany
    {
        return $this->hasMany(Mapel::class);
    }
}
