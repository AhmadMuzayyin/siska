<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['guru_id', 'semester_id', 'bulan', 'tahun', 'bisyaroh', 'gaji_pokok', 'tunjangan', 'potongan', 'total_gaji', 'jumlah_hadir', 'tanggal_bayar', 'keterangan'])]
class GajiGuru extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'bulan' => 'integer',
            'tahun' => 'integer',
            'bisyaroh' => 'integer',
            'gaji_pokok' => 'integer',
            'tunjangan' => 'integer',
            'potongan' => 'integer',
            'total_gaji' => 'integer',
            'jumlah_hadir' => 'integer',
            'tanggal_bayar' => 'date',
        ];
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }
}
