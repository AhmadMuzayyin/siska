<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['mapel_id', 'deskripsi_a', 'deskripsi_b', 'deskripsi_c', 'deskripsi_d', 'template_path'])]
class SettingRapor extends Model
{
    use HasFactory;

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class);
    }
}
