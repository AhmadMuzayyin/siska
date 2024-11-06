<?php

namespace App\Filament\Resources\MapelResource\Pages;

use App\Filament\Resources\MapelResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMapels extends ManageRecords
{
    protected static string $resource = MapelResource::class;

    protected static ?string $title = 'Data Mapel';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Mapel')
                ->icon('phosphor-plus')
                ->color('success')
                ->modalHeading('Tambah Mapel')
                ->modalSubmitActionLabel('Tambah Mapel')
                ->modalCancelActionLabel('Batal')
                ->createAnother(false),
        ];
    }
}
