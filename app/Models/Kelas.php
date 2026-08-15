<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['lembaga_id', 'nama', 'kapasitas'])]
class Kelas extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
        ];
    }

    public function lembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class);
    }

    public function santris(): HasMany
    {
        return $this->hasMany(Santri::class);
    }

    public function waliKelas(): HasOne
    {
        return $this->hasOne(WaliKelas::class);
    }

    public function jadwalPelajarans(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class);
    }
}
