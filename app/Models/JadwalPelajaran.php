<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    protected $guarded = ['id'];
    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class);
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
