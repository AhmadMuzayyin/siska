<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Resources\Pages\ManageRecords;

class ManageContacts extends ManageRecords
{
    protected static string $resource = ContactResource::class;

    protected static ?string $title = 'Kontak';

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
