<?php

namespace App\Filament\Resources\JadwalPelajaranResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\JadwalPelajaranResource;

class EditJadwalPelajaran extends EditRecord
{
    protected static string $resource = JadwalPelajaranResource::class;
    protected static ?string $title = 'Edit Jadwal Pelajaran';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Perbarui')
            ->color('success')
            ->action('save');
    }
    protected function getCloseFormAction(): Action
    {
        return Action::make('close')
            ->label('Batal')
            ->color('gray')
            ->action('close');
    }
}
