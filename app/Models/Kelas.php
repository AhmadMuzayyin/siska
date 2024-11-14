<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $guarded = ['id'];

    public function santri()
    {
        return $this->hasMany(Santri::class);
    }

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    protected static function booted()
    {
        static::deleting(function ($kelas) {
            if ($kelas->jadwalPelajaran->count() > 0) {
                Notification::make()
                    ->title('Gagal Menghapus Kelas')
                    ->body('Kelas tidak bisa dihapus karena sudah digunakan')
                    ->danger()
                    ->send();
                return false;
            }
        });
    }
}
