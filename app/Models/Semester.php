<?php

namespace App\Models;

use App\Enums\SemesterType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['tahun_akademik_id', 'tipe', 'mulai', 'selesai', 'is_aktif'])]
class Semester extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'tipe' => SemesterType::class,
            'mulai' => 'date',
            'selesai' => 'date',
            'is_aktif' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_aktif', true);
    }

    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    public function jadwalPelajarans(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function absensiGurus(): HasMany
    {
        return $this->hasMany(AbsensiGuru::class);
    }

    public function gajiGurus(): HasMany
    {
        return $this->hasMany(GajiGuru::class);
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public function spps(): HasMany
    {
        return $this->hasMany(Spp::class);
    }
}
