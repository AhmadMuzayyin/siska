<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
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

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
}
