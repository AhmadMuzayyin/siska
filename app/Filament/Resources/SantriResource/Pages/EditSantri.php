<?php

namespace App\Filament\Resources\SantriResource\Pages;

use App\Filament\Resources\SantriResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditSantri extends EditRecord
{
    protected static string $resource = SantriResource::class;

    protected static ?string $title = 'Edit Data Santri';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Baru')
                ->color('info')
                ->url(fn () => SantriResource::getUrl(('create'))),
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
            Action::make('save')->label('Perbarui')->color('success')->action('save'),
            Action::make('cancel')
                ->label('Batal')
                ->url(fn () => SantriResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}
