<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $guarded = ['id'];

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    protected static function booted()
    {
        static::deleting(function ($tahunAkademik) {
            if ($tahunAkademik->jadwalPelajaran) {
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
