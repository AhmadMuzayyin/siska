<?php

namespace App\Models;

use App\Enums\StudentAttendanceStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['lembaga_id', 'semester_id', 'jadwal_pelajaran_id', 'santri_id', 'tanggal', 'status', 'keterangan'])]
class Absensi extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'status' => StudentAttendanceStatus::class,
        ];
    }

    public function lembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function jadwalPelajaran(): BelongsTo
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }
}
