<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $guarded = ['id'];
    protected static function booted()
    {
        static::deleting(function ($setting) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            if ($setting->favicon) {
                Storage::disk('public')->delete($setting->favicon);
            }
        });
    }
}
