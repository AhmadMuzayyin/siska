<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
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

    public function jadwalPelajaran()
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }
}
