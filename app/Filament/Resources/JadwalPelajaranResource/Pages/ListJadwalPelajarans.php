<?php

namespace App\Filament\Resources\JadwalPelajaranResource\Pages;

use App\Filament\Resources\JadwalPelajaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwalPelajarans extends ListRecords
{
    protected static string $resource = JadwalPelajaranResource::class;
    protected static ?string $title = 'Jadwal Pelajaran';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Jadwal Pelajaran')
                ->color('success')
                ->icon('phosphor-plus-bold')
                ->createAnother(false),
        ];
    }
}
