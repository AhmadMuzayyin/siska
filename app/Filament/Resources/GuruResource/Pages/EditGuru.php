<?php

namespace App\Filament\Resources\GuruResource\Pages;

use App\Filament\Resources\GuruResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditGuru extends EditRecord
{
    protected static string $resource = GuruResource::class;

    protected static ?string $title = 'Edit Data Guru';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')->label('Perbarui')->color('success'),
            Action::make('cancel')
                ->label('Batal')
                ->url(fn () => GuruResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}
