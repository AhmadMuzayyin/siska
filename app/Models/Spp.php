<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['santri_id', 'semester_id', 'bulan', 'tahun', 'jumlah', 'nominal', 'is_paid', 'paid_at', 'tanggal', 'tanggal_bayar', 'keterangan'])]
class Spp extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'bulan' => 'integer',
            'tahun' => 'integer',
            'jumlah' => 'integer',
            'nominal' => 'integer',
            'is_paid' => 'boolean',
            'paid_at' => 'datetime',
            'tanggal' => 'date',
            'tanggal_bayar' => 'date',
        ];
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }
}
