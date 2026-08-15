<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\SantriStatus;
use App\Enums\UserRole;
use App\Rules\IndonesianPhoneNumber;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

#[Fillable([
    'lembaga_id', 'kelas_id', 'noinduk', 'rfid_uid', 'nama_lengkap', 'nama_panggilan',
    'tempat_lahir', 'tanggal_lahir', 'anak_ke', 'alamat', 'jenis_kelamin',
    'nama_ayah', 'pendidikan_ayah', 'pekerjaan_ayah',
    'nama_ibu', 'pendidikan_ibu', 'pekerjaan_ibu',
    'telepon_wali', 'status', 'notification_read_at',
])]
class Santri extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'anak_ke' => 'integer',
            'jenis_kelamin' => Gender::class,
            'status' => SantriStatus::class,
            'notification_read_at' => 'datetime',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', SantriStatus::Aktif);
    }

    public function lembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function spps(): HasMany
    {
        return $this->hasMany(Spp::class);
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public static function validationRules(?self $santri = null): array
    {
        return [
            'lembaga_id' => ['nullable', 'integer', 'exists:lembagas,id'],
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
            'noinduk' => ['required', 'string', Rule::unique('santris', 'noinduk')->ignore($santri)],
            'rfid_uid' => ['nullable', 'string', Rule::unique('santris', 'rfid_uid')->ignore($santri)],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nama_panggilan' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'anak_ke' => ['required', 'integer', 'min:1', 'max:255'],
            'alamat' => ['required', 'string'],
            'jenis_kelamin' => ['required', Rule::enum(Gender::class)],
            'nama_ayah' => ['required', 'string', 'max:255'],
            'pendidikan_ayah' => ['required', 'string', 'max:255'],
            'pekerjaan_ayah' => ['required', 'string', 'max:255'],
            'nama_ibu' => ['required', 'string', 'max:255'],
            'pendidikan_ibu' => ['required', 'string', 'max:255'],
            'pekerjaan_ibu' => ['required', 'string', 'max:255'],
            'telepon_wali' => ['required', 'string', new IndonesianPhoneNumber],
            'status' => ['required', Rule::enum(SantriStatus::class)],
        ];
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if (in_array($user->role, [UserRole::Admin, UserRole::Keuangan, UserRole::KepalaMadrasah], true)) {
            return $query;
        }

        return $query->whereHas('kelas.waliKelas.guru', function (Builder $guruQuery) use ($user) {
            $guruQuery->where('user_id', $user->id);
        });
    }
}
