<?php

namespace App\Filament\Resources\AbsensiResource\Pages;

use App\Filament\Resources\AbsensiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAbsensis extends ListRecords
{
    protected static string $resource = AbsensiResource::class;

    protected static ?string $title = 'Absensi Santri';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Absensi')
                ->color('success')
                ->icon('phosphor-plus-bold')
                ->url(fn () => route('absensi.create'))
                ->createAnother(false),
        ];
    }
}
