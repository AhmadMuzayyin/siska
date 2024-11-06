<?php

namespace App\Filament\Resources\SppResource\Pages;

use App\Filament\Resources\SppResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSpps extends ManageRecords
{
    protected static string $resource = SppResource::class;

    protected static ?string $title = 'Data Pembayaran SPP';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Pembayaran')
                ->icon('phosphor-plus')
                ->color('success')
                ->modalHeading('Tambah Pembayaran')
                ->modalSubmitActionLabel('Tambah Pembayaran')
                ->modalCancelActionLabel('Batal')
                ->createAnother(false),
        ];
    }
}
