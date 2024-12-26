<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Spp extends Model
{
    protected $guarded = ['id'];

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
    protected static function booted()
    {
        static::addGlobalScope('wali_kelas', function (Builder $query) {
            if (Auth::check() && Auth::user()->role === 'guru') {
                $query->whereHas('santri.kelas.waliKelas', function ($query) {
                    $query->where('guru_id', Auth::user()->guru->id);
                });
            }
        });
    }
}
