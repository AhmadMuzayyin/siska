<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Guru extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    protected static function booted()
    {
        static::deleting(function ($guru) {
            if ($guru->jadwalPelajaran->count() > 0) {
                Notification::make()
                    ->title('Gagal Menghapus Guru')
                    ->body('Guru tidak bisa dihapus karena sudah memiliki jadwal pelajaran')
                    ->danger()
                    ->send();
                return false;
            } else {
                if ($guru->foto) {
                    Storage::disk('public')->delete($guru->foto);
                }
                if ($guru->user) {
                    $guru->user->delete();
                }
            }
        });
    }
}
