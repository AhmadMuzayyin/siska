<?php

namespace App\Filament\Resources\JadwalPelajaranResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\JadwalPelajaranResource;

class CreateJadwalPelajaran extends CreateRecord
{
    protected static string $resource = JadwalPelajaranResource::class;
    protected static ?string $title = 'Tambah Jadwal Pelajaran';
    public static function canCreateAnother(): bool
    {
        return false;
    }
    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Simpan')
            ->color('success')
            ->action('create');
    }
    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label('Batal')
            ->color('gray')
            ->action(function () {
                return redirect()->route('filament.admin.resources.jadwal-pelajarans.index');
            });
    }
}
