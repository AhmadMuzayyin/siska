<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GajiGuru extends Model
{
    protected $guarded = false;

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
