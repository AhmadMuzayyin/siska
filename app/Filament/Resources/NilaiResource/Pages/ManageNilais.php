<?php

namespace App\Filament\Resources\NilaiResource\Pages;

use App\Filament\Resources\NilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;

class ManageNilais extends ManageRecords
{
    protected static string $resource = NilaiResource::class;

    protected static ?string $title = 'Data Nilai';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print')
                ->label('Print')
                ->button()
                ->color('info')
                ->icon('phosphor-printer')
                ->modalContent(fn ($record) => view('Nilai.modal'))
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->hidden(function () {
                    if (Auth::user()->role != 'admin') {
                        return true;
                    }

                    return false;
                }),
            Actions\CreateAction::make()
                ->label('Tambah Nilai')
                ->icon('phosphor-plus')
                ->color('success')
                ->modalHeading('Tambah Nilai')
                ->modalSubmitActionLabel('Tambah')
                ->modalCancelActionLabel('Batal')
                ->createAnother(false),
        ];
    }
}
