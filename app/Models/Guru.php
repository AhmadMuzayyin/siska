<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\GuruStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['user_id', 'alamat', 'whatsapp', 'gender', 'foto', 'status', 'rfid_uid', 'pendidikan_terakhir'])]
class Guru extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'status' => GuruStatus::class,
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', GuruStatus::Aktif);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function waliKelas(): HasOne
    {
        return $this->hasOne(WaliKelas::class);
    }

    public function jadwalPelajarans(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function absensiGurus(): HasMany
    {
        return $this->hasMany(AbsensiGuru::class);
    }

    public function gajiGurus(): HasMany
    {
        return $this->hasMany(GajiGuru::class);
    }
}
