<?php

namespace App\Filament\Resources\SantriResource\Pages;

use Filament\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\SantriResource;

class EditSantri extends EditRecord
{
    protected static string $resource = SantriResource::class;
    protected static ?string $title = 'Edit Data Santri';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus')
                ->modalHeading('Hapus Data Santri')
                ->modalDescription('Apakah anda yakin ingin menghapus data ini?')
                ->modalCancelActionLabel('Batal')
                ->modalSubmitActionLabel('Hapus'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')->label('Perbarui')->color('success'),
            Action::make('cancel')
                ->label('Batal')
                ->url(fn () => SantriResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}
