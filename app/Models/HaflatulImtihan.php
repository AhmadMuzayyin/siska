<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['santri_id', 'semester_id', 'nominal', 'tanggal', 'metode_pembayaran', 'keterangan'])]
class HaflatulImtihan extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'nominal' => 'integer',
            'tanggal' => 'date',
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
