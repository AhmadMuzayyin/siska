<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Edit Data Pengguna';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')->label('Perbarui')->color('success')->action('save'),
            Action::make('cancel')
                ->label('Batal')
                ->url(fn () => UserResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}
