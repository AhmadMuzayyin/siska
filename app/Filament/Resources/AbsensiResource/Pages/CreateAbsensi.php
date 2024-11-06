<?php

namespace App\Filament\Resources\AbsensiResource\Pages;

use App\Filament\Resources\AbsensiResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateAbsensi extends CreateRecord
{
    protected static string $resource = AbsensiResource::class;

    protected static ?string $title = 'Tambah Absensi';

    public static function canCreateAnother(): bool
    {
        return false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create_and_create_another')
            ->label('Simpan')
            ->color('success')
            ->action('create_and_create_another')
            ->hidden()->disabled();
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label('Batal')
            ->color('gray')
            ->action(function () {
                return redirect()->route('filament.admin.resources.absensis.index');
            })->hidden()->disabled();
    }
}
