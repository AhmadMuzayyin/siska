<?php

namespace App\Filament\Resources\AbsensiGuruResource\Pages;

use App\Filament\Resources\AbsensiGuruResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAbsensiGurus extends ManageRecords
{
    protected static string $resource = AbsensiGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah')
                ->color('success')
                ->icon('phosphor-plus-bold'),
        ];
    }
}
