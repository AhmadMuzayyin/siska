<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::deleting(function ($gallery) {
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
        });
    }
}
