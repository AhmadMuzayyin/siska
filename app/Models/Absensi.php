<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $guarded = ['id'];

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function jadwalPelajaran()
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }
    protected static function booted()
    {
        static::deleting(function ($absensi) {
            if ($absensi->jadwalPelajaran) {
                return false;
            }
        });
    }
}
