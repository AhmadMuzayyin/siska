<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['santri_id', 'tipe', 'nominal', 'saldo_akhir', 'tanggal', 'keterangan', 'user_id'])]
class Tabungan extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'nominal' => 'integer',
            'saldo_akhir' => 'integer',
            'tanggal' => 'date',
        ];
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
