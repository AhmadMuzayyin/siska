<?php

namespace App\Filament\Resources\WaliKelasResource\Pages;

use App\Filament\Resources\WaliKelasResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageWaliKelas extends ManageRecords
{
    protected static string $resource = WaliKelasResource::class;

    protected static ?string $title = 'Wali Kelas';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah')
                ->modalHeading('Tambah Data Wali Kelas')
                ->modalSubmitActionLabel('Tambah')
                ->modalCancelActionLabel('Batal')
                ->color('success')
                ->icon('heroicon-o-plus'),
        ];
    }
}
