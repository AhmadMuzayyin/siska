<?php

namespace App\Filament\Resources\JadwalPelajaranResource\Pages;

use App\Filament\Resources\JadwalPelajaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageJadwalPelajarans extends ManageRecords
{
    protected static string $resource = JadwalPelajaranResource::class;

    protected static ?string $title = 'Jadwal Pelajaran';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('Print')
                ->color('info')
                ->icon('heroicon-o-printer')
                ->url(route('jadwal.print'), shouldOpenInNewTab: true),
            Actions\CreateAction::make()
                ->label('Tambah Jadwal Pelajaran')
                ->modalHeading('Tambah Jadwal Pelajaran')
                ->modalSubmitActionLabel('Tambah')
                ->modalCancelActionLabel('Batal')
                ->color('success')
                ->icon('phosphor-plus')
                ->createAnother(false),
        ];
    }
}
