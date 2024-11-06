<?php

namespace App\Filament\Resources\KelasResource\Pages;

use App\Filament\Resources\KelasResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKelas extends ManageRecords
{
    protected static string $resource = KelasResource::class;

    protected static ?string $title = 'Data Kelas';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kelas')
                ->icon('phosphor-plus')
                ->color('success')
                ->modalHeading('Tambah Kelas')
                ->modalSubmitActionLabel('Tambah Kelas')
                ->modalCancelActionLabel('Batal')
                ->createAnother(false),
        ];
    }
}
