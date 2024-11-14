<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $guarded = ['id'];

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id', 'id');
    }

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    protected static function booted()
    {
        static::deleting(function ($mapel) {
            if ($mapel->jadwalPelajaran->count() > 0) {
                Notification::make()
                    ->title('Gagal Menghapus Mapel')
                    ->body('Mapel tidak bisa dihapus karena sudah digunakan')
                    ->danger()
                    ->send();
                return false;
            }
        });
    }
}
