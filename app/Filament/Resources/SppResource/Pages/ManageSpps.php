<?php

namespace App\Filament\Resources\SppResource\Pages;

use App\Filament\Resources\SppResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSpps extends ManageRecords
{
    protected static string $resource = SppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
