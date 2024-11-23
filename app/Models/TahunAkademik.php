<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $guarded = ['id'];

    public function semester()
    {
        return $this->hasMany(Semester::class);
    }

    protected static function booted()
    {
        static::deleting(function ($tahunAkademik) {
            if ($tahunAkademik->semester->count() > 0) {
                Notification::make()
                    ->title('Gagal Menghapus Tahun Akademik')
                    ->body('Tahun Akademik tidak bisa dihapus karena sudah digunakan')
                    ->danger()
                    ->send();

                return false;
            }
        });
    }
}
