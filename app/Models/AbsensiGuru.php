<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    protected $guarded = ['id'];
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
