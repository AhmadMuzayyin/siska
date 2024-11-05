<?php

namespace App\Filament\Resources\GuruResource\Pages;

use App\Filament\Resources\GuruResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageGurus extends ManageRecords
{
    protected static string $resource = GuruResource::class;
    protected static ?string $title = 'Data Guru';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Guru')
                ->icon('phosphor-plus')
                ->color('success')
                ->modalHeading('Tambah Guru')
                ->modalSubmitActionLabel('Tambah Guru')
                ->modalCancelActionLabel('Batal')
                ->createAnother(false),
        ];
    }
}
