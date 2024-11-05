<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;
    protected static ?string $navigationLabel = 'Pengaturan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Pengaturan')
                ->color('success')
                ->icon('phosphor-plus-bold')
                ->createAnother(false)
                ->hidden(fn(): bool => Setting::count() > 0),
        ];
    }
}
