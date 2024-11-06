<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected static ?string $title = 'Edit Data Pengaturan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Perbarui')
            ->color('success')
            ->action('save');
    }

    protected function getCloseFormAction(): Action
    {
        return Action::make('close')
            ->label('Batal')
            ->color('gray')
            ->action('close');
    }
}
