<?php

namespace App\Filament\Resources\SantriResource\Pages;

use App\Filament\Resources\SantriResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListSantris extends ListRecords
{
    protected static string $resource = SantriResource::class;

    protected static ?string $title = 'Data Santri';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Santri')
                ->icon('phosphor-plus')
                ->color('success')
                ->hidden(fn($record) => Auth::user()->role == 'guru'),
        ];
    }
}
