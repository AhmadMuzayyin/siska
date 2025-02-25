<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    protected $guarded = ['id'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }
    public function spp()
    {
        return $this->hasMany(Spp::class);
    }

    protected static function booted()
    {
        static::creating(function ($santri) {
            if ($santri->kelas_id) {
                $santri->kelas->update(['terisi' => $santri->kelas->terisi + 1]);
            }
        });
        static::deleting(function ($santri) {
            if ($santri->kelas_id) {
                $santri->kelas->update(['terisi' => $santri->kelas->terisi - 1]);
            }
        });
    }
}
