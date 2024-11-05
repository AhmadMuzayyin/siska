<?php

namespace App\Filament\Resources\TahunAkademikResource\Pages;

use App\Filament\Resources\TahunAkademikResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTahunAkademiks extends ManageRecords
{
    protected static string $resource = TahunAkademikResource::class;
    protected static ?string $title = 'Tahun Akademik';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Tahun Akademik')
                ->modalHeading('Tambah Tahun Akademik')
                ->modalSubmitActionLabel('Tambah')
                ->modalCancelActionLabel('Batal')
                ->color('success')
                ->icon('phosphor-plus-bold')
                ->createAnother(false),
        ];
    }
}
