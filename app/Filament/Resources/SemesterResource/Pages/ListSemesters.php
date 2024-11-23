<?php

namespace App\Filament\Resources\SemesterResource\Pages;

use App\Filament\Resources\SemesterResource;
use Closure;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSemesters extends ListRecords
{
    protected static string $resource = SemesterResource::class;

    protected static ?string $title = 'Data Semester';

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return null;
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make()
            //     ->label('Tambah Semester')
            //     ->icon('heroicon-o-plus')
            //     ->color('success'),
        ];
    }
}
