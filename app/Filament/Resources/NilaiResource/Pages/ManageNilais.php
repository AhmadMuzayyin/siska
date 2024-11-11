<?php

namespace App\Filament\Resources\NilaiResource\Pages;

use App\Filament\Resources\NilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNilais extends ManageRecords
{
    protected static string $resource = NilaiResource::class;

    protected static ?string $title = 'Data Nilai';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Nilai')
                ->icon('phosphor-plus')
                ->color('success')
                ->modalHeading('Tambah Nilai')
                ->modalSubmitActionLabel('Tambah')
                ->modalCancelActionLabel('Batal')
                ->createAnother(false),
        ];
    }
}
